<?php

namespace App\Console\Commands;

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
            Mail::mailer('smtp')->raw(
                "This is a test email from Jose Rizal University ISO 21001 System.\n\n" .
                "If you received this email, your Google SMTP email configuration is working correctly!\n\n" .
                "Mail Configuration:\n" .
                "- Host: {$mailConfig['host']}\n" .
                "- Port: {$mailConfig['port']}\n" .
                "- Username: {$mailConfig['username']}\n" .
                "- From: {$mailConfig['from_name']} <{$mailConfig['from_address']}>\n\n" .
                "This email was sent at: " . now()->format('Y-m-d H:i:s') . "\n\n" .
                "Best regards,\n" .
                "Jose Rizal University ISO 21001 System",
                function ($message) use ($recipient, $mailConfig) {
                    $message->to($recipient)
                            ->subject('Test Email - ISO 21001 System')
                            ->from($mailConfig['from_address'], $mailConfig['from_name']);
                }
            );

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
