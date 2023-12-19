<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function feed() {
//        return PostResource::collection(
//        );
    }

    public function index_users(User $user) {
        return PostResource::collection($user->posts()->paginate(15));
    }

    public function show(Post $post)
    {
        return PostResource::make($post);
    }

    public function create(Request $request) {
        $request->validate([
            "caption" => "string",
            "media" => "required|mimes:jpg,jpeg,png"
        ]);
        Log::info($request);

        $media_name = time()."-".auth()->id().".".$request->media->extension();
        $request->media->move(storage_path('app/public/media/'), $media_name);

        $media = Media::create([
            'file_name' => $media_name,
            'alt' => $request->caption
        ]);

        return Post::create([
            "caption" => $request->caption,
            "user_id" => auth()->id(),
            "media_id" => $media->id
        ]);
    }

    public function destroy($id) {
        return Post::destroy($id);
    }

    public function update(Request $request, Post $post) {
        $request->validate([
            "caption" => "string"
        ]);
        Log::info($request);

        $post = Post::find($post->id);
        $post->update(["caption" => $request->caption]);
        return response("success", 200);
    }
}
