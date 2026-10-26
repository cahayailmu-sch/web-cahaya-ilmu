<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the 5 official multi-admin accounts without deleting existing data.
     */
    public function run(): void
    {
        // Ensure legacy default admin email (if present) also has superadmin access.
        User::where('email', 'admin@cahayailmu.sch.id')->update([
            'role' => 'superadmin',
            'level' => null,
        ]);

        $admins = [
            [
                'name' => 'Superadmin Cahaya Ilmu',
                'email' => 'superadmin@cahayailmu.sch.id',
                'password' => 'password123',
                'role' => 'superadmin',
                'level' => null,
            ],
            [
                'name' => 'Admin TK Islam Cahaya Ilmu',
                'email' => 'admintk@cahayailmu.sch.id',
                'password' => 'password123',
                'role' => 'admin_jenjang',
                'level' => 'TK',
            ],
            [
                'name' => 'Admin SD Islam Cahaya Ilmu',
                'email' => 'adminsd@cahayailmu.sch.id',
                'password' => 'password123',
                'role' => 'admin_jenjang',
                'level' => 'SD',
            ],
            [
                'name' => 'Admin SMP Islam Cahaya Ilmu',
                'email' => 'adminsmp@cahayailmu.sch.id',
                'password' => 'password123',
                'role' => 'admin_jenjang',
                'level' => 'SMP',
            ],
            [
                'name' => 'Admin SMA Islam Cahaya Ilmu',
                'email' => 'adminsma@cahayailmu.sch.id',
                'password' => 'password123',
                'role' => 'admin_jenjang',
                'level' => 'SMA',
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                $admin,
            );
        }
    }
}
