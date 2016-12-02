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
        $heads = Feedback::getTableHeader();
        $round = Round::findOrFail($round_id);
        $feedbacks = Feedback::getListing($round_id);
        return view('feedbacks.enter_results', 
            compact('heads', 'round', 'feedbacks'));
    }

    public function createTeamToAdj($round_id) {
        $round = Round::findOrFail($round_id);
        $types = [ 0 => 'Team to oralist' ];
        $adj_names = Adjudicator::getNamesForSelectBox();
        $team_names = Team::getNamesForSelectBox();
        $from_team = true;
        return view('feedbacks.create', 
            compact('round', 'from_team', 'types', 'adj_names', 'team_names'));
    }


    public function createAdjToAdj($round_id) {
        $round = Round::findOrFail($round_id);
        $types = [
            1 => 'Chair to panelist',
            2 => 'Panelist to Chair',
            3 => 'Chair to trainee',
        ];
        $adj_names = Adjudicator::getNamesForSelectBox();
        $from_team = false;
        return view('feedbacks.create', 
            compact('round', 'from_team', 'types', 'adj_names'));
    }

    public function store(Request $request) {
        $errors = Feedback::validateRequest($request);
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }
        Feedback::create($request->all());
        $name = $request->id;
        $round_id = $request->round_id;

        \Session::flash('flash_message', "Create $name.");
        return redirect()->route('feedbacks.enter_results', 
            ['round_id' => $request->round_id]);
    }

    public function edit($round_id, $feedback_id) {
        $feedback = Feedback::findOrFail($feedback_id);
        $round = Round::findOrFail($round_id);
        $feedback = Feedback::findOrFail($feedback_id);
        $types = [ 0 => 'Team to oralist' ];
        if ($feedback->type !== 0) {
            $types = [
                1 => 'Chair to panelist',
                2 => 'Panelist to Chair',
                3 => 'Chair to trainee',
            ];
        }
        $adj_names = Adjudicator::getNamesForSelectBox();
        $team_names = Team::getNamesForSelectBox();
        return view('feedbacks.edit', 
            compact('round', 'feedback', 'types', 'adj_names', 'team_names'));
    }

    public function update(Request $request) {
        $errors = Feedback::validateRequest($request);
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
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

    public function checkAsian($round_id) {
        Feedback::checkConsistencyAsian($round_id, $info, $warning, $errors);
        return $this->check($round_id, $info, $warning, $errors);
    }

    public function checkBp($round_id) {
        Feedback::checkConsistencyBp($round_id, $info, $warning, $errors);
        return $this->check($round_id, $info, $warning, $errors);
    }

    private function check($round_id, $info, $warning, $errors) {
        if (!empty($info)) {
            \Session::flash('flash_info', $info);
        }
        if (!empty($warning)) {
            \Session::flash('flash_warning', $warning);
        }
        return redirect()
            ->route('feedbacks.enter_results', ['round_id' => $round_id])
            ->withErrors($errors);
    }
}
