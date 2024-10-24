<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
  public function run() {
    $data = [
      [
        "name" => "ADMINISTRADOR",
      ],
      [
        "name" => "USUARIO",
      ],
    ];

    Role::insert($data);
  }
}
