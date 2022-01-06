<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('avatar')->nullable();
            $table->longText('link')->nullable();
            $table->integer("size")->nullable();
            $table->string("group_id")->nullable();
            $table->string("owner")->nullable();
            $table->string("subject")->nullable();
            $table->string("creation")->nullable();
            $table->string("subject_time")->nullable();
            $table->string("subject_owner")->nullable();
            $table->string("restrict")->nullable();
            $table->string("ephemeral_duration")->nullable();
            $table->longText("desc")->nullable();
            $table->string("desc_id")->nullable();
            $table->string("desc_time")->nullable();
            $table->string("desc_owner")->nullable();

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
        Schema::dropIfExists('groups');
    }
}
