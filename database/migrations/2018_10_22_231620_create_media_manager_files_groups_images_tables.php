<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaManagerFilesGroupsImagesTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'imagens',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('location')->nullable();
                $table->string('name')->nullable();
                $table->string('original_name')->nullable();
                $table->string('storage_location')->default('local');
                $table->string('alt_tag')->nullable();
                $table->string('title_tag')->nullable();
                $table->boolean('is_published')->default(0);
                $table->string('unique_hash', 255)->nullable();
            
                $table->unsignedInteger('file_id')->nullable();
                // $table->foreign('file_id')->references('id')->on('files');
                $table->nullableTimestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'imagenables',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('imagen_id')->nullable();
                // $table->foreign('imagen_id')->references('id')->on('imagens');
                $table->integer('position')->default(0);
                $table->string('imagenable_id');
                $table->string('imagenable_type');
            }
        );
        
        


        Schema::create(
            'thumbnails',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('path')->default('');
                $table->string('relative_url')->default('');
                $table->unsignedInteger('width')->default(0);
                $table->unsignedInteger('height')->default(0);
                $table->integer('thumbnail_id')->default(0)->nullable();
                // $table->foreign('photo_id')->references('id')->on('phonees');
                $table->string('thumbnailable_id');
                $table->string('thumbnailable_type');
            }
        );
        // Schema::create(
        //     'thumbnailables',
        //     function (Blueprint $table) {
        //         $table->increments('id');
        //         $table->unsignedInteger('thumbnail_id')->nullable();
        //         // $table->foreign('thumbnail_id')->references('id')->on('thumbnails');
        //         $table->string('thumbnailable_id');
        //         $table->string('thumbnailable_type');
        //     }
        // );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thumbnailables');
        Schema::dropIfExists('thumbnails');
        Schema::dropIfExists('imagenables');
        Schema::dropIfExists('imagens');
    }
}
