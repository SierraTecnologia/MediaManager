<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acessos', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->integer('computer_id')->unsigned();
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('playlist_id')->unsigned()->nullable();

            $table->foreign('computer_id')->references('id')->on('computers');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('playlist_id')->references('id')->on('playlists');
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
        Schema::dropIfExists('acessos');
    }
}
