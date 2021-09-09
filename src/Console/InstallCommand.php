<?php

namespace Laranext\Span\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'span:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the span config.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Span Config...');

        $this->callSilent('vendor:publish', [
            '--tag' => 'span-config',
        ]);

        $this->info('Span scaffolding installed successfully.');
    }
}
