<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Adjudicator;
use App\Http\Models\Team;
use App\Http\Models\Round;
use App\Http\Models\Feedback;

class FeedbackController extends Controller {

    public function index() {
        $rounds = Round::get();
        return view('feedbacks.index', compact('rounds'));
    }

    public function enterResults($round_id) {
        $heads = ['Type', 'Evaluatee', 'Evaluator', 'Score', 'Edit', 'Delete'];
        $round = Round::findOrFail($round_id);
        $feedbacks = Feedback::getListing($round_id);
        return view('feedbacks.enter_results', compact('heads', 'round', 'feedbacks'));
    }

    public function create($round_id) {
        $round = Round::findOrFail($round_id);
        $types = Feedback::getTypes();
        $adj_names = Adjudicator::getNamesForSelectBox();
        $team_names = Team::getNamesForSelectBox();
        return view('feedbacks.create', 
            compact('round', 'types', 'adj_names', 'team_names'));
    }

    public function store(Request $request) {
        $this->validateRequest($request);
        Feedback::create($request->all());
        $name = $request->id;
        $round_id = $request->round_id;

        \Session::flash('flash_message', "Create $name.");
        return redirect()->route('feedbacks.enter_results', 
            ['round_id' => $request->round_id]);
    }

    public function edit($round_id, $feedback_id) {
        $round = Round::findOrFail($round_id);
        $feedback = Feedback::findOrFail($feedback_id);
        $types = Feedback::getTypes();
        $adj_names = Adjudicator::getNamesForSelectBox();
        $team_names = Team::getNamesForSelectBox();
        \Debugbar::info($feedback->toArray());
        return view('feedbacks.edit', 
            compact('round', 'feedback', 'types', 'adj_names', 'team_names'));
    }

    public function validateRequest($request) {
        $id = ($request->has('id')) ? ',' . $request->input('id') : '';
        $rules = config('validations.feedbacks');

        foreach ($rules as $key => $rule) {
            if (preg_match('/.*unique.*/', $rule)) {
                $rules[$key] .= $id;
            }
        }
        $this->validate($request, $rules);
    }
}
