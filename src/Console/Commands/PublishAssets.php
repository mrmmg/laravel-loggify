<?php

namespace Mrmmg\LaravelLoggify\Console\Commands;

use Illuminate\Console\Command;

class PublishAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loggify:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-publish Loggify assets';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag'   => 'loggify-assets',
            '--force' => true,
        ]);
    }
}
