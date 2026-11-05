<?php

return [

    /*
    |--------------------------------------------------------------------------
    | الإعدادات الافتراضية — تُدمج مع أي كيان
    |--------------------------------------------------------------------------
    */

    '_defaults' => [

        'table'             => null,
        'primary_key'       => 'id',
        'name_field'        => null,
        'has_soft_deletes'  => false,
        'deleted_at_column' => 'deleted_at',

        // الحالة
        'status' => [
            'column'      => 'status',
            'values'      => ['active', 'inactive', 'blocked'],
            'boolean_map' => null, // مثال: ['true'=>'blocked','false'=>'active'] لعمود منطقي
            'labels'      => [
                'active'   => 'نشط',
                'inactive' => 'غير نشط',
                'blocked'  => 'محظور',
            ],
        ],

        // نصوص عامة للواجهة
        'title'             => '',
        'subtitle'          => '',
        'icon'              => 'fas fa-table',
        'entity_name'       => 'سجل',
        'entity_singular'   => 'سجل',
        'entity_sub'        => 'عنصر',
        'btn_add_main'      => 'إضافة',
        'btn_add_sub'       => 'إضافة عنصر',
        'sub_section_title' => 'عناصر مرتبطة',
        'sub_section_icon'  => 'fas fa-list',

        // إظهار الأقسام
        'display' => [
            'list'       => true,
            'details'    => true,
            'statistics' => true,
            'search'     => true,
            'filters'    => true,
            'pagination' => true,
        ],

        // العمليات المسموحة
        'actions' => [
            'create' => true,
            'edit'   => true,
            'delete' => true,
            'view'   => true,
            'status' => false,
        ],

        // البحث
        'search' => [
            'enabled' => true,
            'fields'  => [],
        ],

        // الترقيم
        'pagination' => [
            'enabled'  => true,
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        // البيانات
        'main_fields' => [],
        'sub_fields'  => [],
        'filters'     => [],
        'relations'   => [],
        'lookups'     => [],
        'stats'       => [],

        // خريطة العرض
        'ui' => [
            'list' => [
                'avatar_field'   => null,
                'title_field'    => null,
                'subtitle_field' => null,
                'subtitle_icon'  => 'fas fa-circle-info',
                'status_field'   => 'status',
            ],
            'details' => [
                'avatar_field'  => null,
                'title_field'   => null,
                'status_field'  => 'status',
                'header_fields' => [],
                'info_fields'   => [],
                'notes_field'   => null,
            ],
            'relation' => [
                'title_field'    => 'name',
                'subtitle_field' => null,
                'type_field'     => null,
                'status_field'   => 'status',
                'status_icon'    => 'fas fa-circle-info',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | كيان: الملاك والوكلاء
    |--------------------------------------------------------------------------
    */

    'owners' => [

        'table'             => 'owners',
        'primary_key'       => 'owner_id',
        'organization_column' => 'organization_id',
        'name_field'        => 'full_name',
        'has_soft_deletes'  => true,
        'deleted_at_column' => 'deleted_at',



    // حماية بيانات المؤسسة
    'tenant_scoped' => true,

    // لا تجعل هذا الكيان مشتركاً
    'allow_shared' => false,

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

        'status' => [
            'column'      => 'is_blacklisted',
            'boolean_map' => ['true' => 'blocked', 'false' => 'active'],
            'values'      => ['active', 'inactive', 'blocked'],
            'labels'      => [
                'active'   => 'نشط',
                'inactive' => 'غير نشط',
                'blocked'  => 'محظور',
            ],
        ],

        'display' => [
            'list' => true, 'details' => true, 'statistics' => true,
            'search' => true, 'filters' => true, 'pagination' => true,
        ],

        'actions' => [
            'create' => true, 'edit' => true, 'delete' => true,
            'view'   => true, 'status' => true,
        ],

        'search' => [
            'enabled' => true,
            'fields'  => ['full_name', 'phone', 'phone2', 'email', 'id_number'],
        ],

        'pagination' => [
            'enabled' => true, 'per_page' => 10, 'options' => [10, 25, 50, 100],
        ],

        /* ------- جداول مساعدة لتحويل المفاتيح إلى أسماء ------- */
        'lookups' => [
            'organization_id' => [
                'table'   => 'organizations',
                'key'     => 'organization_id',
                'display' => 'name',
            ],
        ],

        /* ------- إحصائيات ------- */
        'stats' => [
            'active' => [
                'type' => 'count_status', 'value' => 'active',
                'label' => 'نشط', 'icon' => 'fas fa-user-check', 'color' => 'emerald',
            ],
            'blocked' => [
                'type' => 'count_status', 'value' => 'blocked',
                'label' => 'محظور', 'icon' => 'fas fa-user-slash', 'color' => 'cherry',
            ],
            'agents' => [
                'type' => 'sum_relation', 'relation' => 'agents',
                'label' => 'الوكلاء', 'icon' => 'fas fa-user-tie', 'color' => 'sky',
            ],
            'agencies' => [
                'type' => 'unique', 'field' => 'organization_id',
                'label' => 'المؤسسات', 'icon' => 'fas fa-building', 'color' => 'gold',
            ],
        ],

        /* ------- العلاقات ------- */
        'relations' => [
            'agents' => [
                'type'             => 'hasMany',
                'table'            => 'owner_agents',
                'primary_key'      => 'agent_id',
                'foreign_key'      => 'owner_id',
                'has_soft_deletes' => false,
                'status_column'    => 'is_active',
                'enabled'          => true,
                'fields' => [
                    ['key' => 'full_name', 'label' => 'اسم الوكيل',    'type' => 'text',   'icon' => 'fas fa-user',            'required' => true],
                    ['key' => 'phone',     'label' => 'رقم الجوال',    'type' => 'tel',    'icon' => 'fas fa-mobile-screen'],
                    ['key' => 'email',     'label' => 'البريد',        'type' => 'email',  'icon' => 'fas fa-envelope'],
                    [
                        'key' => 'role', 'label' => 'دور الوكيل', 'type' => 'select',
                        'icon' => 'fas fa-briefcase', 'default' => 'administrative',
                        'options' => [
                            ['value' => 'administrative',   'label' => 'وكيل إداري'],
                            ['value' => 'legal',            'label' => 'وكيل قانوني'],
                            ['value' => 'property_manager', 'label' => 'مدير عقارات'],
                        ],
                    ],
                    ['key' => 'power_of_attorney_file', 'label' => 'ملف التوكيل',  'type' => 'text', 'icon' => 'fas fa-file-contract', 'full' => true],
                    ['key' => 'authority_start_date',   'label' => 'بداية التفويض', 'type' => 'date', 'icon' => 'fas fa-calendar-plus'],
                    ['key' => 'authority_end_date',     'label' => 'نهاية التفويض', 'type' => 'date', 'icon' => 'fas fa-calendar-check'],
                    ['key' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'full' => true],
                ],
            ],
        ],

        /* ------- الحقول الرئيسية ------- */
        'main_fields' => [
            ['key' => 'full_name', 'label' => 'الاسم الكامل',     'type' => 'text',  'icon' => 'fas fa-user',           'required' => true],
            ['key' => 'phone',     'label' => 'رقم الجوال',       'type' => 'tel',   'icon' => 'fas fa-mobile-screen',  'required' => true],
            ['key' => 'phone2',    'label' => 'جوال ثانوي',       'type' => 'tel',   'icon' => 'fas fa-mobile-screen-button'],
            ['key' => 'email',     'label' => 'البريد الإلكتروني', 'type' => 'email', 'icon' => 'fas fa-envelope'],
            [
                'key' => 'id_type', 'label' => 'نوع الهوية', 'type' => 'select',
                'icon' => 'fas fa-id-card', 'default' => 'national_id',
                'options' => [
                    ['value' => 'national_id',    'label' => 'هوية وطنية'],
                    ['value' => 'iqama',          'label' => 'إقامة'],
                    ['value' => 'passport',       'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],
            ['key' => 'id_number',   'label' => 'رقم الهوية',    'type' => 'text', 'icon' => 'fas fa-hashtag'],
            ['key' => 'nationality', 'label' => 'الجنسية',       'type' => 'text', 'icon' => 'fas fa-globe', 'default' => 'سعودي'],
            ['key' => 'address',     'label' => 'العنوان',       'type' => 'text', 'icon' => 'fas fa-location-dot', 'full' => true],
            ['key' => 'bank_name',           'label' => 'اسم البنك',       'type' => 'text', 'icon' => 'fas fa-building-columns'],
            ['key' => 'bank_account_name',   'label' => 'اسم صاحب الحساب', 'type' => 'text', 'icon' => 'fas fa-user-check'],
            ['key' => 'bank_account_number', 'label' => 'رقم الحساب',      'type' => 'text', 'icon' => 'fas fa-credit-card'],
            ['key' => 'bank_iban',           'label' => 'رقم الآيبان',     'type' => 'text', 'icon' => 'fas fa-barcode'],
            [
                'key' => 'preferred_payment_method', 'label' => 'طريقة الدفع', 'type' => 'select',
                'icon' => 'fas fa-wallet', 'default' => 'bank_transfer',
                'options' => [
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque',        'label' => 'شيك'],
                    ['value' => 'cash',          'label' => 'نقداً'],
                ],
            ],
            [
                'key' => 'status', 'label' => 'الحالة', 'type' => 'select',
                'icon' => 'fas fa-toggle-on', 'default' => 'active', 'is_status' => true,
                'options' => [
                    ['value' => 'active',   'label' => 'نشط'],
                    ['value' => 'inactive', 'label' => 'غير نشط'],
                    ['value' => 'blocked',  'label' => 'محظور'],
                ],
            ],
            ['key' => 'notes', 'label' => 'ملاحظات', 'type' => 'textarea', 'full' => true],
        ],

        /* ------- الفلاتر ------- */
        'filters' => [
            [
                'key' => 'status', 'label' => 'الحالة', 'icon' => 'fas fa-toggle-on', 'type' => 'select',
                'options' => [
                    ['value' => 'all',      'label' => 'الكل'],
                    ['value' => 'active',   'label' => 'نشط'],
                    ['value' => 'inactive', 'label' => 'غير نشط'],
                    ['value' => 'blocked',  'label' => 'محظور'],
                ],
            ],
            [
                'key' => 'id_type', 'label' => 'نوع الهوية', 'icon' => 'fas fa-id-card', 'type' => 'select',
                'options' => [
                    ['value' => 'all',            'label' => 'الكل'],
                    ['value' => 'national_id',    'label' => 'هوية وطنية'],
                    ['value' => 'iqama',          'label' => 'إقامة'],
                    ['value' => 'passport',       'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],
            [
                'key' => 'organization_id', 'label' => 'المؤسسة', 'icon' => 'fas fa-building', 'type' => 'select',
                'options_source' => 'organization_id',
            ],
            [
                'key' => 'nationality', 'label' => 'الجنسية', 'icon' => 'fas fa-globe', 'type' => 'select',
                'options' => [
                    ['value' => 'all',   'label' => 'الكل'],
                    ['value' => 'سعودي', 'label' => 'سعودي'],
                    ['value' => 'يمني',  'label' => 'يمني'],
                    ['value' => 'مقيم',  'label' => 'مقيم'],
                ],
            ],
            [
                'key' => 'dateFrom', 'label' => 'من تاريخ', 'icon' => 'fas fa-calendar-plus', 'type' => 'date',
                'column' => 'created_at',
            ],
            [
                'key' => 'dateTo', 'label' => 'إلى تاريخ', 'icon' => 'fas fa-calendar-check', 'type' => 'date',
                'column' => 'created_at',
            ],
            [
                'key' => 'preferred_payment_method', 'label' => 'طريقة الدفع', 'icon' => 'fas fa-wallet', 'type' => 'select',
                'options' => [
                    ['value' => 'all',           'label' => 'الكل'],
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque',        'label' => 'شيك'],
                    ['value' => 'cash',          'label' => 'نقداً'],
                ],
            ],
            [
                'key' => 'hasSub', 'label' => 'الوكلاء', 'icon' => 'fas fa-user-tie', 'type' => 'relation_exists', 'relation' => 'agents',
                'options' => [
                    ['value' => 'all',  'label' => 'الكل'],
                    ['value' => 'has',  'label' => 'لديه وكلاء'],
                    ['value' => 'none', 'label' => 'بدون وكلاء'],
                ],
            ],
            [
                'key' => 'sort', 'label' => 'الترتيب', 'icon' => 'fas fa-sort', 'type' => 'sort',
                'options' => [
                    ['value' => 'newest',    'label' => 'الأحدث أولاً'],
                    ['value' => 'oldest',    'label' => 'الأقدم أولاً'],
                    ['value' => 'name_asc',  'label' => 'الاسم (أ-ي)'],
                    ['value' => 'name_desc', 'label' => 'الاسم (ي-أ)'],
                ],
            ],
        ],

        /* ------- خريطة العرض ------- */
        'ui' => [
            'list' => [
                'avatar_field'   => 'full_name',
                'title_field'    => 'full_name',
                'subtitle_field' => 'phone',
                'subtitle_icon'  => 'fas fa-mobile-screen',
                'status_field'   => 'status',
            ],
            'details' => [
                'avatar_field' => 'full_name',
                'title_field'  => 'full_name',
                'status_field' => 'status',
                'header_fields' => [
                    ['field' => 'id_number',       'label' => 'رقم الهوية',    'icon' => 'fas fa-hashtag'],
                    ['field' => 'organization_id', 'label' => 'المؤسسة',       'icon' => 'fas fa-building'],
                    ['field' => 'created_at',      'label' => 'تاريخ الإنشاء', 'icon' => 'fas fa-calendar'],
                ],
                'info_fields' => [
                    ['field' => 'phone',                      'label' => 'رقم الجوال',    'icon' => 'fas fa-mobile-screen',    'color' => 'blue'],
                    ['field' => 'email',                      'label' => 'البريد',        'icon' => 'fas fa-envelope',         'color' => 'gold'],
                    ['field' => 'nationality',                'label' => 'الجنسية',       'icon' => 'fas fa-globe',            'color' => 'green'],
                    ['field' => 'id_type',                    'label' => 'نوع الهوية',    'icon' => 'fas fa-id-card',          'color' => 'orange', 'type' => 'select'],
                    ['field' => 'address',                    'label' => 'العنوان',       'icon' => 'fas fa-location-dot',     'color' => 'purple'],
                    ['field' => 'bank_name',                  'label' => 'البنك',         'icon' => 'fas fa-building-columns', 'color' => 'sky'],
                    ['field' => 'bank_iban',                  'label' => 'الآيبان',       'icon' => 'fas fa-credit-card',      'color' => 'blue'],
                    ['field' => 'preferred_payment_method',   'label' => 'طريقة الدفع',   'icon' => 'fas fa-wallet',           'color' => 'green', 'type' => 'select'],
                ],
                'notes_field' => 'notes',
            ],
            'relation' => [
                'title_field'    => 'full_name',
                'subtitle_field' => 'phone',
                'type_field'     => 'role',
                'status_field'   => 'status',
                'status_icon'    => 'fas fa-mobile-screen',
            ],
        ],

        'sub_fields' => [], // مرجع اختياري
    ],

];