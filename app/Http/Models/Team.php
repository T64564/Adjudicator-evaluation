<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {
    protected $fillable = ['name', 'active'];

    public static function getTableHeader() {
        $tableHeader = ['Id', 'Name', 'Active', 'Edit', 'Delete'];
        return $tableHeader;
    }

    /*
     * id => name
     */
    public static function getNamesForSelectbox() {
        $names = [];
        $teams = Team::where('active', 1)->get();

        foreach ($teams as $team) {
            $names[$team->id] = $team->name;
        }
        return $names;
    }

    public function delete() {
        $fbs = Feedback::get();
        if ($fbs->where('type', 0)
            ->contains('evaluator_id', $this->id)) {
            return false;
        }
        return parent::delete();
    }
}
