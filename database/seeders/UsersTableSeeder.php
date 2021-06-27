<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Maman',
            'identity_id' => '12345678345',
            'gender' => 1,
            'address' => 'Jl Sultan Hasanuddin',
            'photo' => 'maman.png', //note: tidak ada gambar
            'email' => 'admin@admin.com',
            'password' => app('hash')->make('secret'),
            'phone_number' => '08123123123',
            'api_token' => Str::random(40),
            'role' => 0,
            'status' => 1
        ]);
    }
}
