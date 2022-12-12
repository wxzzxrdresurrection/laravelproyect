<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            "password"=>"J22r07c04",
            "rol_id"=>1
        ]);
    }
}
