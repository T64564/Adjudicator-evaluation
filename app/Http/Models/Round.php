<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = ['name', 'silent'];

    public static function getTableHeader()
    {
        $tableHeader = ['Id', 'Name', 'Silent', 'Edit', 'Delete'];
        return $tableHeader;
    }
}
