<?php

namespace App\Console\Commands;

use App\Jobs\PopulateBanners;
use Illuminate\Console\Command;

class GenerateBanners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-banners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Banners for main page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        PopulateBanners::dispatch();
    }
}
