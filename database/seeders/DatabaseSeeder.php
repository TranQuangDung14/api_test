<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'username' => 'Quang Dũng',
            'fullname' => 'Trần Quang Dũng',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345'),
            'role'  => 1,
        ]);
        DB::table('users')->insert([
            'username' => 'Dương dẹo',
            'fullname' => 'Nguyễn Thái Dương',
            'email' => 'quangdung14062001@gmail.com',
            'password' => Hash::make('12345'),
            'role'  => 1,
        ]);

        $this->call([
            ImportProvinceDistrictWardSeeder::class,]);
    }
}
