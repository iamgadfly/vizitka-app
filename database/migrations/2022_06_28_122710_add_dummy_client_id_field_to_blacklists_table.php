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
        Schema::table('blacklists', function (Blueprint $table) {
            $table->foreignId('dummy_client_id')
                ->nullable()
                ->constrained('dummy_clients')
                ->onDelete('cascade');
            $table->foreignId('blacklisted_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blacklists', function (Blueprint $table) {
            $table->dropColumn('dummy_client_id');
            $table->foreignId('blacklisted_id')
                ->change();
        });
    }
};
