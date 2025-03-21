<?php

namespace Database\Seeders;

use App\Models\InfectiousDisease;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class InfectiousDiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first Emergency Department staff user
        $emergencyStaff = User::whereHas('wards', function($query) {
            $query->where('name', 'Emergency Department');
        })->first();

        if (!$emergencyStaff) {
            $this->command->info('No Emergency Department staff found. Skipping InfectiousDisease seeding.');
            return;
        }

        // Get all wards excluding Emergency Department
        $wards = Ward::where('name', '!=', 'Emergency Department')->get();

        if ($wards->isEmpty()) {
            $this->command->info('No wards found excluding Emergency Department. Skipping InfectiousDisease seeding.');
            return;
        }

        // List of infectious diseases
        $diseases = [
            'Influenza A',
            'Influenza B',
            'Measle',
            'Chicken Pox',
            'TB',
            'Typhoid',
            'Cholera',
            'Rota Virus',
            'RSV',
            'Covid 19',
            'Dengue'
        ];

        // Patient types
        $patientTypes = ['adult', 'paed', 'neonate'];

        // Create sample data for each disease in various wards
        foreach ($diseases as $disease) {
            // Create at least one record for each disease
            $primarySampleWard = $wards->random();
            $primaryPatientType = $patientTypes[array_rand($patientTypes)];
            $totalCases = rand(1, 5);

            InfectiousDisease::create([
                'disease' => $disease,
                'patient_type' => $primaryPatientType,
                'ward_id' => $primarySampleWard->id,
                'user_id' => $emergencyStaff->id,
                'total' => $totalCases,
                'notes' => "Sample data for {$disease} disease in {$primarySampleWard->name}",
                'created_at' => now()->subDays(rand(0, 14))->addHours(rand(0, 23)),
                'updated_at' => now()->subDays(rand(0, 14))->addHours(rand(0, 23))
            ]);

            // Randomly add more cases for some diseases in different wards
            if (rand(0, 1) && $wards->count() > 1) {
                $secondaryWards = $wards->where('id', '!=', $primarySampleWard->id)->random(min(2, $wards->count() - 1));

                foreach ($secondaryWards as $ward) {
                    $secondaryPatientType = $patientTypes[array_rand($patientTypes)];
                    $secondaryCases = rand(1, 3);

                    InfectiousDisease::create([
                        'disease' => $disease,
                        'patient_type' => $secondaryPatientType,
                        'ward_id' => $ward->id,
                        'user_id' => $emergencyStaff->id,
                        'total' => $secondaryCases,
                        'notes' => "Additional cases of {$disease} recorded in {$ward->name}",
                        'created_at' => now()->subDays(rand(0, 14))->addHours(rand(0, 23)),
                        'updated_at' => now()->subDays(rand(0, 14))->addHours(rand(0, 23))
                    ]);
                }
            }
        }

        $this->command->info('Sample infectious disease data has been seeded successfully.');
    }
}
