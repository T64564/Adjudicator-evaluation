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
        $this->ranking();
    }

    public function ranking() {
        $rounds = Round::get();
        $adjudicators = Adjudicator::where('active', 1)->get();
        $rankings = new Ranking();
        $heads = $rankings->getTableHeader();

        return view('results.ranking',
            compact('adjudicators', 'heads', 'rankings', 'rounds'));
    }

    public function getExport() {
        $rankings = new Ranking();
        $heads = $rankings->getTableHeader();
        $list = $rankings->getListForCsvExport();

        return exportRankingCsv($list, $heads, 'ranking.csv');
    }
}
