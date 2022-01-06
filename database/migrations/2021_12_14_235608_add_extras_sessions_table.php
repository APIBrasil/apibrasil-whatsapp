<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtrasSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {

            $table->timestamp('last_connected')->nullable();
            $table->timestamp('last_disconnected')->nullable();

            $table->string('connected')->nullable();
            $table->string('locales')->nullable();
            $table->string('number')->nullable();
            $table->string('device_manufacturer')->nullable();
            $table->string('device_model')->nullable();
            $table->string('mcc')->nullable();
            $table->string('mnc')->nullable();
            $table->string('os_build_number')->nullable();
            $table->string('os_version')->nullable();
            $table->string('wa_version')->nullable();
            $table->string('pushname')->nullable();
            $table->string('result')->nullable();
            $table->string('ip_host')->nullable();
            $table->string('location')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            //
        });
    }
}
