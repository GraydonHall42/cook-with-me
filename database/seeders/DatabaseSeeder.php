<?php

namespace Database\Seeders;

use App\Models\User;
use App\Domain\PostBuilder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $json = File::get(database_path('/seeders/data/dummy_posts.json'));
        $posts = json_decode($json, true);

        foreach ($posts as $postData) {
            PostBuilder::start()
                ->forUser($user)
                ->title($postData['title'])
                ->description($postData['description'])
                ->recipe($postData['recipe'])
                ->photos($postData['photos'])
                ->links($postData['links'])
                ->build();
        }
    }
}
