<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('link')->default('/');
            $table->unsignedBigInteger('from_id')->nullable();
            $table->unsignedBigInteger('to_id');
            $table->boolean('seen')->default(0);

            // Foreign keys
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('notifications');
    }
}
