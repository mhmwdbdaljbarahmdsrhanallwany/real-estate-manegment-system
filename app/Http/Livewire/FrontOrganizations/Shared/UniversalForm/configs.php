
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | الإعدادات العامة لمحرك UniversalForm
    |--------------------------------------------------------------------------
    */

    '_defaults' => [

        'has_soft_deletes' => false,
        'has_status'       => false,
        'has_agency'       => false,
        'has_id_type'      => false,
        'has_nationality'  => false,
        'has_payment'      => false,

        'display' => [
            'list'       => true,
            'details'    => true,
            'statistics' => true,
            'search'     => true,
            'filters'    => true,
            'pagination' => true,
        ],

        'actions' => [
            'create' => true,
            'edit'   => true,
            'delete' => true,
            'view'   => true,
            'status' => false,
        ],

        'search' => [
            'enabled' => true,
            'fields'  => [],
        ],

        'pagination' => [
            'enabled' => true,
            'per_page' => 10,
            'options' => [10, 25, 50, 100],
        ],

        'relations' => [],

        'main_fields' => [],
        'sub_fields'  => [],
        'filters'     => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | إدارة الملاك والوكلاء
    |--------------------------------------------------------------------------
    */

    'owners' => [

        /*
        |--------------------------------------------------------------------------
        | تعريف الجدول
        |--------------------------------------------------------------------------
        */

        'table'       => 'owners',
        'primary_key' => 'owner_id',
        'name_field'  => 'full_name',

        'has_soft_deletes' => true,

        'has_status'      => true,
        'has_agency'      => true,
        'has_id_type'     => true,
        'has_nationality' => true,
        'has_payment'     => true,

        /*
        |--------------------------------------------------------------------------
        | إعدادات العرض
        |--------------------------------------------------------------------------
        */

        'display' => [
            'list'       => true,
            'details'    => true,
            'statistics' => true,
            'search'     => true,
            'filters'    => true,
            'pagination' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | العمليات المسموح بها في الواجهة
        |--------------------------------------------------------------------------
        */

        'actions' => [
            'create' => true,
            'edit'   => true,
            'delete' => true,
            'view'   => true,
            'status' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | البحث
        |--------------------------------------------------------------------------
        */

        'search' => [
            'enabled' => true,
            'fields' => [
                'full_name',
                'phone',
                'phone2',
                'email',
                'id_number',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | إعدادات الترقيم
        |--------------------------------------------------------------------------
        */

        'pagination' => [
            'enabled'  => true,
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        /*
        |--------------------------------------------------------------------------
        | العلاقة مع الوكلاء
        |--------------------------------------------------------------------------
        */

        'sub_table'       => 'owner_agents',
        'sub_primary_key' => 'agent_id',
        'sub_foreign_key' => 'owner_id',

        'relations' => [

            'agents' => [
                'type'         => 'hasMany',
                'table'        => 'owner_agents',
                'primary_key'  => 'agent_id',
                'foreign_key'  => 'owner_id',
                'parent_key'   => 'owner_id',
                'name_field'   => 'full_name',
                'enabled'      => true,
                'has_status'   => true,
                'has_soft_deletes' => false,
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | معلومات الصفحة
        |--------------------------------------------------------------------------
        */

        'title'             => 'إدارة المالكين',
        'subtitle'          => 'عرض، إضافة، تعديل، حظر وإدارة وكلاء المالكين',
        'icon'              => 'fas fa-users-gear',
        'entity_name'       => 'المالكين',
        'entity_singular'   => 'مالك',
        'entity_sub'        => 'وكيل',
        'btn_add_main'      => 'إضافة مالك',
        'btn_add_sub'       => 'إضافة وكيل',
        'sub_section_title' => 'وكلاء المالك',
        'sub_section_icon'  => 'fas fa-user-tie',

        /*
        |--------------------------------------------------------------------------
        | حقول المالك الرئيسية
        |--------------------------------------------------------------------------
        */

        'main_fields' => [

            [
                'key'        => 'full_name',
                'label'      => 'الاسم الكامل',
                'type'       => 'text',
                'icon'       => 'fas fa-user',
                'required'   => true,
                'searchable' => true,
                'show_in_list' => true,
            ],

            [
                'key'        => 'phone',
                'label'      => 'رقم الجوال',
                'type'       => 'tel',
                'icon'       => 'fas fa-mobile-screen',
                'required'   => true,
                'searchable' => true,
                'show_in_list' => true,
            ],

            [
                'key'        => 'phone2',
                'label'      => 'جوال ثانوي',
                'type'       => 'tel',
                'icon'       => 'fas fa-mobile-screen-button',
                'searchable' => true,
            ],

            [
                'key'        => 'email',
                'label'      => 'البريد الإلكتروني',
                'type'       => 'email',
                'icon'       => 'fas fa-envelope',
                'searchable' => true,
            ],

            [
                'key'      => 'id_type',
                'label'    => 'نوع الهوية',
                'type'     => 'select',
                'icon'     => 'fas fa-id-card',
                'default'  => 'national_id',

                'options' => [
                    ['value' => 'national_id',    'label' => 'هوية وطنية'],
                    ['value' => 'iqama',          'label' => 'إقامة'],
                    ['value' => 'passport',       'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],

            [
                'key'        => 'id_number',
                'label'      => 'رقم الهوية',
                'type'       => 'text',
                'icon'       => 'fas fa-hashtag',
                'searchable' => true,
            ],

            [
                'key'     => 'nationality',
                'label'   => 'الجنسية',
                'type'    => 'text',
                'icon'    => 'fas fa-globe',
                'default' => 'سعودي',
            ],

            [
                'key'  => 'address',
                'label' => 'العنوان',
                'type'  => 'text',
                'icon'  => 'fas fa-location-dot',
                'full'  => true,
            ],

            [
                'key'   => 'bank_name',
                'label' => 'اسم البنك',
                'type'  => 'text',
                'icon'  => 'fas fa-building-columns',
            ],

            [
                'key'   => 'bank_account_name',
                'label' => 'اسم صاحب الحساب',
                'type'  => 'text',
                'icon'  => 'fas fa-user-check',
            ],

            [
                'key'   => 'bank_account_number',
                'label' => 'رقم الحساب',
                'type'  => 'text',
                'icon'  => 'fas fa-credit-card',
            ],

            [
                'key'   => 'bank_iban',
                'label' => 'رقم الآيبان',
                'type'  => 'text',
                'icon'  => 'fas fa-barcode',
            ],

            [
                'key'     => 'preferred_payment_method',
                'label'   => 'طريقة الدفع',
                'type'    => 'select',
                'icon'    => 'fas fa-wallet',
                'default' => 'bank_transfer',

                'options' => [
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque',        'label' => 'شيك'],
                    ['value' => 'cash',          'label' => 'نقداً'],
                ],
            ],

            [
                'key'       => 'status',
                'label'     => 'الحالة',
                'type'      => 'select',
                'icon'      => 'fas fa-toggle-on',
                'default'   => 'active',
                'is_status' => true,

                'options' => [
                    ['value' => 'active',   'label' => 'نشط'],
                    ['value' => 'inactive', 'label' => 'غير نشط'],
                    ['value' => 'blocked',  'label' => 'محظور'],
                ],
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'full'  => true,
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | حقول الوكلاء
        |--------------------------------------------------------------------------
        */

        'sub_fields' => [

            [
                'key'        => 'full_name',
                'label'      => 'اسم الوكيل',
                'type'       => 'text',
                'icon'       => 'fas fa-user',
                'required'   => true,
                'searchable' => true,
            ],

            [
                'key'        => 'phone',
                'label'      => 'رقم الجوال',
                'type'       => 'tel',
                'icon'       => 'fas fa-mobile-screen',
                'searchable' => true,
            ],

            [
                'key'        => 'email',
                'label'      => 'البريد الإلكتروني',
                'type'       => 'email',
                'icon'       => 'fas fa-envelope',
                'searchable' => true,
            ],

            [
                'key'     => 'role',
                'label'   => 'دور الوكيل',
                'type'    => 'select',
                'icon'    => 'fas fa-briefcase',
                'default' => 'administrative',

                'options' => [
                    ['value' => 'administrative',   'label' => 'وكيل إداري'],
                    ['value' => 'legal',            'label' => 'وكيل قانوني'],
                    ['value' => 'property_manager', 'label' => 'مدير عقارات'],
                ],
            ],

            [
                'key'   => 'power_of_attorney_file',
                'label' => 'ملف التوكيل',
                'type'  => 'text',
                'icon'  => 'fas fa-file-contract',
                'full'  => true,
            ],

            [
                'key'   => 'authority_start_date',
                'label' => 'بداية التفويض',
                'type'  => 'date',
                'icon'  => 'fas fa-calendar-plus',
            ],

            [
                'key'   => 'authority_end_date',
                'label' => 'نهاية التفويض',
                'type'  => 'date',
                'icon'  => 'fas fa-calendar-check',
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'full'  => true,
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | فلاتر الملاك
        |--------------------------------------------------------------------------
        */

        'filters' => [

            [
                'key'   => 'status',
                'label' => 'الحالة',
                'icon'  => 'fas fa-toggle-on',
                'type'  => 'select',

                'options' => [
                    ['value' => 'all',      'label' => 'الكل'],
                    ['value' => 'active',   'label' => 'نشط'],
                    ['value' => 'inactive', 'label' => 'غير نشط'],
                    ['value' => 'blocked',  'label' => 'محظور'],
                ],
            ],

            [
                'key'   => 'id_type',
                'label' => 'نوع الهوية',
                'icon'  => 'fas fa-id-card',
                'type'  => 'select',

                'options' => [
                    ['value' => 'all',            'label' => 'الكل'],
                    ['value' => 'national_id',    'label' => 'هوية وطنية'],
                    ['value' => 'iqama',          'label' => 'إقامة'],
                    ['value' => 'passport',       'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],

            [
                'key'   => 'nationality',
                'label' => 'الجنسية',
                'icon'  => 'fas fa-globe',
                'type'  => 'select',

                'options' => [
                    ['value' => 'all',   'label' => 'الكل'],
                    ['value' => 'سعودي', 'label' => 'سعودي'],
                    ['value' => 'يمني',  'label' => 'يمني'],
                    ['value' => 'مقيم',  'label' => 'مقيم'],
                ],
            ],

            [
                'key'   => 'dateFrom',
                'label' => 'من تاريخ',
                'icon'  => 'fas fa-calendar-plus',
                'type'  => 'date',
            ],

            [
                'key'   => 'dateTo',
                'label' => 'إلى تاريخ',
                'icon'  => 'fas fa-calendar-check',
                'type'  => 'date',
            ],

            [
                'key'   => 'payment',
                'label' => 'طريقة الدفع',
                'icon'  => 'fas fa-wallet',
                'type'  => 'select',

                'options' => [
                    ['value' => 'all',           'label' => 'الكل'],
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque',        'label' => 'شيك'],
                    ['value' => 'cash',          'label' => 'نقداً'],
                ],
            ],

            [
                'key'   => 'hasSub',
                'label' => 'الوكلاء',
                'icon'  => 'fas fa-user-tie',
                'type'  => 'select',

                'options' => [
                    ['value' => 'all',  'label' => 'الكل'],
                    ['value' => 'has',  'label' => 'لديه وكلاء'],
                    ['value' => 'none', 'label' => 'بدون وكلاء'],
                ],
            ],

            [
                'key'   => 'sort',
                'label' => 'الترتيب',
                'icon'  => 'fas fa-sort',
                'type'  => 'select',

                'options' => [
                    ['value' => 'newest',    'label' => 'الأحدث أولاً'],
                    ['value' => 'oldest',    'label' => 'الأقدم أولاً'],
                    ['value' => 'name_asc',  'label' => 'الاسم (أ-ي)'],
                    ['value' => 'name_desc', 'label' => 'الاسم (ي-أ)'],
                ],
            ],

        ],

    ],

];
