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
        $admin = User::where('username', 'admin')->first();
        $wards = Ward::all();

        if ($admin) {
            $admin->wards()->attach($wards->pluck('id'));
        }

        // Nurse1 has access to Medical and Surgical wards
        $Ren = User::where('username', 'Ren')->first();
        $medicalWard = Ward::where('name', 'Medical Ward')->first();
        $surgicalWard = Ward::where('name', 'Surgical Ward')->first();

        if ($Ren && $medicalWard && $surgicalWard) {
            $Ren->wards()->attach([$medicalWard->id, $surgicalWard->id]);
        }

        // Nurse2 has access to Pediatric and ICU wards
        $Rize = User::where('username', 'Rize')->first();
        $newWing = Ward::where('name', 'New Wing')->first();
        $maternitySuite = Ward::where('name', 'Maternity Suite (L&D)')->first();

        if ($Rize && $newWing && $maternitySuite) {
            $Rize->wards()->attach([$newWing->id, $maternitySuite->id]);
        }
    }
}
