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
        Schema::create('events', function (Blueprint $table) {
            $table->id('eventId');
            $table->datetime('start');
            $table->datetime('end');
            $table->foreignId('fk_teamId')->references('teamId')->on('teams');
            $table->foreignId('fk_projectId')->references('projectId')->on('projects');
            $table->foreignId('fk_roomId')->nullable()->references('roomId')->on('rooms');
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
        Schema::dropIfExists('events');
    }
};
