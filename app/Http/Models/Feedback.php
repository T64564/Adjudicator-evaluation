<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {
    protected $fillable = [
        'type', 'round_id', 
        'evaluatee_id', 'evaluator_id', 'score'
    ];

    private $types = [
        0 => 'Team to oralist',
        1 => 'Chair to panelist',
        2 => 'Panelist to Chair',
        3 => 'Chair to trainee',
    ];

    public $type_name;
    public $evaluatee_name;
    public $evaluator_name;

    public static function getTypes() {
        $feedback = new Feedback();
        return $feedback->types;
    }

    public static function getTypeName($type) {
        $feedback = new Feedback();
        return $feedback->types[$type];
    }

    public static function getTableHeader() {
        $tableHeader = 
            ['Id', 'Type', 'Evaluator', 'Evaluatee', 'Score', 'Edit', 'Delete'];
        return $tableHeader;
    }

    public static function getListing($round_id) {
        $feedbacks = Feedback::where('round_id', $round_id)
            ->orderBy('type')->get();

        foreach ($feedbacks as $feedback) {
            $feedback->type_name = $feedback->getTypeName($feedback->type);
            if ($feedback->type === 0) {
                /* evaluator is team */
                $feedback->evaluator_name = 
                    Team::findOrFail($feedback->evaluator_id)->name;
            } else {
                /* evaluator is adjudicator */
                $feedback->evaluator_name = 
                    Adjudicator::findOrFail($feedback->evaluator_id)->name;
            }
            $feedback->evaluatee_name = 
                Adjudicator::findOrFail($feedback->evaluatee_id)->name;
        }
        return $feedbacks;
    }

    /*
     * Check if
     * - the score is valid.
     * - a team does not submit feedback in a silent round
     * - the team submit more than once.
     * - the evaluator and the evaluatee are the same adjudicator.
     * - combination (type, evaluator_id, evaluatee_id) is unique
     *
     */
    public static function validateRequest($request) {
        $errors = [];

        $score_rule = config('validations.feedbacks.score');
        $min_score = preg_replace(
            ['/.*between:/', '/,.*/'], ['', ''], $score_rule);
        $max_score = (float)preg_replace(
            ['/.*between:[0-9]*,/', '/\|.*/'], ['', ''], $score_rule);
        $max_score = (float)preg_replace('/\|.*/', '', $max_score);
        if ($request['score'] < $min_score || $max_score < $request['score']) {
            $errors[] = 
                'Score must be between ' . $min_score . ' and ' . $max_score; 
        }

        if ($request['type'] === '0') {
            if (Round::findOrFail($request['round_id'])->silent == 1) {
                $errors[] = 'Any team may not evaluate in a silent round.';
            }

            // ここICUTのときコメントアウトしてたけど理由覚えてない
            // ->where('evaluatee_id', $request['evaluator_id'])
            // を書き忘れた?
            if (Feedback::where('round_id', $request['round_id'])
                ->where('type', 0)->whereNotIn('id', [$request['id']])
                ->where('evaluatee_id', $request['evaluator_id'])
                ->where('evaluator_id', $request['evaluator_id'])
                ->exists()) {
                $errors[] = 
                    'This team has already submitted a feedback to '
                    . Adjudicator::
                    findOrFail($request['evaluatee_id'])->name
                    . '.';
            }
        } else {
            if ($request['evaluator_id'] === $request['evaluatee_id']) {
                $errors[] = 'The evaluatee and the evaluator are the same id.';
            }

            if (Feedback::where('round_id', $request['round_id'])
                ->whereNotIn('type', [0])->whereNotIn('id', [$request['id']])
                ->where('evaluatee_id', $request['evaluatee_id'])
                ->where('evaluator_id', $request['evaluator_id'])
                ->exists()) {
                $errors[] = 
                    Adjudicator::findOrFail($request['evaluator_id'])->name 
                    . ' has already submitted a feedback to '
                    . Adjudicator::findOrFail($request['evaluatee_id'])->name 
                    . '.';
            }
        }

        return $errors;
    }

    /*
     * Check if
     * - teams that don't submit 
     * - teams that submit in a silent round.
     * - adjudicators that don't evaluate or isn't evaluated in non-silent round.
     * - chairs that submit odd feedbacks to panelist.
     * - chairs that submit feedback to another chair.
     * - chairs that evaluate as a panelist.
     * - chairs that aren't evaluated by panelists that they have evaluated.
     * - panelists that evaluate more than once.
     * - panelists that evaluate another panelist.
     * - panelists that evaluate as a chair.
     * - panelists that aren't evaluated by chair that they have evaluated.
     * - trainees that evaluate.
     *
     * infomation
     * - 
     */
    public static function checkConsistencyAsian($round_id, &$info, &$warning, &$errors) {
        $info = [];
        $errors = [];
        $adjs = Adjudicator::where('active', 1)->get();
        $teams = Team::where('active', 1)->get();
        $round = Round::findOrFail($round_id);
        $fbs = Feedback::where('round_id', $round_id)->get();

        foreach ($teams as $team) {
            if ($round->silent == 0) {
                $count = $fbs->where('type', 0)->where('evaluator_id', $team->id)->count();
                if ($count == 0) {
                    $errors[] = $team->name 
                        . ' doesn\'t evaluate anyone.';
                }
                if ($count > 1) {
                    $errors[] = $team->name 
                        . ' evaluate ' . $count . ' times.';
                }
            } else {
                if ($fbs->where('type', 0)->contains('evaluator_id', $team->id)) {
                    $errors[] = $team->name 
                        . ' may not evaluate anyone in a silent round.';
                }
            }
        }

        foreach ($adjs as $adj) {
            if ($round->silent == 0) {
                if (!$fbs->contains('evaluatee_id', $adj->id)
                    && !$fbs->whereIn('type', [1, 2, 3])
                    ->contains('evaluator_id', $adj->id)) {
                    $errors[] = $adj->name 
                        . ' doesn\'t evaluate and isn\'t evaluated by anyone.';
                }

                $evaluated_count = 
                    $fbs->where('type', 0)->where('evaluatee_id', $adj->id)
                    ->count(); 

                if ($evaluated_count !== 0) {
                    $info[] = $adj->name
                        . ' is evaluated '
                        . $evaluated_count
                        . ' times by teams.';
                }
            } else {
                $evaluated_count = 
                    $fbs->whereIn('type', [1, 2, 3])
                    ->where('evaluatee_id', $adj->id)
                    ->count(); 
                if ($evaluated_count === 0) {
                    $info[] = $adj->name
                        . ' isn\'t evaluated by anyone.';
                }
            }
        }

        $fbs_by_chair = $fbs->whereIn('type', [1, 3]);

        foreach ($fbs_by_chair as $fb) {
            $submit_count = $fbs->where('type', 1)
                ->where('evaluator_id', $fb->evaluator_id)
                ->count();
            if ($submit_count % 2 !== 0) {
                $errors[] = 'Chair (' 
                    . Adjudicator::findOrFail($fb->evaluator_id)->name
                    . ') submits feedbacks to panelists odd number ('
                    . $submit_count
                    . ') of times.';
            }

            if ($fbs->whereIn('type', [1, 3])
                ->contains('evaluator_id', $fb->evaluatee_id)) {
                $errors[] = 'Chair (' 
                    . Adjudicator::findOrFail($fb->evaluator_id)->name
                    . ') submits feedbacks to another chair panel ('
                    . Adjudicator::findOrFail($fb->evaluatee_id)->name
                    . ').';
            }

            if ($fbs->where('type', 2)
                ->contains('evaluator_id', $fb->evaluator_id)) {
                $errors[] = 'Chair (' 
                    . Adjudicator::findOrFail($fb->evaluator_id)->name
                    . ') evaluate '
                    . Adjudicator::findOrFail($fb->evaluatee_id)->name
                    . ' as a panelist.';
            }

            if ($fb->type === 1 
                && !$fbs->where('type', 2)
                ->contains('evaluator_id', $fb->evaluatee_id)) {
                \Debugbar::info($fb);
                \Debugbar::info($fbs->where('type', 2));
                $chair_name = 
                    Adjudicator::findOrFail($fb->evaluator_id)->name;
                $panel_name =
                    Adjudicator::findOrFail($fb->evaluatee_id)->name;
                $errors[] = 'Chair (' 
                    . $chair_name 
                    . ') evaluate panelist ('
                    . $panel_name 
                    . '), but the panelist hasn\'t evaluate the chair.';
            }

            if ($fb->type === 3 &&
                $fbs->whereIn('type', [1, 2, 3])
                ->contains('evaluator_id', $fb->evaluatee_id)) {
                $chair_name = 
                    Adjudicator::findOrFail($fb->evaluator_id)->name;
                $trainee_name =
                    Adjudicator::findOrFail($fb->evaluatee_id)->name;
                $errors[] = 'Chair (' 
                    . $chair_name 
                    . ') evaluate trainee('
                    . $trainee_name 
                    . '), but the trainee evaluate an adjudicator.';
            }

        }

        $fbs_by_panel = $fbs->where('type', 2);
        foreach ($fbs_by_panel as $fb) {
            if ($fbs->where('type', 2)
                ->where('evaluator_id', $fb->evaluator_id)
                ->count() > 1) {
                $errors[] = 'Panelist (' 
                    . Adjudicator::findOrFail($fb->evaluator_id)->name
                    . ') submits feedbacks more than once.';
            }

            if ($fbs->where('type', 2)
                ->contains('evaluator_id', $fb->evaluatee_id)) {
                $errors[] = 'Panelist (' 
                    . Adjudicator::findOrFail($fb->evaluator_id)->name
                    . ') submits feedbacks to another panelist ('
                    . Adjudicator::findOrFail($fb->evaluatee_id)->name
                    . ').';
            }

            if ($fbs->whereIn('type', [1, 3])
                ->contains('evaluator_id', $fb->evaluator_id)) {
                $errors[] = 'Panelist (' 
                    . Adjudicator::findOrFail($fb->evaluator_id)->name
                    . ') evaluate '
                    . Adjudicator::findOrFail($fb->evaluatee_id)->name
                    . ' as a chair.';
            }

            if (!$fbs->where('type', 1)
                ->contains('evaluator_id', $fb->evaluatee_id)) {
                $panel_name =
                    Adjudicator::findOrFail($fb->evaluator_id)->name;
                $chair_name = 
                    Adjudicator::findOrFail($fb->evaluatee_id)->name;
                $errors[] = 'Panelist (' 
                    . $panel_name 
                    . ') evaluate chair ('
                    . $chair_name 
                    . '), but the chair hasn\'t evaluate the panelist.';
            }
        }
    }

    public static function checkConsistencyBp($round_id, &$info, &$warning, &$errors) {
        return Feedback::checkConsistencyAsian($round_id, $info, $warning, $errors);
    }
}
