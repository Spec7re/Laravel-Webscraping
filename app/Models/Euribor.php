<?php
/**
 * Created by PhpStorm.
 * User: specter
 * Date: 07.02.18
 * Time: 15:53
 */

namespace App\Models;

use Goutte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Euribor
{
    //List EuriborRates RATES.
    public function listEu()
    {
        $result = DB::select('SELECT datt , rang, rate FROM eu_rates ');

        echo '<table id="rates" border="1">';

        echo '<tr><th>'.'Period'.'</th>'.'<th>'.$result[8]->datt.'</th>'.'<th>'.$result[4]->datt.'</th>'.'<th>'.$result[0]->datt.'</th></tr>';

        echo '<th>'.$result[0]->rang.'</th>'.'<th>'.$result[8]->rate.'</th>'.'<th>'.$result[4]->rate.'</th>'.'<th>'.$result[0]->rate.'</th></tr>';
        echo '<th>'.$result[1]->rang.'</th>'.'<th>'.$result[9]->rate.'</th>'.'<th>'.$result[5]->rate.'</th>'.'<th>'.$result[1]->rate.'</th></tr>';
        echo '<th>'.$result[2]->rang.'</th>'.'<th>'.$result[10]->rate.'</th>'.'<th>'.$result[6]->rate.'</th>'.'<th>'.$result[2]->rate.'</th></tr>';
        echo '<th>'.$result[3]->rang.'</th>'.'<th>'.$result[11]->rate.'</th>'.'<th>'.$result[7]->rate.'</th>'.'<th>'.$result[3]->rate.'</th></tr>';

        echo '</table>';

        ?>
        <button type="button"><a href="http://euribor.local/update">UPDATE</a></button>
        <?php

    }

    //Update EuriborRates RATES.
    public function updateRates()
    {
        $crawler = Goutte::request('GET', 'http://www.euribor-rates.eu/');
        $crawler->filter('.rightcontent')->each(function ($node) {
            $result = $node->text();
            //Normalize data
            $_result = preg_split('/\R/', $result);

            DB::table('rates')->truncate();

            DB::table('rates')->insert([
                ['datt' => $_result[12], 'rang' =>$_result[15], 'rate' =>$_result[24]],
                ['datt' => $_result[12], 'rang' =>$_result[27], 'rate' =>$_result[36]],
                ['datt' => $_result[12], 'rang' =>$_result[39], 'rate' =>$_result[48]],
                ['datt' => $_result[12], 'rang' =>$_result[51], 'rate' =>$_result[60]],

                ['datt' => $_result[9], 'rang' =>$_result[15], 'rate' =>$_result[21]],
                ['datt' => $_result[9], 'rang' =>$_result[27], 'rate' =>$_result[33]],
                ['datt' => $_result[9], 'rang' =>$_result[39], 'rate' =>$_result[45]],
                ['datt' => $_result[9], 'rang' =>$_result[51], 'rate' =>$_result[57]],

                ['datt' => $_result[6], 'rang' =>$_result[15], 'rate' =>$_result[18]],
                ['datt' => $_result[6], 'rang' =>$_result[27], 'rate' =>$_result[30]],
                ['datt' => $_result[6], 'rang' =>$_result[39], 'rate' =>$_result[42]],
                ['datt' => $_result[6], 'rang' =>$_result[51], 'rate' =>$_result[54]]
            ]);
        });
        echo 'Rates were updated!';

        ?>
        <button type="button"><a href="http://euribor.local/list">RETURN</a></button>
        <?php
    }

    public function getEuri()
    {
        $crawler = Goutte::request('GET', 'http://www.euribor-rates.eu/');

        $crawler->filter('.rightcontent')->each(function ($node) {
            $result = $node->text();

          return  $_result = preg_split('/\R/', $result);
        });
    }

    public function getData()
    {
        $result = DB::select('SELECT datt , rang, rate FROM rates ');
        dd($result);
    }


}