<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
  public function run() {
    $data = [
      [
        "name" => "ADMIN",
        "email" => "admin@svrmexico.com",
        "password" => bcrypt("SVRsh_1029*"),
        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
      ]
    ];

    User::insert($data);
  }
}
