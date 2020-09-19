<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MediaManagerCreateImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('images')) {
            Schema::create(
                'images',
                function (Blueprint $table) {
                    $table->engine = 'InnoDB';
                    $table->increments('id');

                    $table->string('imageable_type')->nullable();
                    $table->string('imageable_id')->nullable();

                    $table->string('file')->nullable();
                    $table->string('file_type')->nullable();
                    $table->string('file_size')->nullable();

                    $table->string('name')->nullable(); // Key used to refer to it in code
                    $table->string('title')->nullable(); // Alt title
                    $table->text('crop_box')->nullable();
                    $table->string('focal_point')->nullable();

                    $table->integer('width')->unsigned();
                    $table->integer('height')->unsigned();
                    $table->timestamps();

                    /**
                     * Peguei esse do outro
                     */
                    $table->string('location')->nullable();
                    $table->string('original_name')->nullable();
                    $table->string('storage_location')->default('local');
                    $table->string('alt_tag')->nullable();
                    $table->string('title_tag')->nullable();
                    $table->boolean('is_published')->default(0);
                    $table->integer('entity_id')->nullable();
                    $table->string('entity_type')->nullable();

                    $table->index(['imageable_type', 'imageable_id']);
                    $table->index(['imageable_id', 'imageable_type']);
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
