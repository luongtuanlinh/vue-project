<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_block', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->nullable()->comment('hạng xe');
            $table->integer('item_id')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('user')->comment('nhân vien');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('note')->nullable();
            $table->tinyInteger('repeat')->default(0)->comment('lặp lại');
            $table->integer('user')->comment('nhan vien');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_block');
        Schema::dropIfExists('holidays');
    }
}
