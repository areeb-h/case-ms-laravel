<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cases;
use Illuminate\Support\Facades\DB;

class CasesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // ...

        // Seed dummy cases for client_id 13 and lawyer_id 11
        $dummy_cases = [
            [
                'case_number' => 'CASE123',
                'case_name' => 'Ali v Google',
                'case_type' => 'HOMICIDE',
                'case_role' => 'Defendant',
                'client_id' => 5,
                'lawyer_id' => 9,
                'status' => 'Open',
                'description' => 'Description for CASE123',
            ],
            [
                'case_number' => 'CASE124',
                'case_name' => 'Ahmed v Tesla',
                'case_type' => 'THEFT',
                'case_role' => 'Appellant',
                'client_id' => 6,
                'lawyer_id' => 10,
                'status' => 'Closed',
                'description' => 'Description for CASE124',
            ],
            // Add more dummy cases here
        ];

        foreach ($dummy_cases as $case) {
            Cases::create($case);
        }
    }
}
