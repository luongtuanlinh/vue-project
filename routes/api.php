<?php

use Illuminate\Routing\Router;

/**@var Router $router * */

$router->group(['middleware' => ['web', 'auth']], function (Router $router) {
    $router->group(['prefix' => 'teacher'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.teacher.all',
            'uses'=> 'TeacherController@all'
        ]);
        $router->get('/teachers', [
            'as' => 'api.teacher.index',
            'uses'=> 'TeacherController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.teacher.detail',
            'uses'  => 'TeacherController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.teacher.store',
            'uses'=> 'TeacherController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.teacher.update',
            'uses'=> 'TeacherController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.teacher.delete',
            'uses'=> 'TeacherController@delete'
        ]);
    });
    $router->group(['prefix' => 'user'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.user.all',
            'uses'=> 'UserController@all'
        ]);
        $router->get('/users', [
            'as' => 'api.user.index',
            'uses'=> 'UserController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.user.detail',
            'uses'  => 'UserController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.user.store',
            'uses'=> 'UserController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.user.update',
            'uses'=> 'UserController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.user.delete',
            'uses'=> 'UserController@delete'
        ]);
    });
    $router->group(['prefix' => 'item/class'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.item.class.all',
            'uses'=> 'ItemClassController@all'
        ]);
        $router->get('/classes', [
            'as' => 'api.item.class.index',
            'uses'=> 'ItemClassController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.item.class.detail',
            'uses'  => 'ItemClassController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.item.class.store',
            'uses'=> 'ItemClassController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.item.class.update',
            'uses'=> 'ItemClassController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.item.class.delete',
            'uses'=> 'ItemClassController@delete'
        ]);
    });
    $router->group(['prefix' => 'item/type'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.item.type.all',
            'uses'=> 'ItemTypeController@all'
        ]);
        $router->get('/types', [
            'as' => 'api.item.type.index',
            'uses'=> 'ItemTypeController@list'
        ]);
        $router->get('/type/all', [
            'as' => 'api.item.type.type.all',
            'uses'=> 'ItemTypeController@listType'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.item.type.detail',
            'uses'  => 'ItemTypeController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.item.type.store',
            'uses'=> 'ItemTypeController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.item.type.update',
            'uses'=> 'ItemTypeController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.item.type.delete',
            'uses'=> 'ItemTypeController@delete'
        ]);
    });
    $router->group(['prefix' => 'item/type/price'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.item.type.price.all',
            'uses'=> 'ItemTypePriceController@all'
        ]);
        $router->get('/prices', [
            'as' => 'api.item.type.price.index',
            'uses'=> 'ItemTypePriceController@list'
        ]);
        $router->get('/type/all', [
            'as' => 'api.item.type.type.price.all',
            'uses'=> 'ItemTypePriceController@listType'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.item.type.price.detail',
            'uses'  => 'ItemTypePriceController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.item.type.price.store',
            'uses'=> 'ItemTypePriceController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.item.type.price.update',
            'uses'=> 'ItemTypePriceController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.item.type.price.delete',
            'uses'=> 'ItemTypePriceController@delete'
        ]);
    });
    $router->group(['prefix' => 'item/block'], function (Router $router) {
        $router->get('/blocks', [
            'as' => 'api.item.block.index',
            'uses'=> 'ItemBlockController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.item.block.detail',
            'uses'  => 'ItemBlockController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.item.block.store',
            'uses'=> 'ItemBlockController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.item.block.update',
            'uses'=> 'ItemBlockController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.item.block.delete',
            'uses'=> 'ItemBlockController@delete'
        ]);
    });
    $router->group(['prefix' => 'item'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.item.all',
            'uses'=> 'ItemController@all'
        ]);
        $router->get('/items', [
            'as' => 'api.item.index',
            'uses'=> 'ItemController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.item.detail',
            'uses'  => 'ItemController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.item.store',
            'uses'=> 'ItemController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.item.update',
            'uses'=> 'ItemController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.item.delete',
            'uses'=> 'ItemController@delete'
        ]);
    });
    $router->group(['prefix' => 'fee'], function (Router $router) {
        $router->get('/getFee', [
            'as' => 'api.fee.getFee',
            'uses'=> 'FeeController@getFee'
        ]);
        $router->get('/fees', [
            'as' => 'api.fee.index',
            'uses'=> 'FeeController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.fee.detail',
            'uses'  => 'FeeController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.fee.store',
            'uses'=> 'FeeController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.fee.update',
            'uses'=> 'FeeController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.fee.delete',
            'uses'=> 'FeeController@delete'
        ]);
    });
    $router->group(['prefix' => 'course'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.course.all',
            'uses'=> 'CourseController@all'
        ]);
        $router->get('/courses', [
            'as' => 'api.course.index',
            'uses'=> 'CourseController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.course.detail',
            'uses'  => 'CourseController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.course.store',
            'uses'=> 'CourseController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.course.update',
            'uses'=> 'CourseController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.course.delete',
            'uses'=> 'CourseController@delete'
        ]);
    });
    $router->group(['prefix' => 'exam'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.exam.all',
            'uses'=> 'ExamController@all'
        ]);
        $router->get('/exams', [
            'as' => 'api.exam.index',
            'uses'=> 'ExamController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.exam.detail',
            'uses'  => 'ExamController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.exam.store',
            'uses'=> 'ExamController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.exam.update',
            'uses'=> 'ExamController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.exam.delete',
            'uses'=> 'ExamController@delete'
        ]);
        $router->post('students/add', [
            'as' => 'api.exam.add.student',
            'uses'=> 'ExamController@addStudent'
        ]);
        $router->get('{id}/students', [
            'as' => 'api.exam.students',
            'uses'=> 'ExamController@getStudents'
        ]);
        $router->post('{id}/remove/student', [
            'as' => 'api.exam.delete.student',
            'uses'=> 'ExamController@removeStudent'
        ]);
        $router->post('{id}/update/students', [
            'as' => 'api.exam.update.student',
            'uses'=> 'ExamController@updateBulkStudent'
        ]);
        $router->post('{id}/export', [
            'as' => 'api.exam.export',
            'uses'=> 'ExamController@export'
        ]);
    });
    $router->group(['prefix' => 'book'], function (Router $router) {
        $router->get('/books', [
            'as' => 'api.book.index',
            'uses'=> 'OrderController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.book.detail',
            'uses'  => 'OrderController@detail'
        ]);
        $router->get('{id}/info', [
            'as'    => 'api.book.info',
            'uses'  => 'OrderController@info'
        ]);
        $router->post('create', [
            'as' => 'api.book.store',
            'uses'=> 'OrderController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.book.update',
            'uses'=> 'OrderController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.book.delete',
            'uses'=> 'OrderController@delete'
        ]);
        $router->post('student/delete', [
            'as' => 'api.book.student.delete',
            'uses'=> 'OrderController@deleteStudent'
        ]);
    });
    $router->group(['prefix' => 'program'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.program.all',
            'uses'=> 'ProgramController@all'
        ]);
        $router->get('/programs', [
            'as' => 'api.program.index',
            'uses'=> 'ProgramController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.program.detail',
            'uses'  => 'ProgramController@detail'
        ]);
        $router->get('{name}/getByName', [
            'as'    => 'api.program.getByName',
            'uses'  => 'ProgramController@getByName'
        ]);
        $router->post('create', [
            'as' => 'api.program.store',
            'uses'=> 'ProgramController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.program.update',
            'uses'=> 'ProgramController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.program.delete',
            'uses'=> 'ProgramController@delete'
        ]);
    });
    $router->group(['prefix' => 'source'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.source.all',
            'uses'=> 'SourceController@all'
        ]);
        $router->get('/sources', [
            'as' => 'api.source.index',
            'uses'=> 'SourceController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.source.detail',
            'uses'  => 'SourceController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.source.store',
            'uses'=> 'SourceController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.source.update',
            'uses'=> 'SourceController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.source.delete',
            'uses'=> 'SourceController@delete'
        ]);
    });
    $router->group(['prefix' => 'student'], function (Router $router) {
        $router->get('/all', [
            'as' => 'api.student.all',
            'uses'=> 'StudentController@studentsInTeacher'
        ]);
        $router->get('/students', [
            'as' => 'api.student.index',
            'uses'=> 'StudentController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.student.detail',
            'uses'  => 'StudentController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.student.store',
            'uses'=> 'StudentController@store'
        ]);
        $router->post('apply', [
            'as' => 'api.student.apply',
            'uses'=> 'StudentController@apply'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.student.update',
            'uses'=> 'StudentController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.student.delete',
            'uses'=> 'StudentController@delete'
        ]);
    });
    $router->group(['prefix' => 'sfee'], function (Router $router) {
        $router->get('/sfee', [
            'as' => 'api.subfee.index',
            'uses'=> 'SubFeeController@list'
        ]);
        $router->get('{id}/detail', [
            'as'    => 'api.subfee.detail',
            'uses'  => 'SubFeeController@detail'
        ]);
        $router->post('create', [
            'as' => 'api.subfee.store',
            'uses'=> 'SubFeeController@store'
        ]);
        $router->post('{id}/update', [
            'as' => 'api.subfee.update',
            'uses'=> 'SubFeeController@update'
        ]);
        $router->post('delete', [
            'as' => 'api.subfee.delete',
            'uses'=> 'SubFeeController@delete'
        ]);
    });
    $router->group(['prefix' => 'ticket'], function (Router $router) {
        $router->get('index', [
            'as'    => 'api.ticket.list',
            'uses'  => 'TicketController@list'
        ]);
        $router->post('create', [
            'as'    => 'api.ticket.create',
            'uses'  => 'TicketController@create'
        ]);
        $router->post('store', [
            'as'    => 'api.ticket.store',
            'uses'  => 'TicketController@store'
        ]);
        $router->get('get', [
            'as'    => 'api.ticket.get',
            'uses'  => 'TicketController@get'
        ]);
        $router->get('teacher', [
            'as'    => 'api.ticket.teacher',
            'uses'  => 'TicketController@getTickets'
        ]);
        $router->post('teacher', [
            'as'    => 'api.ticket.teacher.assign',
            'uses'  => 'TicketController@assign'
        ]);
    });
    $router->group(['prefix' => 'settings'], function (Router $router) {
        $router->get('get', [
            'as'    => 'api.setting.get',
            'uses'  => 'SettingController@get'
        ]);
        $router->post('store', [
            'as'    => 'api.setting.store',
            'uses'  => 'SettingController@store'
        ]);
        $router->post('calendar', [
            'as'    => 'api.calendar.store',
            'uses'  => 'HolidayController@store'
        ]);
        $router->post('delete/calendar', [
            'as'    => 'api.calendar.delete',
            'uses'  => 'HolidayController@delete'
        ]);
        $router->get('calendar', [
            'as'    => 'api.calendar.get',
            'uses'  => 'HolidayController@get'
        ]);
    });
});
