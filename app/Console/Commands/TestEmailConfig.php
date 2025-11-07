<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmailConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {recipient : The email address to send the test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify Google SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('recipient');

        $this->info('Testing email configuration...');
        $this->info('Recipient: ' . $recipient);

        // Get mail configuration
        $mailConfig = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        $this->table(
            ['Configuration', 'Value'],
            [
                ['Mailer', $mailConfig['mailer']],
                ['Host', $mailConfig['host']],
                ['Port', $mailConfig['port']],
                ['Username', $mailConfig['username']],
                ['From Address', $mailConfig['from_address']],
                ['From Name', $mailConfig['from_name']],
            ]
        );

        try {
            // Send test email using the styled template
            Mail::mailer('smtp')
                ->to($recipient)
                ->send(new TestEmail($mailConfig));

            Log::info('Test email sent successfully via command line', [
                'recipient' => $recipient,
                'mail_config' => $mailConfig
            ]);

            $this->info('✅ Test email sent successfully!');
            $this->info('Please check the inbox of: ' . $recipient);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Failed to send test email via command line', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipient' => $recipient
            ]);

            $this->error('❌ Failed to send test email!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Troubleshooting tips:');
            $this->line('1. Check your .env file for correct MAIL_* settings');
            $this->line('2. Verify that your Google App Password is correct');
            $this->line('3. Make sure 2-Step Verification is enabled in your Google account');
            $this->line('4. Check the Laravel logs at storage/logs/laravel.log');

            return Command::FAILURE;
        }
    }
}
