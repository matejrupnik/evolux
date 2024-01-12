<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\KeywordResource;
use App\Http\Resources\PostResource;
use App\Models\Keyword;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function feed() {
        return KeywordResource::collection(auth()->user()->keywords()->get());
    }

    public function like(Post $post) {
        $user = auth()->user();
        $userPosts = $user->posts();

        try {
            if ($userPosts->where('posts.id', $post->id)->exists()) {
                return response()->json(['data' => 'User already liked the post.'], 200);
            } else {
                $userPosts->save($post);
            }
        } catch (Exception $e) {
            return response()->json(['data' => 'Error.'], 400);
        }

        foreach ($post->keywords as $keyword) {
            if ($user->keywords->contains($keyword->id)) {
                $alignmentValue = floatval($user->keywords()->withPivot(['alignment'])->find($keyword->id)->pivot->alignment);
                if ($alignmentValue < 0.9) {
                    $alignmentValue += 0.1;
                }
                $user->keywords()->updateExistingPivot($keyword->id, ['alignment' => $alignmentValue]);
            } else {
                $oldKeyword = $user->keywords()->withPivot(['alignment'])->orderBy('pivot_' . 'alignment', 'asc')->first();
                $user->keywords()->detach($oldKeyword);
                $user->keywords()->attach($keyword->id, ['alignment' => 0.2]);
            }
        }

        foreach ($user->keywords as $keyword) {
            $url = "http://46.164.49.18:7860";
            $file = "words_alpha.txt";
            $file_arr = file(public_path().'/'.$file);
            $num_lines = count($file_arr);
            $last_arr_index = $num_lines - 1;
            $rand_index = rand(0, $last_arr_index);
            $word = trim($file_arr[$rand_index]);

            $payload = [
                "prompt" => 'realistic '.$keyword->keyword . ' ' . $word,
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
            $post->keywords()->saveMany([$keyword, $newKeyword]);
        }

        return response()->json(['data' => 'User liked the post successfully.'], 200);
    }
}
