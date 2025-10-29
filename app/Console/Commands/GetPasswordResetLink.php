<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetPasswordResetLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:get-reset-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract the latest password reset link from logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            $this->error('Log file not found!');
            return 1;
        }

        $content = file_get_contents($logFile);

        // Extract password reset URLs
        preg_match_all('/http:\/\/[^\s]+\/password\/reset\/[a-f0-9]+\?email=[^\s"<]+/', $content, $matches);

        if (empty($matches[0])) {
            $this->error('No password reset links found in logs.');
            $this->info('Make sure you have requested a password reset first.');
            return 1;
        }

        // Get the last (most recent) link
        $latestLink = end($matches[0]);

        // Decode the email parameter
        $decodedLink = html_entity_decode($latestLink);

        $this->info('Latest Password Reset Link:');
        $this->line('');
        $this->line($decodedLink);
        $this->line('');
        $this->info('Copy this link and paste it in your browser.');

        return 0;
    }
}
