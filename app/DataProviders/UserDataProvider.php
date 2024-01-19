<?php

namespace App\DataProviders;

use Illuminate\Support\Facades\Hash;

abstract class UserDataProvider
{
    public static function data(): array
    {
        return [
            ['first_name' => 'Admin', 'last_name' => 'Foozo', 'email' => 'admin@foozo.com', 'email_verified_at' => now(), 'password' => Hash::make('admin12345678')],
            ['first_name' => 'Test1', 'last_name' => 'User', 'email' => 'test1@foozo.com', 'email_verified_at' => now(), 'password' => Hash::make('12345678')],
            ['first_name' => 'Test2', 'last_name' => 'User', 'email' => 'test2@foozo.com', 'email_verified_at' => now(), 'password' => Hash::make('12345678')],
        ];
    }
}
