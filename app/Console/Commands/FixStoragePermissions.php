<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\QrCode;

class FixStoragePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix file permissions for QR code files in storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Fixing Storage Permissions ===');
        $this->newLine();

        $storagePath = storage_path('app/public');

        $this->info('1. Setting directory permissions...');
        $this->info('   Storage path: ' . $storagePath);

        // Fix permissions using chmod
        $commands = [
            "find {$storagePath} -type d -exec chmod 755 {} \;",
            "find {$storagePath} -type f -exec chmod 644 {} \;",
        ];

        foreach ($commands as $command) {
            $this->info('   Running: ' . $command);
            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                $this->info('   ✓ Success');
            } else {
                $this->error('   ✗ Failed with code: ' . $returnCode);
            }
        }

        $this->newLine();

        // Verify QR code files
        $this->info('2. Verifying QR code files...');
        $totalQrCodes = QrCode::whereNotNull('file_path')->count();
        $this->info('   Total QR codes with files: ' . $totalQrCodes);

        if ($totalQrCodes > 0) {
            $fixed = 0;
            $bar = $this->output->createProgressBar($totalQrCodes);
            $bar->start();

            QrCode::whereNotNull('file_path')->chunk(10, function ($qrCodes) use (&$fixed, $bar, $storagePath) {
                foreach ($qrCodes as $qrCode) {
                    $fullPath = $storagePath . '/' . $qrCode->file_path;

                    if (file_exists($fullPath)) {
                        // Set file permissions to 644 (readable by web server)
                        if (chmod($fullPath, 0644)) {
                            $fixed++;
                        }
                    }

                    $bar->advance();
                }
            });

            $bar->finish();
            $this->newLine(2);
            $this->info("   ✓ Fixed permissions for {$fixed} files");
        }

        $this->newLine();

        // Test access
        $this->info('3. Testing file access...');
        $sampleQr = QrCode::whereNotNull('file_path')->first();

        if ($sampleQr) {
            $fullPath = $storagePath . '/' . $sampleQr->file_path;
            $this->info('   Sample file: ' . $sampleQr->file_path);

            if (is_readable($fullPath)) {
                $this->info('   ✓ File is READABLE');
                $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
                $this->info('   → Permissions: ' . $perms);
            } else {
                $this->error('   ✗ File is NOT READABLE');
            }

            $this->info('   → URL: ' . $sampleQr->file_url);
        }

        $this->newLine();
        $this->info('=== Done ===');
        $this->info('All storage permissions have been fixed.');
        $this->info('Try accessing your QR codes now!');

        return Command::SUCCESS;
    }
}
