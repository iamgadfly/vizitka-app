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
        Schema::create('work_schedule_works', function (Blueprint $table) {
            $table->id();
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->foreignId('day_id')
                ->constrained('work_schedule_days')
                ->onDelete('cascade');
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
        Schema::dropIfExists('work_schedule_works');
    }
};
