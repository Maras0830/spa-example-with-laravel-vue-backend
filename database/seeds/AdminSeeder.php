<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Admin::class)->create(['email' => 'maraschen@codingweb.tw', 'password' => '123456']);
        factory(Admin::class, 10)->create();
    }
}
