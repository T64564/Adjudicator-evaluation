<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Adjudicator extends Model
{
    protected $fillable = ['name', 'test_score', 'active'];

    public static function getTableHeader()
    {
        $tableHeader = ['Id', 'Name', 'Test score', 'Active', 'Edit', 'Delete'];
        return $tableHeader;
    }

    /*
     * id => name
     */
    public static function getNamesForSelectbox()
    {
        $names = [];
        $adjs = Adjudicator::where('active', 1)->orderBy('name')->get();

        foreach ($adjs as $adj) {
            $names[$adj->id] = $adj->name;
        }
        return $names;
    }
}
