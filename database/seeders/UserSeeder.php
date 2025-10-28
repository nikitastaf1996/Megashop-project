<?php

namespace Database\Seeders;

use App\Models\Image;
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
        User::factory()->count(100)->create()->each(function ($user) {
            Image::factory()->create([
                'parent_id' => $user->id,
                'type' => 'user'
            ]);
        });
    }
}
