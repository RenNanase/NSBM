<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserWardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin has access to all wards
        // $admin = User::where('username', 'admin')->first();
        // $wards = Ward::all();

        // if ($admin) {
        //     $admin->wards()->attach($wards->pluck('id'));
        // }

        // $Ren = User::where('username', 'Ren')->first();
        // $medicalWard = Ward::where('name', 'Medical Ward')->first();

        // if ($Ren && $medicalWard) {
        //     $Ren->wards()->attach([$medicalWard->id]);
        // }

        // $Rize = User::where('username', 'Rize')->first();
        // $newWing = Ward::where('name', 'New Wing')->first();

        // if ($Rize && $newWing) {
        //     $Rize->wards()->attach([$newWing->id]);
        // }

        $ICU = User::where('username', 'ICU')->first();
        $wards = Ward::all();

        if ($ICU) {
            $ICU->wards()->attach($wards->pluck('id'));
        }

        $Maternity = User::where('username', 'Maternity')->first();
        $wards = Ward::all();

        if ($Maternity) {
            $Maternity->wards()->attach($wards->pluck('id'));
        }

        $Medical = User::where('username', 'Medical')->first();
        $wards = Ward::all();

        if ($Medical) {
            $Medical->wards()->attach($wards->pluck('id'));
        }

        $Surgical = User::where('username', 'Surgical')->first();
        $wards = Ward::all();

        if ($Surgical) {
            $Surgical->wards()->attach($wards->pluck('id'));
        }

        $Newwing = User::where('username', 'Newwing')->first();
        $wards = Ward::all();

        if ($Newwing) {
            $Newwing->wards()->attach($wards->pluck('id'));
        }

        $Nursery = User::where('username', 'Nursery')->first();
        $wards = Ward::all();

        if ($Nursery) {
            $Nursery->wards()->attach($wards->pluck('id'));
        }
    }
}
