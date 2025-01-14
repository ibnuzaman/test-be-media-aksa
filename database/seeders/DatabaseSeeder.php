<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Admin::factory()->create([
            'id' =>(string) str::uuid(),
            'name' => 'admin',            
            'username' => 'admin',
            'phone' => 'no telpon admin',
            'email' => 'email admin',
            'password' => \Illuminate\Support\Facades\Hash::make('pastibisa'),
        ]);
    }
}
