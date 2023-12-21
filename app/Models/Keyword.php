<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $table = 'keywords';
    protected $fillable = [
        "id",
        "keyword",
        "created_at",
        "updated_at"
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function posts() {
        return $this->belongsToMany(Post::class);
    }
}
