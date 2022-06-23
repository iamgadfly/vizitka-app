<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('settings_id')
                ->constrained('maintenance_settings')
                ->onDelete('cascade');

            $table->foreignId('specialist_id')
                ->constrained('specialists')
                ->onDelete('cascade');

            $table->string('title');

            $table->integer('price')
                ->nullable();

            $table->integer('duration');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenances');
    }
};
