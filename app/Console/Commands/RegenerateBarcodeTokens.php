<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Services\BarcodeService;
use Illuminate\Console\Command;

class RegenerateBarcodeTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barcode:regenerate-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate all existing barcode tokens to 8-character format. Use this after updating BarcodeService to a shorter token length.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $members = Registration::whereNotNull('member_number')->get();

        if ($members->isEmpty()) {
            $this->warn('Tidak ada member dengan nomor member.');

            return 0;
        }

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        $updated = 0;
        foreach ($members as $member) {
            // Generate unique 8-char token
            do {
                $token = BarcodeService::generateToken();
            } while (Registration::where('barcode_token', $token)->exists());

            $member->barcode_token = $token;
            $member->save();
            $updated++;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Berhasil regenerate {$updated} barcode token.");

        return 0;
    }
}
