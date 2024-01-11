<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Keyword;
use App\Models\Post;

class KeywordController extends Controller
{
    public function show(Keyword $keyword) {
        return PostResource::collection($keyword->posts()->latest()->paginate(15));
    }
}
