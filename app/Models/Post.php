<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = [
        "id",
        "caption",
        "created_at",
        "updated_at",
        "user_id",
        "media_id"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function media() {
        return $this->belongsTo(Media::class);
    }
}
