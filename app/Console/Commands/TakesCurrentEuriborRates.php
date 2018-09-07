<?php

namespace App\Console\Commands;

use App\EuriborDate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Goutte;

class TakesCurrentEuriborRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:takesCurrentEuriborRates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command that get rates from http://www.euribor-rates.eu/current-euribor-rates.asp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //Short table data
        $this->getShortTableData();
    }

    private function getShortTableData() {

        $crawler = Goutte::request('GET', 'http://www.euribor-rates.eu/');
        $crawler->filter('.rightcontent')->each(function ($node) {
            $resultNodes = $node->text();

            $resultArray = preg_split('/\R/', $resultNodes);

            $resultArray = array_filter($resultArray, function ($value) {
                return $value !== '';
            });

            $resultArray = array_merge($resultArray, []);

            $this->storeShortTableData($resultArray);

        });

    }

    private function storeShortTableData($resultArray) {

        $euriborDate1 = new EuriborDate();
        $euriborDate1->for_date = Carbon::createFromFormat('m-d-Y', $resultArray[2]);
        $euriborDate1->save();
        $euriborDate1->rates()->createMany([
            [
                'duration' => $resultArray[5],
                'rate' => trim($resultArray[6], '%')
            ],
            [
                'duration' => $resultArray[9],
                'rate' => trim($resultArray[10], '%')
            ],
            [
                'duration' => $resultArray[13],
                'rate' => trim($resultArray[14], '%')
            ],
            [
                'duration' => $resultArray[17],
                'rate' => trim($resultArray[18], '%')
            ],


        ]);

        $euriborDate2 = new EuriborDate();
        $euriborDate2->for_date = Carbon::createFromFormat('m-d-Y', $resultArray[3]);
        $euriborDate2->save();
        $euriborDate2->rates()->createMany([
            [
                'duration' => $resultArray[5],
                'rate' => trim($resultArray[7], '%')
            ],
            [
                'duration' => $resultArray[9],
                'rate' => trim($resultArray[11], '%')
            ],
            [
                'duration' => $resultArray[13],
                'rate' => trim($resultArray[15], '%')
            ],
            [
                'duration' => $resultArray[17],
                'rate' => trim($resultArray[19], '%')
            ],


        ]);

        $euriborDate3 = new EuriborDate();
        $euriborDate3->for_date = Carbon::createFromFormat('m-d-Y', $resultArray[4]);
        $euriborDate3->save();
        $euriborDate3->rates()->createMany([
            [
                'duration' => $resultArray[5],
                'rate' => trim($resultArray[8], '%')
            ],
            [
                'duration' => $resultArray[9],
                'rate' => trim($resultArray[12], '%')
            ],
            [
                'duration' => $resultArray[13],
                'rate' => trim($resultArray[16], '%')
            ],
            [
                'duration' => $resultArray[17],
                'rate' => trim($resultArray[20], '%')
            ],

        ]);
    }
}
