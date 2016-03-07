<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Adjudicator extends Model {
    protected $fillable = ['name', 'test_score', 'active'];

    public static function getTableHeader() {
        $tableHeader = ['Name', 'Test score', 'Active', 'Edit', 'Delete'];
        return $tableHeader;
    }

    /*
     * id => name
     */
    public static function getNamesForSelectbox() {
        $names = [];
        $teams = Adjudicator::where('active', 1)->get();

        foreach ($teams as $team) {
            $names[$team->id] = $team->name;
        }
        return $names;
    }
}
