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

    public static function getListing($round_id) {
        $feedbacks = Feedback::where('round_id', $round_id)->get();

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
     * - the team submit more than once.
     * - the evaluator and the evaluatee are the same adjudicator.
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
            if (Feedback::where('round_id', $request['round_id'])
                ->where('type', 0)->whereNotIn('id', [$request['id']])
                ->where('evaluator_id', $request['evaluator_id'])
                ->exist()) {
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
     * - adjudicators that don't evaluate or isn't evaluated
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
     */
    public static function checkConsistency($round_id) {
        $errors = [];
        $adjs = Adjudicator::where('active', 1)->get();
        $teams = Team::where('active', 1)->get();
        $fbs = Feedback::where('round_id', $round_id)->get();

        foreach ($teams as $team) {
            if (!$fbs->where('type', 0)->contains('evaluator_id', $team->id)) {
                $errors[] = $team->name 
                    . ' doesn\'t evaluate anyone.';
            }
        }

        foreach ($adjs as $adj) {
            if (!$fbs->contains('evaluatee_id', $adj->id) &&
                !$fbs->whereIn('type', [1, 2, 3])
                ->contains('evaluator_id', $adj->id)) {
                $errors[] = $adj->name 
                    . ' doesn\'t evaluate and isn\'t evaluated by anyone.';
            }
        }

        $fbs_by_chair = $fbs->whereIn('type', [1, 3]);

        foreach ($fbs_by_chair as $fb) {
            if ($fbs->whereIn('type', [1, 3])
                ->where('evaluator_id', $fb->evaluator_id)
                ->count() % 2 == 0) {
                $errors[] = 'Chair (' 
                    . Adjudicator::findOrFail($fb->evaluator_id)->name
                    . ') submits feedbacks odd number of times.';
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

            if ($fb->type === 1 && 
                !$fbs->where('type', 2)
                ->contains('evaluator_id', $fb->evaluatee)) {
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
                $fbs->contains('evaluator_id', $fb->evaluatee)) {
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
                ->contains('evaluator_id', $fb->evaluatee)) {
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

        return $errors;
    }
}
