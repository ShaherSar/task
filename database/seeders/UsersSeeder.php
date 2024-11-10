<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::query()->where(['email' => 'root@localhost.com'])->exists()) {
            $user = new User();
            $user->email = "root@localhost.com";
            $user->name = "Root User";
            $user->password = Hash::make('admin123');
            $user->save();
        }

        for ($i = 1; $i <= 10; $i++) {
            UserFactory::new()->create();
        }
    }
}
