<?php

namespace Mrmmg\LaravelLoggify\Console\Commands;

use Illuminate\Console\Command;

class InstallLoggify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loggify:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish loggify config & assets';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag'   => 'loggify-config',
        ]);

        $this->call('loggify:assets');
    }
}
