<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Adminseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Adit',
            'email' => 'aditia.dava21@smk.belajar.id',
            'password' => Hash::make('12345678'),
            'role' => 'Admin',
        ]);
    }
}
