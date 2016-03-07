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
        $errors = Feedback::validateRequest($request);
        if (!empty($errors)) {
            return redirect()->route('feedbacks.create', 
                ['round_id' => $request->round_id])
                ->withErrors($errors)->withInput();
        }
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
        return view('feedbacks.edit', 
            compact('round', 'feedback', 'types', 'adj_names', 'team_names'));
    }

    public function update(Request $request) {
        $errors = Feedback::validateRequest($request);
        if (!empty($errors)) {
            return redirect()->route('feedbacks.create', 
                ['round_id' => $request->round_id])
                ->withErrors($errors)->withInput();
        }
        $feedback = Feedback::findOrFail($request['id']);
        $feedback->update($request->all());
        $name = $request->id;
        $round_id = $request->round_id;

        \Session::flash('flash_message', "update $name.");
        return redirect()->route('feedbacks.enter_results', 
            ['round_id' => $request->round_id]);
    }

    public function destroy($round_id, $feedback_id) {
        $feedback = Feedback::findOrFail($feedback_id);
        $name = Feedback::getTypeName($feedback->type);
        $feedback->delete();

        \Session::flash('flash_message', "Delete \"$name\".");
        return redirect()->route('feedbacks.enter_results', 
            ['round_id' => $round_id]);
    }

    public function check($round_id) {
        $errors = Feedback::checkConsistency($round_id);
        return redirect()
            ->route('feedbacks.enter_results', ['round_id' => $round_id])
            ->withErrors($errors);
    }

}
