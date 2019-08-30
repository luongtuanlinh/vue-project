<?php

return [
    'dashboard' => [
        'name' => 'dashboard',
        'text' => 'Dashboard',
        'icon' => 'fa fa-dashboard',
        'router' => '/admin/dashboard',
        'order' => 0,
        'authorize' => [],
    ],
    'student' => [
        'name' => 'student',
        'text' => 'Học viên',
        'icon' => 'fa fa-graduation-cap',
        'router' => '#',
        'order' => 10,
        'authorize' => [],
        'sub'   => [
            'student' => [
                'name' => 'student',
                'text' => 'Danh sách học viên',
                'icon' => 'fa fa-users',
                'router' => '/admin/student/students',
                'order' => 1,
                'authorize' => [],
            ],
            'dang-ky-hoc' => [
                'name' => 'dang-ky-hoc',
                'text' => 'Đăng ký học',
                'icon' => 'fa fa-user',
                'router' => '/admin/student/create',
                'order' => 1,
                'authorize' => [],
            ]
        ]
    ],
    'teacher' => [
        'name' => 'teacher',
        'text' => 'Danh sách giáo viên',
        'icon' => 'fa fa-user-circle',
        'router' => '/admin/teacher/teachers',
        'order' => 20,
        'authorize' => [],
    ],
    'class' => [
        'name' => 'class',
        'text' => 'Hạng giấy phép',
        'icon' => 'fa fa-font-awesome',
        'router' => '/admin/class/classes',
        'order' => 2,
        'authorize' => [],
    ],
    'xe'    => [
        'name' => 'xe',
        'text' => 'Xe',
        'icon' => 'fa fa-car',
        'router' => '#',
        'order' => 2,
        'authorize' => [],
        'sub'   => [
            'item_type' => [
                'name' => 'item_type',
                'text' => 'Loại xe',
                'icon' => 'fa fa-font-awesome',
                'router' => '/admin/item/type/type',
                'order' => 2,
                'authorize' => [],
            ],
            'item_type_price' => [
                'name' => 'item_type',
                'text' => 'Giá theo loại xe',
                'icon' => 'fa fa-money',
                'router' => '/admin/item/type/price',
                'order' => 2,
                'authorize' => [],
            ],
            'item' => [
                'name' => 'item',
                'text' => 'Xe',
                'icon' => 'fa fa-car',
                'router' => '/admin/item/items',
                'order' => 2,
                'authorize' => [],
            ],
            'item_block' => [
                'name' => 'item',
                'text' => 'Lịch khóa xe',
                'icon' => 'fa fa-key',
                'router' => '/admin/item/block/blocks',
                'order' => 2,
                'authorize' => [],
            ],
        ]
    ],
    'program' => [
        'name' => 'program',
        'text' => 'Chương trình đào tạo',
        'icon' => 'fa fa-car',
        'router' => '/admin/program/programs',
        'order' => 2,
        'authorize' => [],
    ],
    'course' => [
        'name' => 'course',
        'text' => 'Khóa học',
        'icon' => 'fa fa-car',
        'router' => '#',
        'order' => 2,
        'authorize' => [],
        'sub'   => [
            'course' => [
                'name' => 'course',
                'text' => 'Danh sách khóa học',
                'icon' => 'fa fa-car',
                'router' => '/admin/course/courses',
                'order' => 2,
                'authorize' => [],
            ],
            'order' => [
                'name' => 'course',
                'text' => 'Danh sách đặt giữ chỗ',
                'icon' => 'fa fa-car',
                'router' => '/admin/book/books',
                'order' => 2,
                'authorize' => [],
            ],
        ]
    ],
    'exam' => [
        'name' => 'exam',
        'text' => 'Quản lý thi',
        'icon' => 'fa fa-car',
        'router' => '/admin/exam/exams',
        'order' => 2,
        'authorize' => [],
    ],
    'ticket' => [
        'name' => 'ticket',
        'text' => 'Vé tập',
        'icon' => 'fa fa-ticket',
        'router' => '#',
        'order' => 1,
        'authorize' => [],
        'sub'   => [
            'book' => [
                'name' => 'ticket',
                'text' => 'Đặt vé',
                'icon' => 'fa fa-plus',
                'router' => '/admin/ticket/book',
                'order' => 1,
                'authorize' => [],
                ],
            'list' => [
                'name' => 'ticket',
                'text' => 'Danh sách vé tập',
                'icon' => 'fa fa-database',
                'router' => '/admin/ticket/tickets',
                'order' => 1,
                'authorize' => [],
            ],
            'teacher' => [
                'name' => 'teacher',
                'text' => 'Điều phối giáo viên',
                'icon' => 'fa fa-hand-o-right',
                'router' => '/admin/ticket/teacher',
                'order' => 1,
                'authorize' => [],
            ]
        ]
    ],
    'fee' => [
        'name' => 'fee',
        'text' => 'Học phí',
        'icon' => 'fa fa-money',
        'router' => '/admin/fee/fees',
        'order' => 1,
        'authorize' => [],
    ],
    'settings' => [
        'name' => 'settings',
        'text' => 'Cài dặt',
        'icon' => 'fa fa-cogs',
        'router' => '#',
        'order' => 100,
        'authorize' => [],
        'sub'   => [
            'settings' => [
                'name' => 'settings',
                'text' => 'Cài dặt hệ thống',
                'icon' => 'fa fa-cogs',
                'router' => '/admin/setting/general',
                'order' => 1,
                'authorize' => []
            ],
            'date' => [
                'name' => 'date',
                'text' => 'Ngày nghỉ lễ',
                'icon' => 'fa fa-calendar',
                'router' => '/admin/setting/calendar',
                'order' => 1,
                'authorize' => [],
            ],
        ]
    ],
];
