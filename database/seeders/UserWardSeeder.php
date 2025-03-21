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

        $icunurse = User::where('username', 'icunurse')->first();
        $icuWard = Ward::where('name', 'ICU')->first();

        if ($icunurse) {
            $icunurse->wards()->attach([$icuWard->id]);
        }

        $maternitynurse = User::where('username', 'maternitynurse')->first();
        $maternityWard = Ward::where('name', 'Maternity')->first();

        if ($maternitynurse) {
            $maternitynurse->wards()->attach([$maternityWard->id]);
        }

        $medicalnurse = User::where('username', 'medicalnurse')->first();
        $medicalWard = Ward::where('name', 'Medical Ward')->first();

        if ($medicalnurse) {
            $medicalnurse->wards()->attach([$medicalWard->id]);
        }

        $surgicalnurse = User::where('username', 'surgicalnurse')->first();
        $surgicalWard = Ward::where('name', 'Surgical Ward')->first();

        if ($surgicalnurse) {
            $surgicalnurse->wards()->attach([$surgicalWard->id]);
        }

        $newwingnurse = User::where('username', 'newwingnurse')->first();
        $newwingWard = Ward::where('name', 'New Wing')->first();

        if ($newwingnurse) {
            $newwingnurse->wards()->attach([$newwingWard->id]);
        }

        $nurserynurse = User::where('username', 'nurserynurse')->first();
        $nurseryWard = Ward::where('name', 'Nursery')->first();

        if ($nurserynurse) {
            $nurserynurse->wards()->attach([$nurseryWard->id]);
        }
    }
}
