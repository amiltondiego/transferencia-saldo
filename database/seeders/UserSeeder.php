<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'type' => UserType::common(),
            'indentify_register' => 12345678912,
            'email' => 'user-login@gmail.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'type' => UserType::common(),
            'indentify_register' => 92345678912,
            'email' => 'user-comum@gmail.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'type' => UserType::shopkeeper(),
            'indentify_register' => 12345678900012,
            'email' => 'lojista@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
