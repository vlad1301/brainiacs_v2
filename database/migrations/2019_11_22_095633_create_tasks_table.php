<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('keyword_id');
            $table->integer('project_id');
            $table->integer('user_id');
            $table->bigInteger('task_API_id');
            $table->string('string_time'); // type 1970 format

            $table->time('created_at'); // type 1970 format
            $table->time('updated_at'); // type 1970 format
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
