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
        $this->fillTestScores();
        $this->fillAverages();
    }

    private function fillTestScores() {
        $adjudicators = Adjudicator::get();
        foreach ($adjudicators as $adjudicator) {
            $this->test_scores[$adjudicator->id] = $adjudicator->test_score;
        }
    }

    private function fillAverages() {
        $adjudicators = Adjudicator::get();
        $rounds = Round::get();

        foreach ($adjudicators as $adjudicator) {
            foreach ($rounds as $round) {
                $avg = \DB::table('feedbacks')->where('round_id', $round->id)
                    ->where('evaluatee_id', $adjudicator->id)->avg('score');

                $this->averages[$adjudicator->id][$round->id] = $avg;
            }

            $sum = 0; 
            $count = 0;
            foreach ($this->averages[$adjudicator->id] as $avg) {
                if (isset($avg)) {
                    $sum += $avg;
                    $count++;
                }
            } 
            $sum += $this->test_scores[$adjudicator->id];
            $count++;
            $this->averages[$adjudicator->id]['round'] =
                $count !== 0 ? $sum / $count : 0;

            $fbs = Feedback::where('evaluatee_id', $adjudicator->id)->get();
            $sum = 0; 
            $count = 0;
            foreach ($fbs as $fb) {
                $sum += $fb->score;
                $count++;
            } 
            $sum += $this->test_scores[$adjudicator->id];
            $count++;
            $this->averages[$adjudicator->id]['feedback'] =
                $count !== 0 ? $sum / $count : 0;
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
        $adjudicators = Adjudicator::get();
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
