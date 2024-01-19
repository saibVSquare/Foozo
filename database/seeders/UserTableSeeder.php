<?php

namespace Database\Seeders;

use App\DataProviders\UserDataProvider;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        User::insertOrIgnore(UserDataProvider::data());
    }
}