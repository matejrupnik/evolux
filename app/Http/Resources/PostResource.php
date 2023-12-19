<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Route;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'caption' => $this->caption,
            'media' => MediaResource::make($this->media),
            'user' => Route::is('post', 'feed') ? UserResource::make($this->user) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
