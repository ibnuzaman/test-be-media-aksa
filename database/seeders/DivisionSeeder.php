<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            'Mobile Apps',
            'QA',
            'Fullstack',
            'Backend',
            'Frontend',            
            'UI/UX Designer',
        ];

        foreach ($divisions as $division) {
            \App\Models\Division::create([
                'id' => Str::uuid(),
                'name' => $division,
            ]);
        }
    }

}
