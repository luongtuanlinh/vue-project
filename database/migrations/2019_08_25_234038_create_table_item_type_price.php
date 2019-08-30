<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItemTypePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_type_price', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_type_id');
            $table->tinyInteger('price_type')->comment("1: trong hình, 2:đường trường; 3: 1 buổi; 4: 1 giờ	");
            $table->tinyInteger('time_type')->comment("1: trong h hanh chinh, 2:ngoai h hanh chinh; 3: ngoai h hanh chinh sang chieu; 4: ngoai h hanh chinh toi	");
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_type_price');
    }
}
