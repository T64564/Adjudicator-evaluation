<?php

namespace App\Http\Models;

/*
 * Feedback Score Ranking
 */
class Ranking {
    public $test_scores;

    /**
     * adjudicator_id =>
     *      round_id(unsiged int)    => average score of the round
     *      'round'                  => average of average scores of each round
     *      'feedback'               => average score of each feedback
     *
     */
    public $averages;

    public function __construct() {
        $this->getTestScores();
        $this->getAverages();
    }

    private function getTestScores() {
        $adjudicators = Adjudicator::where('active', 1)->get();
        foreach ($adjudicators as $adjudicator) {
            $this->test_scores[$adjudicator->id] = $adjudicator->test_score;
        }
    }

    private function getAverages() {
        $adjudicators = Adjudicator::where('active', 1)->get();
        $rounds = Round::get();

        foreach ($adjudicators as $adjudicator) {
            foreach ($rounds as $round) {
                $avg = \DB::table('feedbacks')->where('round_id', $round->id)
                    ->where('evaluatee_id', $adjudicator->id)->avg('score');

                $this->averages[$adjudicator->id][$round->id] = $avg;
            }

            $sum_score_round = 0; 
            $n_round = 0;
            $test_score = $this->test_scores[$adjudicator->id];

            foreach ($this->averages[$adjudicator->id] as $avg) {
                if (isset($avg)) {
                    $sum_score_round += $avg;
                    $n_round += 1;
                }
            } 

            $this->averages[$adjudicator->id]['round'] =
                ($sum_score_round + $test_score) / ($n_round + 1);

            $fbs = Feedback::where('evaluatee_id', $adjudicator->id)->get();
            $sum_score_fb = 0; 
            $n_fb = 0;
            foreach ($fbs as $fb) {
                $sum_score_fb += $fb->score;
                $n_fb += 1;
            }
            $sum_score_fb += $test_score;
            $n_fb += 1;
            $this->averages[$adjudicator->id]['feedback'] =
                $n_fb !== 0 ? $sum_score_fb / $n_fb : 0;


            $this->averages[$adjudicator->id]['4:6'] =
                $n_round !== 0 ? 
                $test_score * 0.4 + ($sum_score_round / $n_round) * 0.6 :
                $test_score * 0.4;

            $this->averages[$adjudicator->id]['2:8'] =
                $n_round !== 0 ? 
                $test_score * 0.2 + ($sum_score_round / $n_round) * 0.8 :
                $test_score * 0.2;

            $this->averages[$adjudicator->id]['ignore_test'] =
                $n_round !== 0 ? 
                $sum_score_round / $n_round
                : 0;
        }
    }


    public function getTableHeader() {
        $rounds = Round::get();
        $heads = [];
        $heads[] = 'Name';
        $heads[] = 'Test score';
        foreach ($rounds as $round) {
            $heads[] = $round->name . ' avg';
        }
        $heads[] = 'Avg of each avg of round';
        $heads[] = 'Avg of each feedback';
        $heads[] = '(test:round) = (4:6)';
        $heads[] = '(test:round) = (2:8)';
        $heads[] = 'Ignore test score';
        return $heads;
    }

    /*
     * Name
     * Test score
     * Round
     * ...
     * Avg1
     * Avg2
     *
     */
    public function getListForCsvExport() {
        $rankings = new Ranking();
        $adjudicators = Adjudicator::where('active', 1)->get();
        $rounds = Round::get();
        $list = [];

        foreach ($adjudicators as $adjudicator) {
            $list[$adjudicator->id][] = $adjudicator->name;
            $list[$adjudicator->id][] = $rankings->test_scores[$adjudicator->id];
            foreach ($rankings->averages[$adjudicator->id] as $avg) {
                $list[$adjudicator->id][] = $avg;
            }
        }
        return $list;
    }
}
