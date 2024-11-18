<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super = User::factory()->create([
            'name' => 'Mark',
            'username' => 'super',
            'email' => 'super@gmail.com',
            'password' => bcrypt('y8UHGJoWFqz0mCkpvojx'),
        ]);
        $super->assignRole('super');

        $admin = User::factory()->create([
            'name' => 'August',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('y8UHGJoWFqz0mCkpvojx'),
        ]);
        $admin->assignRole('admin');
    }
}
