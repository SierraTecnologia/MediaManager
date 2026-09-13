<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddGroupMedialibraryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('medialibrary_files') && ! Schema::hasColumn('medialibrary_files', 'group')) {
            Schema::table('medialibrary_files', function (Blueprint $table) {
                $table->string('group')->default('default');
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
        if (Schema::hasTable('medialibrary_files') && Schema::hasColumn('medialibrary_files', 'group')) {
            Schema::table('medialibrary_files', function (Blueprint $table) {
                $table->dropColumn('group');
            });
        }
    }
}
