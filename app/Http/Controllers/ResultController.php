<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
        $values = Team::get();

        return view('results.ranking', compact('rounds', 'values'));
    }
}
