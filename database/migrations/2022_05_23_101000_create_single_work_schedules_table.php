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
        Schema::create('single_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('day_id')->constrained('work_schedule_days')->onDelete('cascade');
            $table->date('date');
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->boolean('is_break');
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
        Schema::dropIfExists('single_work_schedules');
    }
};
