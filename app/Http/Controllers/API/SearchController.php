<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\KeywordResource;
use App\Http\Resources\PostResource;
use App\Models\Keyword;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function search(Request $request) {
        $data = $request->get('data');
        $search = Keyword::where('keyword', 'like', "%{$data}%")->paginate(15);

        if ($search->total()) {
            return KeywordResource::collection($search);
        }

        $url = "http://46.164.49.18:7860";
        $file = "words_alpha.txt";
        $file_arr = file(public_path().'/'.$file);
        $num_lines = count($file_arr);
        $last_arr_index = $num_lines - 1;
        $rand_index = rand(0, $last_arr_index);
        $word = trim($file_arr[$rand_index]);

        $payload = [
            "prompt" => 'realistic '.$data . ' ' . $word,
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
        $newData = Keyword::create(['keyword' => $data]);
        $newKeyword = Keyword::create(['keyword' => $word]);
        $post->keywords()->saveMany([$newData, $newKeyword]);

        return PostResource::make($post);
    }
}
