<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'phone' => '01111111111',
                'email' => 'admin1@mail.com',
                'email_verified_at' => now(),
                'role' => 0,
            ],
            // [
            //     'phone' => '01111111112',
            //     'email' => 'police1@mail.com',
            //     'email_verified_at' => now(),
            //     'role' => 1,
            // ],

            // [
            //     'phone' => '01111111113',
            //     'email' => 'owner1@mail.com',
            //     'email_verified_at' => now(),
            //     'role' => 2,
            // ],
            // [
            //     'phone' => '01111111114',
            //     'email' => 'hotel1@mail.com',
            //     'email_verified_at' => now(),
            //     'role' => 3,
            // ],
        ];

        foreach ($users as $user) {
            User::create(array(
                'phone' => $user['phone'],
                'email' => $user['email'],
                'email_verified_at' => now(),
                'role_id' => $user['role'],
                'status' => 1,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ));
        }
    }
}
