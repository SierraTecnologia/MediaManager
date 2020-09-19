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

        try {
            Schema::create(
                'photo_albums',
                function (Blueprint $table) {
                    $table->engine = 'InnoDB';
                    $table->increments('id')->unsigned();
                    $table->integer('position')->nullable();
                    $table->string('name', 255);
                    $table->text('description')->nullable();
                    $table->string('folder_id', 255);
                    $table->unsignedInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                    $table->unsignedInteger('user_id_edited')->nullable();
                    $table->foreign('user_id_edited')->references('id')->on('users')->onDelete('set null');
                    $table->timestamps();
                    $table->softDeletes();
                }
            );
        } catch (\Throwable $th) {
            try {
                Schema::create(
                    'photo_albums',
                    function (Blueprint $table) {
                        $table->engine = 'InnoDB';
                        $table->increments('id')->unsigned();
                        $table->integer('position')->nullable();
                        $table->string('name', 255);
                        $table->text('description')->nullable();
                        $table->string('folder_id', 255);
                        $table->unsignedInteger('user_id')->nullable();
                        $table->unsignedInteger('user_id_edited')->nullable();
                        $table->timestamps();
                        $table->softDeletes();
                    }
                );
            } catch (\Throwable $th) {
            }
        }
        

        Schema::create(
            'photos',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->unsigned();

                $table->text('description');
                $table->string('path')->default('');
                $table->string('relative_url')->default('');
                $table->string('avg_color', 7)->default('');
                $table->boolean('is_published')->default(false);

                $table->string('metadata')->default('');
                $table->unsignedInteger('created_by_user_id')->nullable();
            
                $table->integer('position')->nullable();
                $table->boolean('slider')->nullable();
                $table->string('filename', 255);
                $table->string('name', 255)->nullable();
                $table->unsignedInteger('photo_album_id')->nullable();
                $table->foreign('photo_album_id')->references('id')->on('photo_albums')->onDelete('set null');
                $table->boolean('album_cover')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
                $table->integer('photo_id')->default(0)->nullable();
                $table->integer('thumbnail_id')->default(0)->nullable();
                // $table->foreign('photo_id')->references('id')->on('phonees');
            }
        );
        Schema::create(
            'thumbnailables',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('thumbnail_id')->nullable();
                // $table->foreign('thumbnail_id')->references('id')->on('thumbnails');
                $table->string('thumbnailable_id');
                $table->string('thumbnailable_type');
            }
        );
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
        Schema::dropIfExists('photos');
        Schema::dropIfExists('photo_albums');
        Schema::dropIfExists('imagenables');
        Schema::dropIfExists('imagens');
    }
}
