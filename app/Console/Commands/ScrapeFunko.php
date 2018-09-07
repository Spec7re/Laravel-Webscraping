<?php

namespace App\Console\Commands;
use Goutte;
use Illuminate\Console\Command;

class ScrapeFunko extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:funko';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Funko POP! Vinyl Scraper';
    /**
     * The list of funko collection slugs.
     *
     * @var array
     */
    protected $collections = [
        'animation',
        'disney',
        'games',
        'heroes',
        'marvel',
        'monster-high',
        'movies',
        'pets',
        'rocks',
        'sports',
        'star-wars',
        'television',
        'the-vault',
        'the-vote',
        'ufc',
    ];
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
        foreach ($this->collections as $collection) {
            $this->scrape($collection);
        }
    }
    /**
     * For scraping data for the specified collection.
     *
     * @param  string $collection
     * @return boolean
     */
    public static function scrape($collection)
    {
//        $goutte = new Client();
        $crawler = Goutte::request('GET', env('FUNKO_POP_URL').'/'.$collection);
        $pages = ($crawler->filter('footer .pagination li')->count() > 0)
            ? $crawler->filter('footer .pagination li:nth-last-child(2)')->text()
            : 0
        ;
        for ($i = 0; $i < $pages + 1; $i++) {
            if ($i != 0) {
                $crawler = Goutte::request('GET', env('FUNKO_POP_URL').'/'.$collection.'?page='.$i);
            }
            $crawler->filter('.product-item')->each(function ($node) {
                $sku   = explode('#', $node->filter('.product-sku')->text())[1];
                $title = trim($node->filter('.title a')->text());
                print_r($sku.', '.$title);
            });
        }
        return true;
    }

}
