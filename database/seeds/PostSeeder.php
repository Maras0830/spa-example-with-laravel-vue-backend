<?php

use App\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Post::class, 10)->create()
            ->each(function($post) {
                factory(App\Comment::class, 2)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => factory(App\Admin::class)->create()->id,
                    'comment_from_type' => 'App\\Admin',
                ]);
                factory(App\Comment::class, 8)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => factory(App\User::class)->create()->id,
                    'comment_from_type' => 'App\\User',
                ])->each(function ($comment) use ($post){
                    factory(App\Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => factory(App\User::class)->create()->id,
                        'comment_from_type' => 'App\\User',
                        'comment_id' => $comment->id,
                    ]);
                });
            });
    }
}
