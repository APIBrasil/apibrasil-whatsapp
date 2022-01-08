<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();

            $table->string('session_name')->nullable()->unique();
            $table->string('session_key')->nullable()->unique();

            $table->string('server_whatsapp')->nullable();
            $table->longText('apitoken')->nullable();

            $table->string('webhook_wh_message')->nullable();
            $table->string('webhook_wh_status')->nullable();
            $table->string('webhook_wh_connect')->nullable();
            $table->string('webhook_wh_qr_code')->nullable();

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

            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onUdpdate('cascade')
                ->onDelete('cascade');

            $table->string('last_activity')->nullable();

            $table->timestamp('last_connected')->nullable();
            $table->timestamp('last_disconnected')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('sessions');
    }
}
