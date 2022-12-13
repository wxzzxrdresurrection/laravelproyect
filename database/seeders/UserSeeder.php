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
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "nombre"=>"Admin",
            "ap_paterno"=>"Admin",
            "ap_materno"=>"Admin",
            "correo"=>"javier.res220704@gmail.com",
            "telefono"=>"8713814026",
            "password"=>Hash::make("J22r07c04"),
            "role_id"=>'1'
        ]);
    }
}
