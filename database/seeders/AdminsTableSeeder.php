<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Admins')->insert([
            [
                'Email' => 'admin@uniarchive.com',
                'School_ID' => '2025-00001-CM-2',
                'LastName' => 'Doe',
                'FirstName' => 'John',
                'Gender' => 'male',
                'ContactNumber' => '09123456789',
                'Password' => Hash::make('password123'),
                'AccountCreated' => now(),
            ],
        ]);
    }
}
