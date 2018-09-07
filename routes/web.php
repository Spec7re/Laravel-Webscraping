<?php

use Illuminate\Support\Facades\Artisan;

Route::get('/command', function() {
    $exitCode = Artisan::call('command:takesCurrentEuriborRates');

    return redirect()->to('/');
});

Route::get('/','EuriborController@index');


// Route::get('/update','EuriborController@updateRates');

Route::get('/current','EuriborController@currentRates');

