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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')
                ->constrained('specialists')
                ->onDelete('cascade');
            $table->foreignId('client_id')
                ->nullable()
                ->constrained('clients')
                ->onDelete('cascade');
            $table->foreignId('dummy_client_id')
                ->nullable()
                ->constrained('dummy_clients')
                ->onDelete('cascade');
            $table->foreignId('maintenance_id')
                ->constrained('maintenances')
                ->onDelete('cacade');
            $table->date('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('status')->default('unconfirmed');
            $table->string('order_number');
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
        Schema::dropIfExists('appointments');
    }
};
