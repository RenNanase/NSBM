<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'username' => 'admin',
        //     'password' => Hash::make('password'),
        // ]);

        // User::create([
        //     'username' => 'Ren',
        //     'password' => Hash::make('password'),
        // ]);

        // User::create([
        //     'username' => 'Rize',
        //     'password' => Hash::make('password'),
        // ]);

        User::create([
            'username' => 'icunurse',
            'password' => Hash::make('icu123'),
        ]);

        User::create([
            'username' => 'maternitynurse',
            'password' => Hash::make('maternity123'),
        ]);

        User::create([
            'username' => 'medicalnurse',
            'password' => Hash::make('medical123'),
        ]);

        User::create([
            'username' => 'surgicalnurse',
            'password' => Hash::make('surgical123'),
        ]);

        User::create([
            'username' => 'newwingnurse',
            'password' => Hash::make('newwing123'),
        ]);

        User::create([
            'username' => 'nurserynurse',
            'password' => Hash::make('nursery123'),
        ]);
    }
}
