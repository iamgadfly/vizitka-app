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
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('surname');
            $table->foreignId('activity_kind_id')->constrained('activity_kinds');
            $table->string('card_title');
            $table->string('about')->nullable();
            $table->string('address')->nullable();
            $table->string('placement')->nullable();
            $table->string('floor')->nullable();
            $table->string('instagram_account')->nullable();
            $table->string('vk_account')->nullable();
            $table->string('youtube_account')->nullable();
            $table->string('tiktok_account')->nullable();
            $table->string('avatar')->nullable();
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
        Schema::dropIfExists('specialists');
    }
};
