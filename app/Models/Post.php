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
        "media_id"
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function media() {
        return $this->belongsTo(Media::class);
    }

    public function keywords() {
        return $this->belongsToMany(Keyword::class);
    }
}
