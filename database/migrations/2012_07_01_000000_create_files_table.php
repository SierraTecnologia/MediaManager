<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('files')) {
            Schema::create(
                'files',
                function (Blueprint $table) {
                    $table->engine = 'InnoDB';
                    $table->increments('id')->unsigned();
                    $table->string('name', 255)->nullable();
                    $table->string('description')->nullable();
                    $table->string('url', 255)->nullable();
                    $table->string('path', 255)->nullable();
                    $table->string('filesystem', 255)->default(\Illuminate\Support\Facades\Config::get('rica.storage.disk', env('FILESYSTEM_DRIVER', 'local')));
                    $table->string('type', 255)->nullable();
                    $table->string('filename', 255)->nullable();
                    $table->string('unique_hash', 255)->nullable();
                    $table->string('size', 255)->nullable();
                    $table->string('last_modified', 255)->nullable();

                    $table->string('tags')->nullable();
                    $table->text('details')->nullable();
                    $table->string('extension')->nullable(); //"json"
                    $table->string('mime')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                }
            );
        }
        if (!Schema::hasTable('fileables')) {
            Schema::create(
                'fileables',
                function (Blueprint $table) {
                    $table->increments('id');
                    $table->unsignedInteger('file_id')->nullable();
                    // $table->foreign('file_id')->references('id')->on('files');
                    $table->unsignedInteger('fileable_id');
                    $table->string('fileable_type');
                    $table->unsignedInteger('order')->nullable();
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
      Schema::dropIfExists('fileables');
      Schema::dropIfExists('files');
  }
}
