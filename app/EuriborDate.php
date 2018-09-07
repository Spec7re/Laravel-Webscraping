<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EuriborDate extends Model
{
    //
    public $timestamps = false;

    protected $table = 'euribor_dates';

    protected $guarded = ['id'];

    protected $dates = ['for_date'];

    public function rates()
    {
        return $this->hasMany(EuriborRates::class);
    }
}
