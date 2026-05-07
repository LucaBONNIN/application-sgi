<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $domain = app()->environment('demonstration') ? '127011.xyz' : 'laravel.dev.test';

        // L'admin intendance
        $admin = User::create([
            'name' => 'Admin Intendance 1',
            'email' => "admin.intendance.1@{$domain}",
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // On assigne le rôle super admin
        $admin->assignRole(Utils::getSuperAdminName());

        // On rattache l'admin au service Administration
        $adminServices = Service::where('name', 'Administration')->get();
        $admin->services()->attach($adminServices);

        // Les demandeurs (professeurs etc)
        $services = Service::all();

        User::factory(10)
            ->when(
                app()->environment('demonstration'),
                fn ($factory) => $factory->state(fn () => ['email' => fake()->unique()->userName()."@{$domain}"])
            )
            ->create()
            ->each(function ($user) use ($services) {
                $user->assignRole('demandeurs');

                $user->services()->attach(
                    $services->random(rand(1, 2))->pluck('id')->toArray()
                );
            });
    }
}
