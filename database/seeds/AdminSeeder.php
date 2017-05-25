<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Admin::class)->create(['email' => 'maraschen@codingweb.tw', 'password' => HASH::make('123456')]);
        factory(Admin::class, 10)->create();
    }
}
