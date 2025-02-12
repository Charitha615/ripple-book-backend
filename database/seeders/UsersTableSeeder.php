<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('admin12345'),
                'user_type' => 'ADMIN',
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_events_coordinator' => 1,
                'is_community_service_coordinator' => 1,
                'is_dana_coordinator' => 1,
                'is_meditate_with_us_coordinator' => 1,
                'is_dhamma_talks_coordinator' => 1,
                'is_arama_poojawa_coordinator' => 1,
                'is_build_up_hermitage_coordinator' => 1,
                'is_donation_coordinator' => 1,
                'gender' => 'male',
                'nic' => '123456789V',
            ],
//            [
//                'name' => 'Jane Coordinator',
//                'email' => 'coordinator@coordinator.com',
//                'email_verified_at' => Carbon::now(),
//                'password' => Hash::make('password'),
//                'user_type' => 'Coordinator',
//                'remember_token' => null,
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
//                'is_events_coordinator' => 0,
//                'is_community_service_coordinator' => 1,
//                'is_dana_coordinator' => 0,
//                'is_meditate_with_us_coordinator' => 1,
//                'is_dhamma_talks_coordinator' => 0,
//                'is_arama_poojawa_coordinator' => 1,
//                'is_build_up_hermitage_coordinator' => 0,
//                'is_donation_coordinator' => 1,
//                'gender' => 'male',
//                'nic' => '987654321V',
//            ],
        ]);
    }
}
