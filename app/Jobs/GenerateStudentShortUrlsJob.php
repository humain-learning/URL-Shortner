<?php

namespace App\Jobs;

use App\Models\ShortenedUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GenerateStudentShortUrlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;        // 🔴 IMPORTANT
    public $timeout = 120;

    public function handle()
    {
        foreach ($this->rows as $row) {

            $encodedId = $row[$this->encIndex] ?? null;
            if (!$encodedId) {
                continue;
            }

            // ⛔️ skip if already exists
            if (ShortenedUrl::where('encoded_id', $encodedId)->exists()) {
                continue;
            }

            ShortenedUrl::create([
                'encoded_id' => $encodedId,
                'short_url'  => Str::random(8),
                'created_at' => now(),
            ]);
        }
    }

    public function failed(\Throwable $e)
    {
        \Log::error('Short URL job failed', [
            'msg' => $e->getMessage()
        ]);
    }
}

