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
            'username' => 'ICU',
            'password' => Hash::make('ICU123abc'),
        ]);

        User::create([
            'username' => 'Maternity',
            'password' => Hash::make('Maternity123abc'),
        ]);

        User::create([
            'username' => 'Medical',
            'password' => Hash::make('Medical123abc'),
        ]);

        User::create([
            'username' => 'Surgical',
            'password' => Hash::make('Surgical123abc'),
        ]);

        User::create([
            'username' => 'Newwing',
            'password' => Hash::make('Newwing123abc'),
        ]);

        User::create([
            'username' => 'Nursery',
            'password' => Hash::make('Nursery123abc'),
        ]);
    }
}
