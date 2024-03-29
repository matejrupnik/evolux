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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'likes' => $this->users()->count(),
            'keywords' => $this->keywords,
            'is_liked' => $this->users()->where('users.id', auth()->id())->exists()
        ];
    }
}
