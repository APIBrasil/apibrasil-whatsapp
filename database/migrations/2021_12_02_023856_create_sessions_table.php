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

            $table->string('session_name')->nullable();
            $table->string('session_key')->nullable();
            $table->string('session_api_token')->nullable();

            $table->string('webhook_wh_message')->nullable();
            $table->string('webhook_wh_status')->nullable();
            $table->string('webhook_wh_connect')->nullable();
            $table->string('webhook_wh_qr_code')->nullable();

            $table->string('last_activity')->nullable();

            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onUdpdate('cascade')
                ->onDelete('cascade');

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
