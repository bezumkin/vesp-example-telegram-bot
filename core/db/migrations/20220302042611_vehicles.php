<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Vehicles extends Migration
{
    public function up(): void
    {
        $this->schema->create('vehicles', static function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('type');
            $table->string('fuel');
            $table->tinyInteger('doors');
            $table->tinyInteger('seats');
            $table->string('gearbox');
            $table->json('properties')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema->drop('vehicles');
    }
}
