<?php

namespace App\Console\Commands;

use App\Jobs\MailPodcast;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendUserLoginEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('start');
        dispatch(new MailPodcast())->onQueue('email')->delay(120);
    }
}
