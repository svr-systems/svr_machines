<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration {
  public function up() {
    Schema::create('sales', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->datetime('machine_date');
      $table->tinyInteger('pump');
      $table->decimal('quantity', 9, 2);
      $table->decimal('amount', 9, 2);
      $table->foreignId('machine_id')->constrained('machines');
    });
  }

  public function down() {
    Schema::dropIfExists('sales');
  }
}
