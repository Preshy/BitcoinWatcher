<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BitcoinPriceController;

class BitcoinPriceWatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen for the latest price of Bitcoin every day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $bitcoinPriceController;
    public function __construct()
    {
        parent::__construct();
        $this->bitcoinPriceController = new BitcoinPriceController();
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle()
    {
        $executeSendRate = $this->bitcoinPriceController->sendRate();

        if($executeSendRate === true) {
            $this->info('Bitcoin Price For Today Sent!');
        } else {
            $this->error('An error occurred, check the log!');
        }
    }
}
