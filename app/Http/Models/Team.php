<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'active'];

    public static function getTableHeader() {
        $tableHeader = ['Name', 'Active'];
        return $tableHeader;
    }
}
