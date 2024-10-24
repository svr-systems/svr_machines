<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getItems() {
    $data = Role::
      where('active', true)->
      get([
        'id',
        'name',
      ]);

    return $data;
  }
}
