<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model {
  use HasFactory;
  protected function serializeDate(\DateTimeInterface $date) {
    return \Carbon\Carbon::instance($date)->toISOString(true);
  }

  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  static public function getItems($req) {
    $items = Machine::
      where('active', true)->
      orderBy('id')->
      get([
        'id',
        'user_id'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key + 1;
      $item->user = User::find($item->user_id, ["name"]);
    }

    return $items;
  }

  static public function getItem($id) {
    $item = Machine::
      find($id, [
        'id',
        'created_at',
        'updated_at',
        'created_by_id',
        'updated_by_id',
        'user_id',
      ]);

    $item->created_by = User::find($item->created_by_id, ['name']);
    $item->updated_by = User::find($item->updated_by_id, ['name']);
    $item->user = User::find($item->user_id, ["name"]);

    return $item;
  }
}
