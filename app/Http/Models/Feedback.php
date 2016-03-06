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
        if ($type === 0) {
            return 'Team to Oralist';
        } elseif ($type === 1) {
            return 'Chair to panelist';
        } elseif ($type === 2) {
            return 'Panelist to Chair';
        } elseif ($type === 3) {
            return 'Chair to trainee';
        }
        return 'Undefined';
    }

    public static function getListing($round_id) {
        $feedbacks = Feedback::where('round_id', $round_id)->get();

        foreach ($feedbacks as $feedback) {
            \Debugbar::info($feedback->toArray());
            \Debugbar::info($feedback->evaluator_id);
            $feedback->type_name = $feedback->getTypeName($feedback->type);
            if ($feedback->type === 0) {
                $feedback->evaluator_name = 
                    Team::findOrFail($feedback->evaluator_id)->name;
            } else {
                $feedback->evaluator_name = 
                    Adjudicator::findOrFail($feedback->evaluator_id)->name;
            }
            $feedback->evaluatee_name = 
                Adjudicator::findOrFail($feedback->evaluatee_id)->name;
        }
        return $feedbacks;
    }
}
