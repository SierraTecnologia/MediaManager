<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMedialibraryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('medialibrary_categories', function (Blueprint $table) {

                // Primary key
                $table->increments('id');

                // Foreign key
                /** @var \Illuminate\Database\Eloquent\Model $owner */
                $owner = app(config('media-library.relations.owner.model', 'App\Models\User'));

                $table->integer('owner_id')->unsigned();
                $table->foreign('owner_id')
                    ->references($owner->getKeyName())
                    ->on($owner->getTable())
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                $table->integer('parent_id')->unsigned()->nullable();
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('medialibrary_categories')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                // Data
                $table->string('name');
                $table->integer('order')->unsigned()->default(0);

                // Metadata
                $table->timestamps();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medialibrary_categories');
    }
}
