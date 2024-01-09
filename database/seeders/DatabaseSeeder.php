<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Keyword;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Media::factory(10)->create();
        $keywords = Keyword::factory(10)->sequence(
            ['keyword' => 'dog'],
            ['keyword' => 'cat'],
            ['keyword' => 'baloon'],
            ['keyword' => 'technology'],
            ['keyword' => 'college'],
            ['keyword' => 'computer'],
            ['keyword' => 'paper'],
            ['keyword' => 'phone'],
            ['keyword' => 'nature'],
            ['keyword' => 'hills']
        )->create();
        Keyword::factory(10)->create();
        User::factory(10)->hasAttached($keywords, ['alignment' => 0.1])->create();
        Post::factory(20)->create()->each(function($post) {
            $keywords = Keyword::query()->inRandomOrder()->limit(10)->get();
            $post->keywords()->saveMany($keywords);
        });
    }
}
