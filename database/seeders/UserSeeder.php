<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Emp 1',
                'email' => 'emp1@example.com',
                'role_id' => 1,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Emp 2',
                'email' => 'emp2@example.com',
                'role_id' => 1,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Emp 3',
                'email' => 'emp3@example.com',
                'role_id' => 1,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Emp 4',
                'email' => 'emp4@example.com',
                'role_id' => 1,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role_id' => 0,
                'password' => Hash::make('securepass'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
