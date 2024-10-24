<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
  use HasFactory;
  protected function serializeDate(\DateTimeInterface $date) {
    return \Carbon\Carbon::instance($date)->toISOString(true);
  }

  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  static public function getItems($req) {
    $items = Sale::
      where('active', true)->
      orderBy('id')->
      get([
        'id',
        'machine_id'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key + 1;
      $item->machine = Machine::find($item->machine_id, ["name"]);
    }

    return $items;
  }

  static public function getItem($id) {
    $item = Sale::
    find($id, [
        'id',
        'created_at',
        'updated_at',
        'created_by_id',
        'updated_by_id',
        'machine_date',
        'pump',
        'quantity',
        'amount',
        'machine_id',
      ]);

    $item->created_by = User::find($item->created_by_id, ['name']);
    $item->updated_by = User::find($item->updated_by_id, ['name']);
    $item->machine = User::find($item->machine_id, ["name"]);

    return $item;
  }
}
