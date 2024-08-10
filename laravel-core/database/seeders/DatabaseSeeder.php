<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [];
        foreach(config('permissions') as $section=>$sectionPermission){
            foreach($sectionPermission as $permission){
                $permissions[] = $section.'_'.$permission;
            }
        }
        User::create([
            "name" => "Tarek", 
            "email" => "noughitarek@gmail.com", 
            "password" => Hash::make('password2'),
            "role" => "admin",
            "permissions" => implode(',',$permissions),
        ]);
    }
}
