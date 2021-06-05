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


/**
 * @todo Ver se pode jogar fora
 */
// <?php
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;

// class CreateSlaveMidiaTables extends Migration
// {

// 	/**
// 	 * Run the migrations.
// 	 *
// 	 * @return void
// 	 */
// 	public function up()
// 	{
        
//         Schema::create(config('app.db-prefix', '').'files', function (Blueprint $table) {
//             $table->increments('id');
//             $table->string('name');
//             $table->string('location');
//             $table->integer('user');
//             $table->string('tags')->nullable();
//             $table->text('details')->nullable();
//             $table->string('mime');
//             $table->string('size');
//             $table->boolean('is_published')->default(0);
//             $table->integer('order');
//             $table->nullableTimestamps();
//             $table->softDeletes();
//         });
//         Schema::create(config('app.db-prefix', '').'images', function (Blueprint $table) {
//             $table->increments('id');
//             $table->string('location');
//             $table->string('name')->nullable();
//             $table->string('original_name');
//             $table->string('storage_location')->default('local');
//             $table->string('alt_tag')->nullable();
//             $table->string('title_tag')->nullable();
//             $table->boolean('is_published')->default(0);
//             $table->integer('entity_id');
//             $table->string('entity_type');
//             $table->nullableTimestamps();
//             $table->softDeletes();
//         });

        
// 		Schema::create(config('app.db-prefix', '').'photo_albums', function (Blueprint $table) {
// 			$table->engine = 'InnoDB';
// 			$table->increments('id')->unsigned();
// 			$table->string('language_code');
// 			$table->foreign('language_code')->references('code')->on('languages');
// 			$table->integer('position')->nullable();
// 			$table->string('name', 255);
// 			$table->text('description')->nullable();
// 			$table->string('folder_id', 255);
// 			$table->unsignedInteger('user_id')->nullable();
// 			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
// 			$table->unsignedInteger('user_id_edited')->nullable();
// 			$table->foreign('user_id_edited')->references('id')->on('users')->onDelete('set null');
// 			$table->timestamps();
//             $table->softDeletes();
//         });
        

// 		Schema::create(config('app.db-prefix', '').'photos', function (Blueprint $table) {
// 			$table->engine = 'InnoDB';
// 			$table->increments('id')->unsigned();

//             $table->text('description');
//             $table->string('path')->default('');
//             $table->string('relative_url')->default('');
//             $table->string('avg_color', 7)->default('');
//             $table->boolean('is_published')->default(false);

//             $table->string('metadata')->default('');
//             $table->unsignedInteger('created_by_user_id')->nullable();
            
// 			$table->integer('position')->nullable();
// 			$table->boolean('slider')->nullable();
// 			$table->string('filename', 255);
// 			$table->string('name', 255)->nullable();
// 			$table->unsignedInteger('photo_album_id')->nullable();
// 			$table->foreign('photo_album_id')->references('id')->on('photo_albums')->onDelete('set null');
// 			$table->boolean('album_cover')->nullable();
// 			$table->timestamps();
//             $table->softDeletes();
//         });
        

        
// 		Schema::create(config('app.db-prefix', '').'videos', function (Blueprint $table) {
// 			$table->engine = 'InnoDB';
// 			$table->increments('id')->unsigned();
// 			$table->string('name', 255)->nullable();
// 			$table->string('url', 255)->nullable();
// 			$table->string('tempo', 255)->nullable();
// 			$table->string('language', 255)->nullable();
// 			$table->integer('actors')->nullable();
// 			$table->timestamps();
//             $table->softDeletes();
//         });
        

//         Schema::create(config('app.db-prefix', '').'thumbnails', function (Blueprint $table) {
//             $table->increments('id');
//             $table->string('path')->default('');
//             $table->string('relative_url')->default('');
//             $table->unsignedInteger('width')->default(0);
//             $table->unsignedInteger('height')->default(0);
// 			$table->integer('photo_id')->default(0)->nullable();
// 			$table->integer('thumbnail_id')->default(0)->nullable();
//             // $table->foreign('photo_id')->references('id')->on('phonees');
//         });
//         Schema::create(config('app.db-prefix', '').'thumbnailables', function (Blueprint $table) {
//             $table->increments('id');
//             $table->unsignedInteger('thumbnail_id')->nullable();
//             // $table->foreign('thumbnail_id')->references('id')->on('thumbnails');
//             $table->unsignedInteger('thumbnailable_id');
//             $table->string('thumbnailable_type');
//         });

// 	}

// 	/**
// 	 * Reverse the migrations.
// 	 *
// 	 * @return void
// 	 */
// 	public function down()
// 	{
// 		Schema::drop('identity_slaves');
// 	}

// }
