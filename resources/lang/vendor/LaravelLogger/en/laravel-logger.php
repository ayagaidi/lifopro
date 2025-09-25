<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Language Lines - Global
    |--------------------------------------------------------------------------
    */
    'userTypes' => [
        'guest'      => 'ضيف',
        'registered' => 'مسجل',
        'crawler'    => 'الزاحف',
    ],

    'verbTypes' => [
        'created'    => 'أنشئت في',
        'edited'     => 'عدلت في',
        'deleted'    => 'حدفت في',
        'viewed'     => 'عرضت في',
        'crawled'    => 'crawled',
    ],

    'listenerTypes' => [
        'auth'       => 'نشاط مؤثق',
        'attempt'    => 'محاولة موثقة',
        'failed'     => 'محاولة تسجيل الدخول الفاشلة',
        'lockout'    => 'اقفل',
        'reset'      => 'إعادة تعيين كلمة المرور',
        'login'      => 'مسجّل الدخول',
        'logout'     => 'تسجيل الخروج',
    ],

    'tooltips' => [
        'viewRecord' => 'عرض تفاصيل السجل',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Admin Dashboard Language Lines
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'title'     => 'سجل النشاطات',
        'subtitle'  => 'الأحداث',

        'labels'    => [
            'id'            => 'Id',
            'time'          => 'وقت',
            'description'   => 'وصف',
            'user'          => 'مستخدم',
            'method'        => 'طريقة',
            'route'         => 'طريق',
            'ipAddress'     => 'Ip <span class="hidden-sm hidden-xs">عنوان</span>',
            'agent'         => '<span class="hidden-sm hidden-xs">المتصفح </span>',
            'deleteDate'    => '<span class="hidden-sm hidden-xs">التاريخ </span>Deleted',
        ],

        'menu'      => [
            'alt'           => 'قائمة سجل النشاط',
            'clear'         => 'مسح سجل النشاط',
            'show'          => 'إظهار السجلات التي تم مسحها',
            'back'          => 'العودة إلى سجل النشاط',
        ],

        'search'    => [
            'all'           => 'الكل',
            'search'        => 'بحث',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Admin Drilldown Language Lines
    |--------------------------------------------------------------------------
    */

    'drilldown' => [
        'title'                 => 'سجل النشاطات :id',
        'title-details'         => 'تفاصيل النشاط',
        'title-ip-details'      => 'تفاصيل عنوان IP',
        'title-user-details'    => 'بيانات المستخدم',
        'title-user-activity'   => 'نشاط مستخدم إضافي',

        'buttons'   => [
            'back'      => '<span class="hidden-xs hidden-sm">ارجع الى </span><span class="hidden-xs">سجل النشاطات</span>',
        ],

        'labels' => [
            'userRoles'     => 'User Roles',
            'userLevel'     => 'Level',
        ],

        'list-group' => [
            'labels'    => [
                'id'            => 'سجل النشاطات ID:',
                'ip'            => 'عنوان IP',
                'description'   => 'وصف',
                'details'       => 'تفاصيل',
                'userType'      => 'نوع المستخدم',
                'userId'        => 'رقم المستخدم ',
                'route'         => 'الطريق',
                'agent'         => 'المتصفح',
                'locale'        => 'محلي',
                'referer'       => 'المرجع',

                'methodType'    => 'نوع الطريقة',
                'createdAt'     => 'وقت الحدث',
                'updatedAt'     => 'عدلت في',
                'deletedAt'     => ' حدفت في',
                'timePassed'    => 'مر الوقت',
                'userName'      => 'اسم المستخدم',
                'userFirstName' => 'الاسم الأول',
                'userLastName'  => 'اسم العائلة',
                'userFulltName' => 'الاسم الكامل',
                'userEmail'     => 'البريد الالكتروني للمستخدم',
                'userSignupIp'  => 'اشترك في IP',
                'userCreatedAt' => 'آنشآت في',
                'userUpdatedAt' => 'عدلت في',
            ],

            'fields' => [
                'none' => 'None',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Modals
    |--------------------------------------------------------------------------
    */

    'modals' => [
        'shared' => [
            'btnCancel'     => 'الغاء',
            'btnConfirm'    => 'تآكيد',
        ],
        'clearLog' => [
            'title'     => 'مسح سجل النشاط',
            'message'   => 'هل أنت متأكد أنك تريد مسح سجل النشاط?',
        ],
        'deleteLog' => [
            'title'     => 'حذف سجل النشاط بشكل دائم',
            'message'   => 'هل أنت متأكد أنك تريد حذف سجل النشاط بشكل دائم?',
        ],
        'restoreLog' => [
            'title'     => 'استعادة سجل النشاط الذي تم مسحه',
            'message'   => 'هل أنت متأكد أنك تريد استعادة سجلات النشاط التي تم مسحها؟',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Flash Messages
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'logClearedSuccessfuly'   => 'تم مسح سجل النشاط بنجاح',
        'logDestroyedSuccessfuly' => 'تم حذف سجل النشاط بنجاح',
        'logRestoredSuccessfuly'  => 'تمت استعادة سجل النشاط بنجاح',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Cleared Dashboard Language Lines
    |--------------------------------------------------------------------------
    */

    'dashboardCleared' => [
        'title'     => 'مسح سجلات النشاط',
        'subtitle'  => 'الأحداث التي تم مسحها',

        'menu'      => [
            'deleteAll'  => 'احذف كل سجلات النشاط',
            'restoreAll' => 'استعادة كافة سجلات النشاط',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Pagination Language Lines
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'countText' => 'عرض :firstItem - :lastItem ل :total نتائج <small>(:perPage per صفحة)</small>',
    ],

];
