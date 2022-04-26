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
        Schema::create('work_schedule_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('smart_schedule')
                ->default(true);
            $table->boolean('confirmation')
                ->default(true);
            $table->integer('cancel_appointment')
                ->default(60);
            $table->integer('new_appointment_not_before_than')
                ->default(43200); // One month
            $table->integer('new_appointment_not_after_than')
                ->default(60); // One hour
            $table->json('weekends');
            $table->foreignId('type_id')
                ->constrained('work_schedule_types')
                ->onDelete('cascade');
            $table->foreignId('specialist_id')->constrained('specialists');
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
        Schema::dropIfExists('work_schedule_settings');
    }
};
