<?php

namespace App;

use Goutte;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EuriborRates extends Model
{
    //
    public $timestamps = false;

    protected $table = 'euribor_rates';

    protected $guarded = ['id'];

}
