<?php

namespace Database\Seeders;

use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wards = [
            [
                'name' => 'Medical Ward',
                'total_bed' => 5,
                'total_licensed_op_beds' => 2,
            ],
            [
                'name' => 'Surgical Ward',
                'total_bed' => 21,
                'total_licensed_op_beds' => 15,
            ],
            [
                'name' => 'New Wing',
                'total_bed' => 8,
                'total_licensed_op_beds' => 8,
            ],
            [
                'name' => 'ICU',
                'total_bed' => 5,
                'total_licensed_op_beds' => 2,
            ],
            [
                'name' => 'Nursery',
                'total_bed' => 10,
                'total_licensed_op_beds' => 10,
            ],
            [
                'name' => 'Maternity Suite (L&D)',
                'total_bed' => 6,
                'total_licensed_op_beds' => 2,
            ],
        ];

        foreach ($wards as $ward) {
            Ward::create($ward);
        }
    }
}
