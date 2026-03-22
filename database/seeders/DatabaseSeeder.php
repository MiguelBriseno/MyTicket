<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole  = Role::create(['name' => 'admin']);
        $agentRole  = Role::create(['name' => 'agent']);
        $userRole   = Role::create(['name' => 'user']);

        // Crear usuario admin
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@tickets.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($adminRole);

        // Crear 2 agentes de prueba
        $agent1 = User::create([
            'name'     => 'Agente Soporte',
            'email'    => 'agente@tickets.com',
            'password' => Hash::make('password123'),
        ]);
        $agent1->assignRole($agentRole);

        $agent2 = User::create([
            'name'     => 'Agente Técnico',
            'email'    => 'tecnico@tickets.com',
            'password' => Hash::make('password123'),
        ]);
        $agent2->assignRole($agentRole);

        // Crear 2 usuarios normales de prueba
        $user1 = User::create([
            'name'     => 'Juan Cliente',
            'email'    => 'juan@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user1->assignRole($userRole);

        $user2 = User::create([
            'name'     => 'María Cliente',
            'email'    => 'maria@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user2->assignRole($userRole);

        // Crear departamentos
        Department::create([
            'name'        => 'Soporte General',
            'description' => 'Atención a dudas y problemas generales',
            'is_active'   => true,
        ]);

        Department::create([
            'name'        => 'Soporte Técnico',
            'description' => 'Problemas técnicos y de infraestructura',
            'is_active'   => true,
        ]);

        Department::create([
            'name'        => 'Facturación',
            'description' => 'Dudas y problemas relacionados a pagos',
            'is_active'   => true,
        ]);
    }
}
