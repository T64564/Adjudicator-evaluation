<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Adjudicator;
use App\Http\Models\Ranking;
use App\Http\Models\Round;
use App\Http\Models\Team;

class ResultController extends Controller {

    public function index() {
        $heads = Team::getTableHeader();
        $values = Team::get();
        return view('teams.index', compact('heads', 'values'));
    }

    public function ranking() {
        $rounds = Round::get();
        $adjudicators = Adjudicator::get();
        $rankings = new Ranking();

        return view('results.ranking',
            compact('adjudicators', 'rankings', 'rounds'));
    }
}
