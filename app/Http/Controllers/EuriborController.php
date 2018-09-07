<?php

namespace App\Http\Controllers;


use App\EuriborDate;
use App\EuriborRates;
use Carbon\Carbon;
use Goutte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EuriborController extends Controller {

    public function index()
    {

        $euriborDates = EuriborDate::with('rates')
            ->take(5)
            ->orderBy('for_date', 'desc')
            ->get();

        return view('index', compact('euriborDates'));


    }

    public function currentRates()
    {
        $crawler = Goutte::request('GET', 'http://www.euribor-rates.eu/current-euribor-rates.asp');

        $crawler->filter('.maincontent table table')->each(function ($node) {
            $resultNodes = $node->text();

            $resultArray = preg_split('/\R/', $resultNodes);

            $resultArray = array_filter($resultArray, function ($value) {
                return $value !== '';
            });

            $resultArray = array_merge($resultArray, []);

            $this->storeCurrentRates($resultArray);
        });
    }

    public function storeCurrentRates($resultArray)
    {
        $dateIndexes = [1, 2, 3, 4, 5];
        $startItemIndex = 6;
        $loopCount = 8;
        $loopItems = 6;
        $eu = [];

        foreach ($dateIndexes as $key => $dateIndex) {

            $ratesArray = [];
            $euriborDate = null;

            $euriborDate = new EuriborDate();
            $euriborDate->for_date = Carbon::createFromFormat('m-d-Y', $resultArray[$dateIndex]);

            for ($i = 0; $i < $loopCount; $i++) {

                $ratesArray[] = [
                    'duration' => $resultArray[$startItemIndex + ($i * $loopItems)],
                    'rate'     => floatval(trim($resultArray[$startItemIndex + ($i * $loopItems) + $key + 1], '%'))
                ];

            } //End for

            $euriborDate->save();
            $euriborDate->rates()->createMany($ratesArray);

            // return Redirect::back();
        } //End foreach

    }

//    public function updateRates()
//    {
//        $crawler = Goutte::request('GET', 'http://www.euribor-rates.eu/');
//        $crawler->filter('.rightcontent')->each(function ($node) {
//            $resultNodes = $node->text();
//
//            $resultArray = preg_split('/\R/', $resultNodes);
//
//            $resultArray = array_filter($resultArray, function ($value) {
//                return $value !== '';
//            });
//
//            $resultArray = array_merge($resultArray, []);
//
//            $this->store($resultArray);
//
//        });
//    }

//    private function store($resultArray)
//    {
//
//        $euriborDate1 = new EuriborDate();
//        $euriborDate1->for_date = Carbon::createFromFormat('m-d-Y', $resultArray[2]);
//        $euriborDate1->save();
//        $euriborDate1->rates()->createMany([
//            [
//                'duration' => $resultArray[5],
//                'rate'     => trim($resultArray[6], '%')
//            ],
//            [
//                'duration' => $resultArray[9],
//                'rate'     => trim($resultArray[10], '%')
//            ],
//            [
//                'duration' => $resultArray[13],
//                'rate'     => trim($resultArray[14], '%')
//            ],
//            [
//                'duration' => $resultArray[17],
//                'rate'     => trim($resultArray[18], '%')
//            ],
//
//
//        ]);
//
//        $euriborDate2 = new EuriborDate();
//        $euriborDate2->for_date = Carbon::createFromFormat('m-d-Y', $resultArray[3]);
//        $euriborDate2->save();
//        $euriborDate2->rates()->createMany([
//            [
//                'duration' => $resultArray[5],
//                'rate'     => trim($resultArray[7], '%')
//            ],
//            [
//                'duration' => $resultArray[9],
//                'rate'     => trim($resultArray[11], '%')
//            ],
//            [
//                'duration' => $resultArray[13],
//                'rate'     => trim($resultArray[15], '%')
//            ],
//            [
//                'duration' => $resultArray[17],
//                'rate'     => trim($resultArray[19], '%')
//            ],
//
//
//        ]);
//
//        $euriborDate3 = new EuriborDate();
//        $euriborDate3->for_date = Carbon::createFromFormat('m-d-Y', $resultArray[4]);
//        $euriborDate3->save();
//        $euriborDate3->rates()->createMany([
//            [
//                'duration' => $resultArray[5],
//                'rate'     => trim($resultArray[8], '%')
//            ],
//            [
//                'duration' => $resultArray[9],
//                'rate'     => trim($resultArray[12], '%')
//            ],
//            [
//                'duration' => $resultArray[13],
//                'rate'     => trim($resultArray[16], '%')
//            ],
//            [
//                'duration' => $resultArray[17],
//                'rate'     => trim($resultArray[20], '%')
//            ],
//
//        ]);
//    }
}
