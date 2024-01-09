<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Keyword;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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

        File::copyDirectory('public/images/', 'public/storage/images/');
        $i = 0;
        $files = Storage::disk('public')->files('images/');
        sort($files, SORT_NATURAL);
        foreach ($files as $file) {
            if ($file == 'images/.DS_Store') continue;
            $media = Media::factory()->create(['file_name' => explode('/', $file)[1]]);
            $post = Post::factory()->create(['media_id' => $media->id]);
            $post->keywords()->saveMany($keywords->slice($i/3, 1));
            $i++;
        }
        $user = User::factory()->hasAttached($keywords, ['alignment' => 0.1])->create([
            'username' => 'JNovak',
            'email' => 'janez@novak.com',
            'password' => Hash::make("janeznovak"),
            'first_name' => 'Janez',
            'middle_name' => 'Martin',
            'last_name' => 'Novak',
            'media_id' => 1,
            'is_admin' => 1
        ]);
        User::factory(10)->hasAttached($keywords, ['alignment' => 0.1])->create();
    }
}
