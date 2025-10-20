<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin')  // important: hashed
        ]);

        // ডিফল্ট সেটিংস
        Setting::create(['key' => 'starting_number', 'value' => '1']);
        Setting::create(['key' => 'secret_code_digits', 'value' => '6']);
        Setting::create(['key' => 'facebook_page_url', 'value' => 'https://www.facebook.com']);
    }
}
