<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Créer le rôle admin s'il n'existe pas
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Créer un utilisateur administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Gédéon',
                'password' => Hash::make('password'), // ⚠️ change le mot de passe !
                'email_verified_at' => now()
            ]
        );

        // Assigner le rôle admin à cet utilisateur
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }
    }
}
