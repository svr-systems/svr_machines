<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinesTable extends Migration {
  public function up() {
    Schema::create('machines', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->foreignId('user_id')->constrained('users');
    });
  }

  public function down() {
    Schema::dropIfExists('machines');
  }
}
