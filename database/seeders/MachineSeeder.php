<?php

namespace Database\Seeders;

use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder {
  public function run() {
    $data = [
      [
        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
        "created_by_id" => 1,
        "updated_by_id" => 1,
        "user_id" => 1,
      ]
    ];

    Machine::insert($data);
  }
}
