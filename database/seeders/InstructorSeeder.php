<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\instructor;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        instructor::insert([
            ['name' => 'Justin'],
            ['name' => 'Farah'],
            ['name' => 'Sally'],
            ['name' => 'Jhonny'],
    ]);
    }
}
