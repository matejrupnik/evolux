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
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function feed() {
        return KeywordResource::collection(auth()->user()->keywords()->get());
    }

    public function like(Post $post) {
        try {
            if (auth()->user()->posts()->where('posts.id', $post->id)->exists()) {
                auth()->user()->posts()->detach($post);
            } else {
                auth()->user()->posts()->save($post);
            }
        } catch (Exception $e) {
            return response()->json(['data' => 'Error.'], 400);
        }

        // zgeneriri slike pa posodob user tage

        return response()->json(['data' => 'User interacted with the post successfully.'], 200);
    }
}
