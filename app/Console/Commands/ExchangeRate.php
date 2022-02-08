<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExchangeRate:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        \App\Modules\Address\Entities\ExchangeRate::truncate();
        $endpoint = 'latest';
        $access_key = 'd953b5e0114069f9d0818af2fbdff02b';
        $base = 'EUR';
        $symbols = 'USD,CAD,EUR';
        // Initialize CURL:
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://data.fixer.io/api/' . $endpoint . '?access_key=' . $access_key . '&base=' . $base . '&symbols' . $symbols);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);
        // Decode JSON response:
        $exchangeRates = json_decode($json, 1);
        foreach ($exchangeRates['rates'] as $currency => $rate) {
            \App\Modules\Address\Entities\ExchangeRate::create([
                'rate' => $rate,
                'currency' => $currency,
            ]);
        }
    }
}
