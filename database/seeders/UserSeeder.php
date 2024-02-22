<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Admin
        $admin = new User();
        $admin->name = 'Administrator';
        $admin->username = 'admin';
        $admin->email = 'admin@gmail.com';
        $admin->password = Hash::make('admin');
        $admin->role_id = 1;
        $admin->save();

        //Operator
        $operator = new User();
        $operator->name = 'Operator';
        $operator->username = 'operator';
        $operator->email = 'operator@gmail.com';
        $operator->password = Hash::make('operator');
        $operator->role_id = 2;
        $operator->save();

        //Wali
        $wali = new User();
        $wali->name = 'Wali';
        $wali->username = 'wali';
        $wali->email = 'wali@gmail.com';
        $wali->password = Hash::make('wali');
        $wali->role_id = 3;
        $wali->save();
    }
}
