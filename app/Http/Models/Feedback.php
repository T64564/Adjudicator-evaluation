<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'type', 'round_id', 
        'evaluatee_id', 'evalator_id', 'score'];
}
