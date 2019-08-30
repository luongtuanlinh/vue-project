<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->tinyInteger('type')->comment('1-thi nghe. 2-sat hach');
            $table->string('name');
            $table->integer('class_id')->comment('hang giay phep');
            $table->dateTime('date');
            $table->string('fee', 20);
            $table->tinyInteger('status')->nullable()->comment('1-hoàn thành');
            $table->timestamps();
        });

        Schema::create('exam_student', function (Blueprint $table) {
            $table->integer('exam_id')->unsigned();
            $table->bigInteger('student_id')->unsigned();
            $table->tinyInteger('fee')->default(0)->comment('1-đã nộp phí thi');
            $table->tinyInteger('status')->default(0)->comment('ket qua thi/trang thai');
            $table->integer('score1')->nullable()->comment('diem ly thuyet');
            $table->integer('score2')->nullable()->comment('diem ly thuyet');

            $table->primary(['exam_id', 'student_id']);
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints('exam_student_exam_id_foreign');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_student');
    }
}
