<?php

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
        for ($i = 0; $i<25; $i++)
            DB::table('posts')->insert([
                'title' => str_random(8),
                'content' => str_random(40),
                'author_id' => 1,
            ]);
    }
}
