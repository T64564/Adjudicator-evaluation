<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Adjudicator extends Model {
    protected $fillable = ['name', 'test_score', 'active'];

    public $total_score = 1.2;

    public static function getTableHeader() {
        $tableHeader = ['Name', 'Test score', 'Total score', 'Active', 'Edit', 'Delete'];
        return $tableHeader;
    }

    /*
     * id => name
     */
    public static function getNamesForSelectbox() {
        $names = [];
        $teams = Adjudicator::get();

        foreach ($teams as $team) {
            $names[$team->id] = $team->name;
        }
        return $names;
    }
}
