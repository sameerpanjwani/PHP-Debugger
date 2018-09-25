<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DebuggerLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if(!Schema::hasTable('debugger_log')) {
            Schema::create('debugger_log', function (Blueprint $table) {
                $table->increments('id');
                $table->text('message');
                $table->string('subject', 255);
                $table->text('properties');
                $table->string('channel_name', 255);
                $table->string('function_name');
                $table->string('file_name');
                $table->string('class_name');
                $table->string('line_no');
                $table->text('function_arguments');
                $table->integer('time_from_start');
                $table->integer('time_from_previous');
                $table->integer('memory_from_start');
                $table->integer('memory_from_previous');
                $table->dateTime("created_at");

            });
        //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debugger_log');
    }
}
