<?php

namespace App\Jobs;

use App\Models\Keyword;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $keywords;

    /**
     * Create a new job instance.
     */
    public function __construct($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->keywords as $keyword) {
            GenerateImage::dispatch($keyword);
        }
    }
}
