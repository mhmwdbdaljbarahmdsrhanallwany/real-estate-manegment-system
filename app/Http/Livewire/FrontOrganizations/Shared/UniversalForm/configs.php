<?php

return [

   
    // | الإعدادات الافتراضية — تُدمج مع أي كيان
    

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
            'list' => true,
            'details' => true,
            'statistics' => true,
            'search' => true,
            'filters' => true,
            'pagination' => true,
        ],

        'actions' => [
            'create' => true,
            'edit' => true,
            'delete' => true,
            'view'   => true,
            'status' => true,
        ],

        'search' => [
            'enabled' => true,
            'fields'  => ['full_name', 'phone', 'phone2', 'email', 'id_number'],
        ],

        'pagination' => [
            'enabled' => true,
            'per_page' => 10,
            'options' => [10, 25, 50, 100],
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
                'type' => 'count_status',
                'value' => 'active',
                'label' => 'نشط',
                'icon' => 'fas fa-user-check',
                'color' => 'emerald',
            ],
            'blocked' => [
                'type' => 'count_status',
                'value' => 'blocked',
                'label' => 'محظور',
                'icon' => 'fas fa-user-slash',
                'color' => 'cherry',
            ],
            'agents' => [
                'type' => 'sum_relation',
                'relation' => 'agents',
                'label' => 'الوكلاء',
                'icon' => 'fas fa-user-tie',
                'color' => 'sky',
            ],
            'agencies' => [
                'type' => 'unique',
                'field' => 'organization_id',
                'label' => 'المؤسسات',
                'icon' => 'fas fa-building',
                'color' => 'gold',
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
                        'key' => 'role',
                        'label' => 'دور الوكيل',
                        'type' => 'select',
                        'icon' => 'fas fa-briefcase',
                        'default' => 'administrative',
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
                'key' => 'id_type',
                'label' => 'نوع الهوية',
                'type' => 'select',
                'icon' => 'fas fa-id-card',
                'default' => 'national_id',
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
                'key' => 'preferred_payment_method',
                'label' => 'طريقة الدفع',
                'type' => 'select',
                'icon' => 'fas fa-wallet',
                'default' => 'bank_transfer',
                'options' => [
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque',        'label' => 'شيك'],
                    ['value' => 'cash',          'label' => 'نقداً'],
                ],
            ],
            [
                'key' => 'status',
                'label' => 'الحالة',
                'type' => 'select',
                'icon' => 'fas fa-toggle-on',
                'default' => 'active',
                'is_status' => true,
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
                'key' => 'status',
                'label' => 'الحالة',
                'icon' => 'fas fa-toggle-on',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',      'label' => 'الكل'],
                    ['value' => 'active',   'label' => 'نشط'],
                    ['value' => 'inactive', 'label' => 'غير نشط'],
                    ['value' => 'blocked',  'label' => 'محظور'],
                ],
            ],
            [
                'key' => 'id_type',
                'label' => 'نوع الهوية',
                'icon' => 'fas fa-id-card',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',            'label' => 'الكل'],
                    ['value' => 'national_id',    'label' => 'هوية وطنية'],
                    ['value' => 'iqama',          'label' => 'إقامة'],
                    ['value' => 'passport',       'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],
            [
                'key' => 'organization_id',
                'label' => 'المؤسسة',
                'icon' => 'fas fa-building',
                'type' => 'select',
                'options_source' => 'organization_id',
            ],
            [
                'key' => 'nationality',
                'label' => 'الجنسية',
                'icon' => 'fas fa-globe',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',   'label' => 'الكل'],
                    ['value' => 'سعودي', 'label' => 'سعودي'],
                    ['value' => 'يمني',  'label' => 'يمني'],
                    ['value' => 'مقيم',  'label' => 'مقيم'],
                ],
            ],
            [
                'key' => 'dateFrom',
                'label' => 'من تاريخ',
                'icon' => 'fas fa-calendar-plus',
                'type' => 'date',
                'column' => 'created_at',
            ],
            [
                'key' => 'dateTo',
                'label' => 'إلى تاريخ',
                'icon' => 'fas fa-calendar-check',
                'type' => 'date',
                'column' => 'created_at',
            ],
            [
                'key' => 'preferred_payment_method',
                'label' => 'طريقة الدفع',
                'icon' => 'fas fa-wallet',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',           'label' => 'الكل'],
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque',        'label' => 'شيك'],
                    ['value' => 'cash',          'label' => 'نقداً'],
                ],
            ],
            [
                'key' => 'hasSub',
                'label' => 'الوكلاء',
                'icon' => 'fas fa-user-tie',
                'type' => 'relation_exists',
                'relation' => 'agents',
                'options' => [
                    ['value' => 'all',  'label' => 'الكل'],
                    ['value' => 'has',  'label' => 'لديه وكلاء'],
                    ['value' => 'none', 'label' => 'بدون وكلاء'],
                ],
            ],
            [
                'key' => 'sort',
                'label' => 'الترتيب',
                'icon' => 'fas fa-sort',
                'type' => 'sort',
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

    /*
|--------------------------------------------------------------------------
| كيان: وكلاء الملاك
|--------------------------------------------------------------------------
*/

    'owner_agents' => [

        // معلومات الجدول
        'table'             => 'owner_agents',
        'primary_key'       => 'agent_id',
        'name_field'        => 'full_name',
        'has_soft_deletes'  => false,
        'deleted_at_column' => 'deleted_at',

        // حماية بيانات المؤسسة عن طريق المالك
        'tenant_scoped' => true,
        'allow_shared'  => false,

        'tenant_scope' => [
            'type'                 => 'belongs_to',
            'table'                => 'owners',
            'local_key'            => 'owner_id',
            'owner_key'            => 'owner_id',
            'organization_column'  => 'organization_id',
            'parent_soft_deletes'  => true,
            'parent_deleted_column' => 'deleted_at',
        ],

        // معلومات الواجهة
        'title'              => 'إدارة وكلاء الملاك',
        'subtitle'           => 'عرض وإضافة وتعديل وإدارة وكلاء الملاك',
        'icon'               => 'fas fa-user-tie',
        'entity_name'        => 'وكلاء الملاك',
        'entity_singular'    => 'وكيل',
        'entity_sub'         => 'وكيل',
        'btn_add_main'       => 'إضافة وكيل',
        'btn_add_sub'        => 'إضافة عنصر',
        'sub_section_title'  => 'بيانات الوكيل',
        'sub_section_icon'   => 'fas fa-user-tie',

        /*
    |--------------------------------------------------------------------------
    | حالة الوكيل
    |--------------------------------------------------------------------------
    */

        'status' => [
            'column'      => 'is_active',
            'boolean_map' => [
                '1' => 'active',
                '0' => 'inactive',
            ],
            'values' => [
                'active',
                'inactive',
            ],
            'labels' => [
                'active'   => 'نشط',
                'inactive' => 'غير نشط',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | إظهار الأقسام
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
    | العمليات المسموحة
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
            'fields'  => [
                'full_name',
                'phone',
                'email',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | الترقيم
    |--------------------------------------------------------------------------
    */

        'pagination' => [
            'enabled'  => true,
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        /*
    |--------------------------------------------------------------------------
    | جداول مساعدة لتحويل المفاتيح إلى أسماء
    |--------------------------------------------------------------------------
    */

        'lookups' => [
            'owner_id' => [
                'table'                => 'owners',
                'key'                  => 'owner_id',
                'display'              => 'full_name',
                'organization_column'  => 'organization_id',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | الإحصائيات
    |--------------------------------------------------------------------------
    */

        'stats' => [

            'total' => [
                'type'  => 'count',
                'label' => 'إجمالي الوكلاء',
                'icon'  => 'fas fa-users',
                'color' => 'gold',
            ],

            'active' => [
                'type'  => 'count_status',
                'value' => 'active',
                'label' => 'الوكلاء النشطون',
                'icon'  => 'fas fa-user-check',
                'color' => 'emerald',
            ],

            'inactive' => [
                'type'  => 'count_status',
                'value' => 'inactive',
                'label' => 'الوكلاء غير النشطين',
                'icon'  => 'fas fa-user-clock',
                'color' => 'cherry',
            ],

            'legal' => [
                'type'  => 'count',
                'field' => 'role',
                'value' => 'legal',
                'label' => 'الوكلاء القانونيون',
                'icon'  => 'fas fa-scale-balanced',
                'color' => 'purple',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | الحقول الرئيسية
    |--------------------------------------------------------------------------
    */

        'main_fields' => [

            [
                'key'      => 'owner_id',
                'label'    => 'المالك',
                'type'     => 'select',
                'icon'     => 'fas fa-user',
                'required' => true,
                'options_source' => 'owner_id',
            ],

            [
                'key'      => 'full_name',
                'label'    => 'اسم الوكيل',
                'type'     => 'text',
                'icon'     => 'fas fa-user-tie',
                'required' => true,
            ],

            [
                'key'   => 'phone',
                'label' => 'رقم الجوال',
                'type'  => 'tel',
                'icon'  => 'fas fa-mobile-screen',
            ],

            [
                'key'   => 'email',
                'label' => 'البريد الإلكتروني',
                'type'  => 'email',
                'icon'  => 'fas fa-envelope',
            ],

            [
                'key'     => 'role',
                'label'   => 'صفة الوكيل',
                'type'    => 'select',
                'icon'    => 'fas fa-briefcase',
                'default' => 'administrative',

                'options' => [
                    [
                        'value' => 'administrative',
                        'label' => 'وكيل إداري',
                    ],
                    [
                        'value' => 'legal',
                        'label' => 'وكيل قانوني',
                    ],
                    [
                        'value' => 'property_manager',
                        'label' => 'مدير عقارات',
                    ],
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
                'key'       => 'is_active',
                'label'     => 'الحالة',
                'type'      => 'select',
                'icon'      => 'fas fa-toggle-on',
                'default'   => 1,
                'is_status' => true,

                'options' => [
                    [
                        'value' => 1,
                        'label' => 'نشط',
                    ],
                    [
                        'value' => 0,
                        'label' => 'غير نشط',
                    ],
                ],
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'icon'  => 'fas fa-note-sticky',
                'full'  => true,
            ],
        ],

        /* ------- الفلاتر ------- */
        'filters' => [
            [
                'key' => 'status',
                'label' => 'الحالة',
                'icon' => 'fas fa-toggle-on',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',      'label' => 'الكل'],
                    ['value' => 'active',   'label' => 'نشط'],
                    ['value' => 'inactive', 'label' => 'غير نشط'],
                    ['value' => 'blocked',  'label' => 'محظور'],
                ],
            ],
            [
                'key' => 'id_type',
                'label' => 'نوع الهوية',
                'icon' => 'fas fa-id-card',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',            'label' => 'الكل'],
                    ['value' => 'national_id',    'label' => 'هوية وطنية'],
                    ['value' => 'iqama',          'label' => 'إقامة'],
                    ['value' => 'passport',       'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],
            [
                'key' => 'organization_id',
                'label' => 'المؤسسة',
                'icon' => 'fas fa-building',
                'type' => 'select',
                'options_source' => 'organization_id',
            ],
            [
                'key' => 'nationality',
                'label' => 'الجنسية',
                'icon' => 'fas fa-globe',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',   'label' => 'الكل'],
                    ['value' => 'سعودي', 'label' => 'سعودي'],
                    ['value' => 'يمني',  'label' => 'يمني'],
                    ['value' => 'مقيم',  'label' => 'مقيم'],
                ],
            ],
            [
                'key' => 'dateFrom',
                'label' => 'من تاريخ',
                'icon' => 'fas fa-calendar-plus',
                'type' => 'date',
                'column' => 'created_at',
            ],
            [
                'key' => 'dateTo',
                'label' => 'إلى تاريخ',
                'icon' => 'fas fa-calendar-check',
                'type' => 'date',
                'column' => 'created_at',
            ],
            [
                'key' => 'preferred_payment_method',
                'label' => 'طريقة الدفع',
                'icon' => 'fas fa-wallet',
                'type' => 'select',
                'options' => [
                    ['value' => 'all',           'label' => 'الكل'],
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque',        'label' => 'شيك'],
                    ['value' => 'cash',          'label' => 'نقداً'],
                ],
            ],
            [
                'key' => 'hasSub',
                'label' => 'الوكلاء',
                'icon' => 'fas fa-user-tie',
                'type' => 'relation_exists',
                'relation' => 'agents',
                'options' => [
                    ['value' => 'all',  'label' => 'الكل'],
                    ['value' => 'has',  'label' => 'لديه وكلاء'],
                    ['value' => 'none', 'label' => 'بدون وكلاء'],
                ],
            ],
            [
                'key' => 'sort',
                'label' => 'الترتيب',
                'icon' => 'fas fa-sort',
                'type' => 'sort',
                'options' => [
                    ['value' => 'newest',    'label' => 'الأحدث أولاً'],
                    ['value' => 'oldest',    'label' => 'الأقدم أولاً'],
                    ['value' => 'name_asc',  'label' => 'الاسم (أ-ي)'],
                    ['value' => 'name_desc', 'label' => 'الاسم (ي-أ)'],
                ],
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | خريطة العرض
    |--------------------------------------------------------------------------
    */

        'ui' => [

            'list' => [
                'avatar_field'   => 'full_name',
                'title_field'    => 'full_name',
                'subtitle_field' => 'phone',
                'subtitle_icon'  => 'fas fa-mobile-screen',
                'status_field'   => 'is_active',
            ],

            'details' => [

                'avatar_field' => 'full_name',
                'title_field'  => 'full_name',
                'status_field' => 'is_active',

                'header_fields' => [
                    [
                        'field' => 'owner_id',
                        'label' => 'المالك',
                        'icon'  => 'fas fa-user',
                    ],
                    [
                        'field' => 'role',
                        'label' => 'صفة الوكيل',
                        'icon'  => 'fas fa-briefcase',
                    ],
                    [
                        'field' => 'created_at',
                        'label' => 'تاريخ الإنشاء',
                        'icon'  => 'fas fa-calendar',
                    ],
                ],

                'info_fields' => [
                    [
                        'field' => 'phone',
                        'label' => 'رقم الجوال',
                        'icon'  => 'fas fa-mobile-screen',
                        'color' => 'blue',
                    ],
                    [
                        'field' => 'email',
                        'label' => 'البريد الإلكتروني',
                        'icon'  => 'fas fa-envelope',
                        'color' => 'gold',
                    ],
                    [
                        'field' => 'role',
                        'label' => 'صفة الوكيل',
                        'icon'  => 'fas fa-briefcase',
                        'color' => 'purple',
                        'type'  => 'select',
                    ],
                    [
                        'field' => 'authority_start_date',
                        'label' => 'بداية التفويض',
                        'icon'  => 'fas fa-calendar-plus',
                        'color' => 'green',
                    ],
                    [
                        'field' => 'authority_end_date',
                        'label' => 'نهاية التفويض',
                        'icon'  => 'fas fa-calendar-check',
                        'color' => 'orange',
                    ],
                ],

                'notes_field' => 'notes',
            ],

            'relation' => [
                'title_field'    => 'full_name',
                'subtitle_field' => 'phone',
                'type_field'     => 'role',
                'status_field'   => 'is_active',
                'status_icon'    => 'fas fa-user-tie',
            ],
        ],

        // لا توجد علاقات فرعية للوكلاء حالياً
        'relations'  => [],
        'sub_fields' => [],
    ],





'buildings' => [

    // معلومات الجدول الأساسية
    'table'               => 'buildings',
    'primary_key'         => 'building_id',
    'organization_column' => 'organization_id',
    'name_field'          => 'building_name',
    'has_soft_deletes'    => true,
    'deleted_at_column'   => 'deleted_at',

    // حماية بيانات المؤسسة
    'tenant_scoped' => true,
    'allow_shared'  => false,

    // معلومات الواجهة
    'title'             => 'إدارة المباني',
    'subtitle'          => 'إدارة المباني والعمارات والمواقع والمواصفات العقارية',
    'icon'              => 'fas fa-building',
    'entity_name'       => 'المباني والعقارات',
    'entity_singular'   => 'مبنى',
    'btn_add_main'      => 'إضافة مبنى',

    // حالة المبنى
    'status' => [
        'column'      => 'is_active',
        'boolean_map' => [
            'true'  => 'active',
            'false' => 'inactive',
        ],
        'values' => [
            'active',
            'inactive',
        ],
        'labels' => [
            'active'   => 'نشط',
            'inactive' => 'غير نشط',
        ],
    ],

    // إعدادات العرض
    'display' => [
        'list'       => true,
        'details'    => true,
        'statistics' => true,
        'search'     => true,
        'filters'    => true,
        'pagination' => true,
    ],

    // الإجراءات
    'actions' => [
        'create' => true,
        'edit'   => true,
        'delete' => true,
        'view'   => true,
        'status' => true,
    ],

    // البحث
    'search' => [
        'enabled' => true,
        'fields' => [
            'building_name',
            'building_number',
            'city',
            'district',
            'street',
            'postal_code',
        ],
    ],

    // الترقيم
    'pagination' => [
        'enabled'  => true,
        'per_page' => 10,
        'options'  => [10, 25, 50, 100],
    ],

    // القوائم المرجعية
    'lookups' => [

        'owner_id' => [
            'table'   => 'owners',
            'key'     => 'owner_id',
            'display' => 'full_name',
        ],

        'organization_id' => [
            'table'   => 'organizations',
            'key'     => 'organization_id',
            'display' => 'name',
        ],

        'branch_id' => [
            'table'   => 'agency_branches',
            'key'     => 'branch_id',
            'display' => 'branch_name',
        ],
    ],

    // الإحصائيات
    'stats' => [

        'active' => [
            'type'  => 'count',
            'field' => 'is_active',
            'value' => 1,
            'label' => 'المباني النشطة',
            'icon'  => 'fas fa-building-circle-check',
            'color' => 'emerald',
        ],

        'inactive' => [
            'type'  => 'count',
            'field' => 'is_active',
            'value' => 0,
            'label' => 'المباني غير النشطة',
            'icon'  => 'fas fa-building-circle-exclamation',
            'color' => 'cherry',
        ],

        'residential' => [
            'type'  => 'count',
            'field' => 'property_type',
            'value' => 'residential',
            'label' => 'المباني السكنية',
            'icon'  => 'fas fa-house',
            'color' => 'sky',
        ],

        'commercial' => [
            'type'  => 'count',
            'field' => 'property_type',
            'value' => 'commercial',
            'label' => 'المباني التجارية',
            'icon'  => 'fas fa-store',
            'color' => 'gold',
        ],
    ],

    // العلاقات
    'relations' => [

        'owner' => [
            'type'        => 'belongsTo',
            'table'       => 'owners',
            'primary_key' => 'owner_id',
            'foreign_key' => 'owner_id',
            'name_field'  => 'full_name',
            'enabled'     => true,
            'fields'      => [
                'owner_id',
                'full_name',
                'phone',
            ],
            'label' => 'المالك',
        ],

        'organization' => [
            'type'        => 'belongsTo',
            'table'       => 'organizations',
            'primary_key' => 'organization_id',
            'foreign_key' => 'organization_id',
            'name_field'  => 'name',
            'enabled'     => true,
            'fields'      => [
                'organization_id',
                'name',
            ],
            'label' => 'المؤسسة',
        ],

        'branch' => [
            'type'        => 'belongsTo',
            'table'       => 'agency_branches',
            'primary_key' => 'branch_id',
            'foreign_key' => 'branch_id',
            'name_field'  => 'branch_name',
            'enabled'     => true,
            'fields'      => [
                'branch_id',
                'branch_name',
            ],
            'label' => 'الفرع',
        ],
    ],

    // الحقول الرئيسية
    'main_fields' => [

        [
            'key'      => 'building_name',
            'label'    => 'اسم المبنى',
            'type'     => 'text',
            'icon'     => 'fas fa-building',
            'required' => true,
        ],

        [
            'key'   => 'building_number',
            'label' => 'رقم المبنى الوطني',
            'type'  => 'text',
            'icon'  => 'fas fa-hashtag',
        ],

        [
            'key'      => 'owner_id',
            'label'    => 'مالك المبنى',
            'type'     => 'select',
            'lookup'   => 'owner_id',
            'icon'     => 'fas fa-user',
            'required' => true,
        ],

        [
            'key'    => 'organization_id',
            'label'  => 'المؤسسة المسؤولة',
            'type'   => 'select',
            'lookup' => 'organization_id',
            'icon'   => 'fas fa-sitemap',
        ],

        [
            'key'    => 'branch_id',
            'label'  => 'الفرع المسؤول',
            'type'   => 'select',
            'lookup' => 'branch_id',
            'icon'   => 'fas fa-code-branch',
        ],

        [
            'key'   => 'address_line1',
            'label' => 'العنوان الرئيسي',
            'type'  => 'text',
            'icon'  => 'fas fa-map-marker-alt',
        ],

        [
            'key'   => 'address_line2',
            'label' => 'العنوان الإضافي',
            'type'  => 'text',
            'icon'  => 'fas fa-location-arrow',
        ],

        [
            'key'   => 'city',
            'label' => 'المدينة',
            'type'  => 'text',
            'icon'  => 'fas fa-city',
        ],

        [
            'key'   => 'district',
            'label' => 'الحي',
            'type'  => 'text',
            'icon'  => 'fas fa-map',
        ],

        [
            'key'   => 'street',
            'label' => 'الشارع',
            'type'  => 'text',
            'icon'  => 'fas fa-road',
        ],

        [
            'key'   => 'postal_code',
            'label' => 'الرمز البريدي',
            'type'  => 'text',
            'icon'  => 'fas fa-mail-bulk',
        ],

        [
            'key'     => 'country',
            'label'   => 'الدولة',
            'type'    => 'text',
            'default' => 'السعودية',
            'icon'    => 'fas fa-globe',
        ],

        [
            'key'   => 'latitude',
            'label' => 'خط العرض',
            'type'  => 'number',
            'icon'  => 'fas fa-map-pin',
        ],

        [
            'key'   => 'longitude',
            'label' => 'خط الطول',
            'type'  => 'number',
            'icon'  => 'fas fa-map-pin',
        ],

        [
            'key'   => 'number_of_floors',
            'label' => 'عدد الأدوار',
            'type'  => 'number',
            'icon'  => 'fas fa-layer-group',
        ],

        [
            'key'   => 'number_of_units',
            'label' => 'عدد الوحدات',
            'type'  => 'number',
            'icon'  => 'fas fa-door-open',
        ],

        [
            'key'   => 'year_built',
            'label' => 'سنة البناء',
            'type'  => 'number',
            'icon'  => 'fas fa-calendar',
        ],

        [
            'key'   => 'land_area',
            'label' => 'مساحة الأرض (م²)',
            'type'  => 'number',
            'icon'  => 'fas fa-ruler-combined',
        ],

        [
            'key'   => 'building_area',
            'label' => 'مساحة المبنى (م²)',
            'type'  => 'number',
            'icon'  => 'fas fa-vector-square',
        ],

        [
            'key'     => 'property_type',
            'label'   => 'نوع العقار',
            'type'    => 'select',
            'icon'    => 'fas fa-house',
            'default' => 'residential',
            'options' => [
                ['value' => 'residential',  'label' => 'سكني'],
                ['value' => 'commercial',   'label' => 'تجاري'],
                ['value' => 'industrial',   'label' => 'صناعي'],
                ['value' => 'agricultural', 'label' => 'زراعي'],
                ['value' => 'mixed',        'label' => 'متعدد الاستخدامات'],
            ],
        ],

        [
            'key'     => 'has_elevator',
            'label'   => 'يوجد مصعد',
            'type'    => 'checkbox',
            'default' => false,
            'icon'    => 'fas fa-elevator',
        ],

        [
            'key'     => 'has_parking',
            'label'   => 'يوجد موقف سيارات',
            'type'    => 'checkbox',
            'default' => false,
            'icon'    => 'fas fa-square-parking',
        ],

        [
            'key'     => 'has_security',
            'label'   => 'توجد حراسة',
            'type'    => 'checkbox',
            'default' => false,
            'icon'    => 'fas fa-shield-alt',
        ],

        [
            'key'     => 'has_maintenance',
            'label'   => 'توجد صيانة',
            'type'    => 'checkbox',
            'default' => false,
            'icon'    => 'fas fa-tools',
        ],

        [
            'key'   => 'notes',
            'label' => 'ملاحظات',
            'type'  => 'textarea',
            'icon'  => 'fas fa-sticky-note',
            'full'  => true,
        ],

        [
            'key'   => 'images',
            'label' => 'صور المبنى',
            'type'  => 'file',
            'icon'  => 'fas fa-images',
        ],

        [
            'key'   => 'documents',
            'label' => 'مستندات المبنى',
            'type'  => 'file',
            'icon'  => 'fas fa-file-alt',
        ],

        [
            'key'       => 'is_active',
            'label'     => 'حالة المبنى',
            'type'      => 'select',
            'default'   => 1,
            'is_status' => true,
            'options'   => [
                ['value' => 1, 'label' => 'نشط'],
                ['value' => 0, 'label' => 'غير نشط'],
            ],
        ],
    ],

    // الفلاتر
    'filters' => [

        [
            'key'     => 'status',
            'label'   => 'الحالة',
            'type'    => 'select',
            'column'  => 'is_active',
            'options' => [
                ['value' => 'all', 'label' => 'الكل'],
                ['value' => '1',   'label' => 'نشط'],
                ['value' => '0',   'label' => 'غير نشط'],
            ],
        ],

        [
            'key'     => 'property_type',
            'label'   => 'نوع العقار',
            'type'    => 'select',
            'column'  => 'property_type',
            'options' => [
                ['value' => 'all',          'label' => 'الكل'],
                ['value' => 'residential',  'label' => 'سكني'],
                ['value' => 'commercial',   'label' => 'تجاري'],
                ['value' => 'industrial',   'label' => 'صناعي'],
                ['value' => 'agricultural', 'label' => 'زراعي'],
                ['value' => 'mixed',        'label' => 'متعدد الاستخدامات'],
            ],
        ],

        [
            'key'    => 'city',
            'label'  => 'المدينة',
            'type'   => 'text',
            'column' => 'city',
        ],

        [
            'key'    => 'district',
            'label'  => 'الحي',
            'type'   => 'text',
            'column' => 'district',
        ],

        [
            'key'    => 'owner_id',
            'label'  => 'المالك',
            'type'   => 'select',
            'column' => 'owner_id',
            'lookup' => 'owner_id',
        ],

        [
            'key'    => 'branch_id',
            'label'  => 'الفرع',
            'type'   => 'select',
            'column' => 'branch_id',
            'lookup' => 'branch_id',
        ],
    ],

    // خريطة العرض
    'ui' => [

        'list' => [
            'avatar_field'   => 'building_name',
            'title_field'    => 'building_name',
            'subtitle_field' => 'building_number',
            'subtitle_icon'  => 'fas fa-hashtag',
            'status_field'   => 'is_active',
        ],

        'details' => [
            'avatar_field' => 'building_name',
            'title_field'  => 'building_name',
            'status_field' => 'is_active',

            'header_fields' => [
                [
                    'field' => 'building_number',
                    'label' => 'رقم المبنى',
                    'icon'  => 'fas fa-hashtag',
                ],
                [
                    'field' => 'city',
                    'label' => 'المدينة',
                    'icon'  => 'fas fa-city',
                ],
                [
                    'field' => 'created_at',
                    'label' => 'تاريخ الإنشاء',
                    'icon'  => 'fas fa-calendar',
                ],
            ],

            'info_fields' => [
                [
                    'field' => 'address_line1',
                    'label' => 'العنوان',
                    'icon'  => 'fas fa-map-marker-alt',
                ],
                [
                    'field' => 'owner_id',
                    'label' => 'المالك',
                    'icon'  => 'fas fa-user',
                ],
                [
                    'field' => 'organization_id',
                    'label' => 'المؤسسة',
                    'icon'  => 'fas fa-building',
                ],
                [
                    'field' => 'branch_id',
                    'label' => 'الفرع',
                    'icon'  => 'fas fa-code-branch',
                ],
                [
                    'field' => 'property_type',
                    'label' => 'نوع العقار',
                    'icon'  => 'fas fa-house',
                ],
                [
                    'field' => 'number_of_floors',
                    'label' => 'عدد الأدوار',
                    'icon'  => 'fas fa-layer-group',
                ],
                [
                    'field' => 'building_area',
                    'label' => 'مساحة المبنى',
                    'icon'  => 'fas fa-ruler-combined',
                ],
                [
                    'field' => 'year_built',
                    'label' => 'سنة البناء',
                    'icon'  => 'fas fa-calendar',
                ],
            ],

            'notes_field' => 'notes',
        ],
    ],

],


];
