<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'vw_survey';
    protected $fillable = [ 
        'customer_number',
        'representative',
        'question1',
        'question2',
        'question3',
        'question4',
        'question5',
        'question6',
        'question7',
        'question7_other',
        'date',
    ];
}