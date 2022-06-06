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
        Schema::create('business_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')
                ->unique()
                ->constrained('specialists')
                ->onDelete('cascade');
            $table->string('background_image');
            $table->string('title')->nullable();
            $table->string('about')->nullable();
            $table->string('address')->nullable();
            $table->string('placement')->nullable();
            $table->string('floor')->nullable();
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
        Schema::dropIfExists('business_cards');
    }
};
