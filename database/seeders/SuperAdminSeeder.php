<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'ndeyearame2.niang@ucad.edu.sn'],
            [
                'name'      => 'Ndèye Arame Niang',
                'email'     => 'ndeyearame2.niang@ucad.edu.sn',
                'password'  => bcrypt('admin12345678'),
                'role'      => 'superadmin',
                'is_active' => true,
            ]
        );
    }
}
