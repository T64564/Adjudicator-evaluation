<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {
    protected $fillable = ['name', 'active'];

    public static function getTableHeader() {
        $tableHeader = ['Name', 'Active', 'Edit', 'Delete'];
        return $tableHeader;
    }

    /*
     * id => name
     */
    public static function getNamesForSelectbox() {
        $names = [];
        $teams = Team::get();

        foreach ($teams as $team) {
            $names[$team->id] = $team->name;
        }
        return $names;
    }
}
