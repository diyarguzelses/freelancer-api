<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Ahmet',
            'surname' => 'Ateş',
            'email' => 'ahmetates@example.com',
            'password' => Hash::make('123456789'),
            'is_freelancer' => 1,
            'is_admin' => 1,
            'status' => 1,
        ]);


    }
}
