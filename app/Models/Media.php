<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $table = 'media';
    protected $fillable = [
        "id",
        "file_name",
        "alt",
        "created_at",
        "updated_at"
    ];

    public function post() {
        return $this->hasOne(Post::class);
    }

    public function user() {
        return $this->hasOne(User::class);
    }
}
