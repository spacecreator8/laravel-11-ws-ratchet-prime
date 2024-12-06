<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(3)->create();
         User::factory()->create([
             'name' => 'qwe',
             'email' => 'qwe@ya.ru',
             'password' =>Hash::make('qwe'),
         ]);
        User::factory()->create([
            'name' => 'asd',
            'email' => 'asd@ya.ru',
            'password' =>Hash::make('asd'),
        ]);
        User::factory()->create([
            'name' => 'zxc',
            'email' => 'zxc@ya.ru',
            'password' =>Hash::make('zxc'),
        ]);
        Message::factory(40)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
