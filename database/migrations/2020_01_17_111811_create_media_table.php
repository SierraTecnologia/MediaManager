<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('model_id');
            $table->string('model_type');
            $table->string('name');
            $table->string('file_name');
            $table->string('disk')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('conversions_disk')->nullable();
            $table->string('collection_name')->nullable();
            $table->unsignedBigInteger('size')->nullable();

            // @todo nao funcionou no sqlite
            $table->string('manipulations', 255)->nullable();
            $table->string('custom_properties', 255)->nullable();
            $table->string('responsive_images', 255)->nullable();
            // $table->json('manipulations')->nullable();
            // $table->json('custom_properties')->nullable();
            // $table->json('responsive_images')->nullable();



            $table->unsignedInteger('order_column')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
