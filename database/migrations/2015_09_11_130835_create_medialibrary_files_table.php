<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMedialibraryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('medialibrary_files', function (Blueprint $table) {

                // Primary key
                $table->char('id', 36);
                $table->primary('id');

                // Foreign keys
                /** @var \Illuminate\Database\Eloquent\Model $owner */
                $owner = app(config('media-library.relations.owner.model', 'App\Models\User'));

                $table->integer('owner_id')->unsigned();
                $table->foreign('owner_id')
                    ->references($owner->getKeyName())
                    ->on($owner->getTable())
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                if (!is_null(config('media-library.relations.user.model'))) {
                    /** @var \Illuminate\Database\Eloquent\Model $user */
                    $user = app(config('media-library.relations.user.model'));

                    $table->integer('user_id')->unsigned()->nullable();
                    $table->foreign('user_id')
                        ->references($user->getKeyName())
                        ->on($user->getTable())
                        ->onUpdate('CASCADE')
                        ->onDelete('SET NULL');
                }

                $table->integer('category_id')->unsigned()->nullable();
                $table->foreign('category_id')
                    ->references('id')
                    ->on('medialibrary_categories')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');

                // Data
                $table->string('name')->nullable();
                $table->text('caption')->nullable();

                // Metadata
                $table->timestamps();
                $table->text('properties')->nullable();
                $table->smallInteger('width')->nullable();
                $table->smallInteger('height')->nullable();

                // Properties
                $table->string('type');
                $table->string('disk');
                $table->string('filename');
                $table->string('extension');
                $table->string('mime_type');
                $table->integer('size');

                // Flags
                $table->boolean('hidden')->default(false);
                $table->boolean('completed')->default(false);
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
        Schema::dropIfExists('medialibrary_files');
    }
}
