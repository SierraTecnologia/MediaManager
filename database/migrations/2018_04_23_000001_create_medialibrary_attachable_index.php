<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedialibraryAttachableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('medialibrary_attachable')
            && ! Schema::hasIndex('medialibrary_attachable', 'medialibrary_attachable_attachable_id_attachable_type_index')) {
            Schema::table('medialibrary_attachable', function (Blueprint $table) {
                $table->index(['attachable_id', 'attachable_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('medialibrary_attachable')
            && Schema::hasIndex('medialibrary_attachable', 'medialibrary_attachable_attachable_id_attachable_type_index')) {
            Schema::table('medialibrary_attachable', function (Blueprint $table) {
                $table->dropIndex('medialibrary_attachable_attachable_id_attachable_type_index');
            });
        }
    }
}
