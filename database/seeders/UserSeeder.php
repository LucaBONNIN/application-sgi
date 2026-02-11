<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // L'admin intendance
        $admin = User::create([
            'name' => 'Admin Intendance 1',
            'email' => 'admin.intendance.1@laravel.dev.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // On rattache l'admin au service Administration
        $adminServices = Service::where('name', 'Administration')->get();
        $admin->services()->attach($adminServices);

        // Les profs
        $services = Service::all();

        User::factory(10)->create()->each(function ($user) use ($services) {
            $user->services()->attach(
                $services->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
