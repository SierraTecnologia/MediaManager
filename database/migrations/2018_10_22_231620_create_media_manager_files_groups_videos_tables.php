<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaManagerFilesGroupsVideosTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'videos',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->unsigned();
                $table->string('name', 255)->nullable();
                $table->string('description')->nullable();
                $table->string('url', 255)->nullable();
                $table->string('path', 255)->nullable();
                $table->string('relative_path', 255)->nullable();
                $table->string('filesystem', 255)->default(\Illuminate\Support\Facades\Config::get('rica.storage.disk', env('FILESYSTEM_DRIVER', 'local')));
                $table->string('type', 255)->nullable();
                $table->string('filename', 255)->nullable();
                $table->string('size', 255)->nullable();
                $table->string('tempo', 255)->nullable();
                $table->string('language', 255)->nullable();
                $table->string('actors', 255)->nullable();
                $table->string('last_modified', 255)->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'videoables',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('video_id')->nullable();
                // $table->foreign('video_id')->references('id')->on('videos');
                $table->string('videoable_id');
                $table->string('videoable_type');
                $table->unsignedInteger('position')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('videoables');
        Schema::dropIfExists('videos');
    }
}
