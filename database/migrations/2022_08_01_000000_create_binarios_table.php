<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinariosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('binaries')) {
            Schema::create(
                'binaries',
                function (Blueprint $table) {
                    $table->engine = 'InnoDB';
                    $table->string('hash', 64)->primary();
                    $table->string('type', 255)->nullable();
                    $table->string('size', 255)->nullable();
                    $table->string('tags')->nullable();
                    $table->text('details')->nullable();
                    $table->string('extension', 6)->nullable(); //"json"
                    $table->string('mime')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                }
            );
        }
        // if (!Schema::hasTable('binaryables')) {
        //     Schema::create(
        //         'binaryables',
        //         function (Blueprint $table) {
        //             $table->uuid('id')->primary();
        //             $table->uuid('binary_id');
        //             $table->foreign('binary_id')->references('id')->on('binaries');
        //             $table->uuid('binaryable_id');
        //             $table->string('binaryable_type');
        //             $table->unsignedInteger('order')->nullable();
        //         }
        //     );
        // }
      }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //   Schema::dropIfExists('binaryables');
      Schema::dropIfExists('binaries');
  }
}
