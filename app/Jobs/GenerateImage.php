<?php

namespace App\Jobs;

use App\Models\Keyword;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $keyword;

    /**
     * Create a new job instance.
     */
    public function __construct($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = "http://46.164.49.18:7860";
        $file = "words_alpha.txt";
        $file_arr = file(public_path().'/'.$file);
        $num_lines = count($file_arr);
        $last_arr_index = $num_lines - 1;
        $rand_index = rand(0, $last_arr_index);
        $word = trim($file_arr[$rand_index]);

        $payload = [
            "prompt" => 'realistic '.$this->keyword->keyword . ' ' . $word,
            "steps" => 10,
            "height" => 1024,
            "width" => 1024
        ];

        $response = Http::post("$url/sdapi/v1/txt2img", $payload);
        $responseData = $response->json();

        $base64Image = $responseData['images'][0];
        $imageData = base64_decode($base64Image);

        $filename = 'output_' . Str::random(8) . '.png';
        Storage::put("public/images/$filename", $imageData);

        $media = Media::factory()->create(['file_name' => $filename]);
        $post = Post::factory()->create(['media_id' => $media->id]);
        $newKeyword = Keyword::create(['keyword' => $word]);
        $post->keywords()->saveMany([$this->keyword, $newKeyword]);

        Log::info('Image generated.');
    }
}
