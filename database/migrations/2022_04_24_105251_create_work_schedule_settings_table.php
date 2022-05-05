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
            $table->integer('limit_before')
                ->default(43200); // One month
            $table->integer('limit_after')
                ->default(60); // One hour
            $table->foreignId('specialist_id')
                ->constrained('specialists')
                ->onDelete('cascade');
            $table->string('type'); // standard, flexible, sliding
            $table->string('break_type');
            $table->date('start_from')->nullable();
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
