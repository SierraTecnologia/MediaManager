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
            'files',
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
                $table->string('last_modified', 255)->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'fileables',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('file_id')->nullable();
                // $table->foreign('file_id')->references('id')->on('files');
                $table->string('fileable_id');
                $table->string('fileable_type');
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
        Schema::dropIfExists('fileables');
        Schema::dropIfExists('files');
    }
}
