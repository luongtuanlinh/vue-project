<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->integer('role');
            $table->string('permissions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
        });

        //thong tin dang nhap
        Schema::create('user_login', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->bigInteger('user_id');
            $table->string('loginID');
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();
        });

        //bang hoc vien
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->string('mobile', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->dateTime('birth_day');
            $table->tinyInteger('gender');
            $table->integer('status')->default(0);
            $table->string('phone', 20)->nullable()->comment('sđt ng thân/bạn bè');
            $table->string('passport', 20)->comment('số CMND/căn cước');
            $table->dateTime('passport_date')->comment('ngày cấp');
            $table->integer('passport_address')->comment('nơi cấp');
            $table->string('driver_no', 20)->nullable()->comment('số GPLX cũ');
            $table->integer('driver_class')->nullable()->comment('hạng GPLX cũ');
            $table->dateTime('driver_date')->nullable()->comment('ngày cấp GPLX');
            $table->string('driver_address')->nullable()->comment('nơi cấp GPLX');
            $table->integer('course_id');
            $table->integer('program_id');
            $table->integer('teacher_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('fee')->default(0);
            $table->string('sub_fee')->default(0);
            $table->string('discount')->default(0);
            $table->tinyInteger('has_picture')->default(0);
            $table->tinyInteger('has_passport')->default(0);
            $table->tinyInteger('has_apply')->default(0)->comment('đơn');
            $table->tinyInteger('health_certification')->default(0)->comment('giấy khám sk');
            $table->dateTime('health_certification_date')->nullable();
            $table->text('note')->nullable();
            $table->integer('user_id')->comment('nhân viên');
            $table->timestamps();
            $table->softDeletes();
        });

        //hạng xe
        Schema::create('item_class', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //loai xe
        Schema::create('item_type', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger("type");
            $table->string('name');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //danh sách xe
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('type');
            $table->timestamps();
            $table->softDeletes();
        });

        //chương trình đào tạo
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('time')->default(0)->comment('thời gian học (giờ)');
            $table->string('fee');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        //nguồn tuyển sinh
        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        //khoa hoc
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car_class_id')->comment('hạng xe');
            $table->string('name');
            $table->tinyInteger('status');
            $table->string('quantity')->default(0)->comment('sl học viên đăng ký');
            $table->string('max')->comment('sl học viên tối đa');
            $table->dateTime('start_time')->comment('ngày khai giảng');
            $table->dateTime('end_time')->comment('ngày bế giảng');
            $table->dateTime('exam_time')->comment('ngày thi sát hạch');
            $table->dateTime('exam_end_time')->comment('ngày thi tốt nghiệp');
            $table->timestamps();
            $table->softDeletes();
        });

        //giao viên đặt giữ chỗ cho hv
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->comment('1-user/2-giao vien');
            $table->integer('user_id')->comment('id giáo viên');
            $table->integer('course_id')->comment('id khóa học');
            $table->integer('applied')->default(0)->comment('sl hồ sơ đã nộp');
            $table->integer('quantity')->comment('sl học viên');
            $table->dateTime('time_order')->comment('ngày đặt');
            $table->dateTime('expire_date')->comment('ngày hết hạn');
            $table->string('total_fee')->comment('tổng phí');
            $table->string('paid')->default(0)->comment('học phí đã đóng');
            $table->integer('admin_id')->comment('nhân viên');
            $table->timestamps();
        });

        //học phí
        Schema::create('fees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id');
            $table->integer('program_id');
            $table->string('fee');
            $table->integer('time')->default(1);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        //lệ phí
        Schema::create('sub_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('fee');
            $table->tinyInteger('status')->default(1);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //khách đặt vé tập
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email', 100)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        //đặt vé tập
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('item_id')->comment('xe tập');
            $table->string('customer_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('price', 20);
            $table->tinyInteger('type');
            $table->tinyInteger('price_type');
            $table->tinyInteger('status');
            $table->integer('teacher_id')->nullable();
            $table->integer('user_id')->comment('nhan vien');
            $table->timestamps();
        });

        //thanh toán vé tập
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code');
            $table->tinyInteger('type')->comment('loại thu chi');
            $table->bigInteger('target_id');
            $table->string('cost', 20);
            $table->tinyInteger('payment_method')->default(1);
            $table->integer('user');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_login');
        Schema::dropIfExists('students');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('student_class');
        Schema::dropIfExists('item_class');
        Schema::dropIfExists('item_type');
        Schema::dropIfExists('items');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('sources');
        Schema::dropIfExists('fees');
        Schema::dropIfExists('sub_fees');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('purchases');
    }
}
