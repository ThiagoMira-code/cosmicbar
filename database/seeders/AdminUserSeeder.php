<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates or updates a default administrator user.
     * It uses the 'username' field as a unique key to prevent duplicates.
     */
    public function run(): void
    {
        User::updateOrCreate(
        ['username' => 'cosmicadmin'],
        [
            'name'     => 'Cosmic Admin',
            'email'    => 'admin@cosmicbar.it',
            'password' => Hash::make('Grafite#15'),
            'is_admin' => true,
        ]
    );

    // Criar Funcionário (Não Admin)
    User::updateOrCreate(
        ['username' => 'staff_user'],
        [
            'name'     => 'Staff Member',
            'email'    => 'staff@cosmicbar.it',
            'password' => Hash::make('password123'),
            'is_admin' => false, // Define como falso para restringir acesso
        ]
    );
    }
}

