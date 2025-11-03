<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\QrCode;

class DiagnoseStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:diagnose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose storage configuration and QR code file accessibility';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Storage Diagnostics ===');
        $this->newLine();

        // Check storage link
        $this->info('1. Checking storage symlink...');
        $publicStoragePath = public_path('storage');
        $storagePublicPath = storage_path('app/public');

        if (is_link($publicStoragePath)) {
            $this->info('   ✓ Storage symlink EXISTS at: ' . $publicStoragePath);
            $this->info('   → Points to: ' . readlink($publicStoragePath));

            if (readlink($publicStoragePath) === $storagePublicPath) {
                $this->info('   ✓ Symlink is CORRECT');
            } else {
                $this->warn('   ⚠ Symlink points to wrong location!');
                $this->warn('   Expected: ' . $storagePublicPath);
            }
        } else {
            $this->error('   ✗ Storage symlink NOT FOUND!');
            $this->info('   Run: php artisan storage:link');
        }

        $this->newLine();

        // Check storage disk configuration
        $this->info('2. Checking storage disk configuration...');
        $this->info('   Default disk: ' . config('filesystems.default'));
        $this->info('   Public disk root: ' . config('filesystems.disks.public.root'));
        $this->info('   Public disk URL: ' . config('filesystems.disks.public.url'));
        $this->newLine();

        // Check QR code directory
        $this->info('3. Checking QR code storage directory...');
        $qrCodePath = 'qr-codes';

        if (Storage::disk('public')->exists($qrCodePath)) {
            $this->info('   ✓ QR code directory EXISTS');
            $files = Storage::disk('public')->files($qrCodePath);
            $allFiles = Storage::disk('public')->allFiles($qrCodePath);
            $this->info('   → Contains ' . count($allFiles) . ' files');
        } else {
            $this->warn('   ⚠ QR code directory DOES NOT EXIST');
            $this->info('   Creating directory...');
            Storage::disk('public')->makeDirectory($qrCodePath);
            $this->info('   ✓ Directory created');
        }

        $this->newLine();

        // Check QR codes in database
        $this->info('4. Checking QR codes in database...');
        $totalQrCodes = QrCode::count();
        $this->info('   Total QR codes: ' . $totalQrCodes);

        if ($totalQrCodes > 0) {
            $missingFiles = 0;
            $existingFiles = 0;

            $this->info('   Checking file existence...');
            $bar = $this->output->createProgressBar($totalQrCodes);
            $bar->start();

            $issues = [];

            QrCode::chunk(10, function ($qrCodes) use (&$missingFiles, &$existingFiles, &$issues, $bar) {
                foreach ($qrCodes as $qrCode) {
                    if ($qrCode->file_path) {
                        if (Storage::disk('public')->exists($qrCode->file_path)) {
                            $existingFiles++;
                        } else {
                            $missingFiles++;
                            $issues[] = [
                                'id' => $qrCode->id,
                                'name' => $qrCode->name,
                                'path' => $qrCode->file_path,
                            ];
                        }
                    } else {
                        $missingFiles++;
                        $issues[] = [
                            'id' => $qrCode->id,
                            'name' => $qrCode->name,
                            'path' => 'NULL',
                        ];
                    }
                    $bar->advance();
                }
            });

            $bar->finish();
            $this->newLine(2);

            $this->info('   ✓ Files found: ' . $existingFiles);
            if ($missingFiles > 0) {
                $this->warn('   ⚠ Files missing: ' . $missingFiles);

                if ($this->confirm('Show details of missing files?', true)) {
                    $this->table(
                        ['ID', 'Name', 'File Path'],
                        collect($issues)->map(fn($issue) => [
                            $issue['id'],
                            $issue['name'],
                            $issue['path'],
                        ])->toArray()
                    );
                }

                if ($this->confirm('Regenerate missing QR code files?', false)) {
                    $this->info('   Regenerating files...');
                    $regenerated = 0;

                    foreach ($issues as $issue) {
                        if ($issue['path'] !== 'NULL') {
                            try {
                                $qrCode = QrCode::find($issue['id']);
                                $qrCodeService = app(\App\Services\QrCodeService::class);
                                $qrCodeService->regenerateFile($qrCode);
                                $regenerated++;
                                $this->info("   ✓ Regenerated QR code #{$issue['id']}");
                            } catch (\Exception $e) {
                                $this->error("   ✗ Failed to regenerate QR code #{$issue['id']}: " . $e->getMessage());
                            }
                        }
                    }

                    $this->info("   ✓ Regenerated {$regenerated} QR code files");
                }
            }
        }

        $this->newLine();

        // Final summary
        $this->info('=== Summary ===');
        $this->info('Storage link: ' . (is_link($publicStoragePath) ? '✓' : '✗'));
        $this->info('QR codes in database: ' . $totalQrCodes);
        if ($totalQrCodes > 0) {
            $this->info('Files status: ' . $existingFiles . ' found, ' . $missingFiles . ' missing');
        }

        $this->newLine();
        $this->info('Diagnostics complete!');

        return Command::SUCCESS;
    }
}
