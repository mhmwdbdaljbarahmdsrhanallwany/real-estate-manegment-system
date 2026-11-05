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

        /*
    |--------------------------------------------------------------------------
    | 1. معلومات الجدول الأساسية
    |--------------------------------------------------------------------------
    */
        'table'             => 'buildings',
        'primary_key'       => 'building_id',
        'name_field'        => 'building_name',
        'entity_name'       => 'المباني والعقارات',
        'entity_singular'   => 'مبنى',
        'has_soft_deletes'  => true,
        'deleted_at_column' => 'deleted_at',

        /*
    |--------------------------------------------------------------------------
    | 2. حماية البيانات وعزل المؤسسات والفروع
    |--------------------------------------------------------------------------
    */
        'tenant_scoped' => true,
        'allow_shared'  => false,

        // الجدول يحتوي على المؤسسة والفرع والمالك مباشرة.
        'tenant_scope' => [
            'type'                => 'direct',
            'organization_column' => 'organization_id',
            'branch_column'       => 'branch_id',
            'owner_column'        => 'owner_id',
            'require_organization' => true,
            'require_owner'        => true,
        ],

        /*
    |--------------------------------------------------------------------------
    | 3. العناوين والتعريفات
    |--------------------------------------------------------------------------
    */
        'title'             => 'إدارة المباني',
        'subtitle'          => 'إدارة المباني والعمارات والمواقع والمواصفات العقارية',
        'icon'              => 'fas fa-building',
        'singular_title'    => 'إضافة مبنى جديد',
        'edit_title'        => 'تعديل بيانات المبنى',
        'details_title'     => 'تفاصيل المبنى',
        'empty_title'       => 'لا توجد مبانٍ مسجلة',
        'empty_description' => 'ابدأ بإضافة المبنى الأول إلى سجلات المؤسسة',

        /*
    |--------------------------------------------------------------------------
    | 4. إعدادات العرض
    |--------------------------------------------------------------------------
    */
        'display' => [
            'title_column'       => 'building_name',
            'subtitle_column'    => 'building_number',
            'description_column' => 'address_line1',
            'image_column'       => 'images',
            'date_column'        => 'created_at',
            'show_id'            => true,
            'show_created_at'    => true,
            'show_updated_at'    => true,
        ],

        /*
    |--------------------------------------------------------------------------
    | 5. حالة المبنى
    |--------------------------------------------------------------------------
    */
        'status' => [
            'column' => 'is_active',
            'type'   => 'boolean',

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

            'colors' => [
                'active'   => 'success',
                'inactive' => 'secondary',
            ],

            'icons' => [
                'active'   => 'fas fa-check-circle',
                'inactive' => 'fas fa-pause-circle',
            ],

            'default' => 'active',
        ],

        /*
    |--------------------------------------------------------------------------
    | 6. حقول البحث
    |--------------------------------------------------------------------------
    */
        'search' => [
            'enabled' => true,
            'placeholder' => 'ابحث باسم المبنى أو رقمه أو المدينة أو الحي...',
            'fields' => [
                'building_name',
                'building_number',
                'city',
                'district',
                'street',
                'postal_code',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 7. إعدادات الترقيم
    |--------------------------------------------------------------------------
    */
        'pagination' => [
            'enabled' => true,
            'per_page' => 10,
            'options' => [10, 20, 50, 100],
            'default_sort' => 'building_id',
            'default_direction' => 'desc',
        ],

        /*
    |--------------------------------------------------------------------------
    | 8. القوائم المرجعية Lookups
    |--------------------------------------------------------------------------
    */
        'lookups' => [

            // المالكين
            'owners' => [
                'table'       => 'owners',
                'value'       => 'owner_id',
                'label'       => 'full_name',
                'key'         => 'owner_id',
                'label_field' => 'full_name',
                'where_null'  => 'deleted_at',
                'order_by'    => 'full_name',
                'placeholder' => 'اختر المالك',
            ],

            // المؤسسات
            'organizations' => [
                'table'       => 'organizations',
                'value'       => 'organization_id',
                'label'       => 'name',
                'key'         => 'organization_id',
                'label_field' => 'name',
                'placeholder' => 'اختر المؤسسة',
            ],

            // الفروع
            'branches' => [
                'table'       => 'agency_branches',
                'value'       => 'branch_id',
                'label'       => 'branch_name',
                'key'         => 'branch_id',
                'label_field' => 'branch_name',
                'placeholder' => 'اختر الفرع',
                'depends_on'  => 'organization_id',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 9. حقول النموذج الرئيسية
    |--------------------------------------------------------------------------
    */
        'main_fields' => [

            // هوية المبنى
            [
                'name'     => 'building_name',
                'label'    => 'اسم المبنى',
                'type'     => 'text',
                'section'  => 'basic',
                'required' => true,
                'rules'    => 'required|string|max:255',
                'placeholder' => 'أدخل اسم المبنى',
                'icon'     => 'fas fa-building',
                'column'   => 'col-md-6',
            ],

            [
                'name'    => 'building_number',
                'label'   => 'رقم المبنى الوطني',
                'type'    => 'text',
                'section' => 'basic',
                'required' => false,
                'rules'   => 'nullable|string|max:50',
                'placeholder' => 'رقم المبنى',
                'icon'    => 'fas fa-hashtag',
                'column'  => 'col-md-6',
            ],

            [
                'name'       => 'owner_id',
                'label'      => 'مالك المبنى',
                'type'       => 'select',
                'lookup'     => 'owners',
                'section'    => 'basic',
                'required'   => true,
                'rules'      => 'required|integer|exists:owners,owner_id',
                'placeholder' => 'اختر مالك المبنى',
                'icon'       => 'fas fa-user',
                'column'     => 'col-md-6',
            ],

            [
                'name'       => 'organization_id',
                'label'      => 'المؤسسة المسؤولة',
                'type'       => 'select',
                'lookup'     => 'organizations',
                'section'    => 'basic',
                'required'   => false,
                'rules'      => 'nullable|integer|exists:organizations,organization_id',
                'placeholder' => 'اختر المؤسسة',
                'icon'       => 'fas fa-sitemap',
                'column'     => 'col-md-6',
            ],

            [
                'name'       => 'branch_id',
                'label'      => 'الفرع المسؤول',
                'type'       => 'select',
                'lookup'     => 'branches',
                'section'    => 'basic',
                'required'   => false,
                'rules'      => 'nullable|integer|exists:agency_branches,branch_id',
                'placeholder' => 'اختر الفرع',
                'icon'       => 'fas fa-code-branch',
                'column'     => 'col-md-6',
            ],

            // العنوان الجغرافي
            [
                'name'     => 'address_line1',
                'label'    => 'العنوان الرئيسي',
                'type'     => 'text',
                'section'  => 'address',
                'rules'    => 'nullable|string|max:255',
                'placeholder' => 'أدخل العنوان الرئيسي',
                'icon'     => 'fas fa-map-marker-alt',
                'column'   => 'col-md-6',
            ],

            [
                'name'     => 'address_line2',
                'label'    => 'العنوان الإضافي',
                'type'     => 'text',
                'section'  => 'address',
                'rules'    => 'nullable|string|max:255',
                'placeholder' => 'العنوان الإضافي',
                'icon'     => 'fas fa-location-arrow',
                'column'   => 'col-md-6',
            ],

            [
                'name'     => 'city',
                'label'    => 'المدينة',
                'type'     => 'text',
                'section'  => 'address',
                'rules'    => 'nullable|string|max:100',
                'placeholder' => 'اسم المدينة',
                'icon'     => 'fas fa-city',
                'column'   => 'col-md-6',
            ],

            [
                'name'     => 'district',
                'label'    => 'الحي',
                'type'     => 'text',
                'section'  => 'address',
                'rules'    => 'nullable|string|max:100',
                'placeholder' => 'اسم الحي',
                'icon'     => 'fas fa-map',
                'column'   => 'col-md-6',
            ],

            [
                'name'     => 'street',
                'label'    => 'الشارع',
                'type'     => 'text',
                'section'  => 'address',
                'rules'    => 'nullable|string|max:100',
                'placeholder' => 'اسم الشارع',
                'icon'     => 'fas fa-road',
                'column'   => 'col-md-6',
            ],

            [
                'name'     => 'postal_code',
                'label'    => 'الرمز البريدي',
                'type'     => 'text',
                'section'  => 'address',
                'rules'    => 'nullable|string|max:20',
                'placeholder' => 'الرمز البريدي',
                'icon'     => 'fas fa-mail-bulk',
                'column'   => 'col-md-6',
            ],

            [
                'name'     => 'country',
                'label'    => 'الدولة',
                'type'     => 'text',
                'section'  => 'address',
                'default'  => 'السعودية',
                'rules'    => 'nullable|string|max:50',
                'icon'     => 'fas fa-globe',
                'column'   => 'col-md-6',
            ],

            // الإحداثيات
            [
                'name'     => 'latitude',
                'label'    => 'خط العرض Latitude',
                'type'     => 'number',
                'section'  => 'location',
                'rules'    => 'nullable|numeric|between:-90,90',
                'placeholder' => 'مثال: 24.7136000',
                'step'     => '0.0000001',
                'icon'     => 'fas fa-globe',
                'column'   => 'col-md-6',
            ],

            [
                'name'     => 'longitude',
                'label'    => 'خط الطول Longitude',
                'type'     => 'number',
                'section'  => 'location',
                'rules'    => 'nullable|numeric|between:-180,180',
                'placeholder' => 'مثال: 46.6753000',
                'step'     => '0.0000001',
                'icon'     => 'fas fa-globe',
                'column'   => 'col-md-6',
            ],

            // مواصفات المبنى
            [
                'name'     => 'number_of_floors',
                'label'    => 'عدد الأدوار',
                'type'     => 'number',
                'section'  => 'specifications',
                'rules'    => 'nullable|integer|min:0',
                'placeholder' => 'عدد الأدوار',
                'icon'     => 'fas fa-layer-group',
                'column'   => 'col-md-4',
            ],

            [
                'name'     => 'year_built',
                'label'    => 'سنة البناء',
                'type'     => 'number',
                'section'  => 'specifications',
                'rules'    => 'nullable|integer|min:1800|max:2100',
                'placeholder' => 'مثال: 2020',
                'icon'     => 'fas fa-calendar',
                'column'   => 'col-md-4',
            ],

            [
                'name'     => 'land_area',
                'label'    => 'مساحة الأرض (م²)',
                'type'     => 'number',
                'section'  => 'specifications',
                'rules'    => 'nullable|numeric|min:0',
                'step'     => '0.01',
                'icon'     => 'fas fa-ruler-combined',
                'column'   => 'col-md-4',
            ],

            [
                'name'     => 'building_area',
                'label'    => 'مساحة المبنى (م²)',
                'type'     => 'number',
                'section'  => 'specifications',
                'rules'    => 'nullable|numeric|min:0',
                'step'     => '0.01',
                'icon'     => 'fas fa-vector-square',
                'column'   => 'col-md-4',
            ],

            [
                'name'     => 'property_type',
                'label'    => 'نوع العقار',
                'type'     => 'select',
                'section'  => 'specifications',
                'default'  => 'residential',
                'options'  => [
                    'residential'  => 'سكني',
                    'commercial'   => 'تجاري',
                    'industrial'   => 'صناعي',
                    'agricultural' => 'زراعي',
                    'mixed'        => 'متعدد الاستخدامات',
                ],
                'rules' => 'required|in:residential,commercial,industrial,agricultural,mixed',
                'icon'  => 'fas fa-house',
                'column' => 'col-md-6',
            ],

            // الخدمات والمرافق
            [
                'name'    => 'has_elevator',
                'label'   => 'يوجد مصعد',
                'type'    => 'checkbox',
                'section' => 'facilities',
                'default' => false,
                'rules'   => 'boolean',
                'icon'    => 'fas fa-elevator',
                'column'  => 'col-md-3',
            ],

            [
                'name'    => 'has_parking',
                'label'   => 'يوجد موقف سيارات',
                'type'    => 'checkbox',
                'section' => 'facilities',
                'default' => false,
                'rules'   => 'boolean',
                'icon'    => 'fas fa-square-parking',
                'column'  => 'col-md-3',
            ],

            [
                'name'    => 'has_security',
                'label'   => 'توجد حراسة',
                'type'    => 'checkbox',
                'section' => 'facilities',
                'default' => false,
                'rules'   => 'boolean',
                'icon'    => 'fas fa-shield-alt',
                'column'  => 'col-md-3',
            ],

            [
                'name'    => 'has_maintenance',
                'label'   => 'توجد صيانة',
                'type'    => 'checkbox',
                'section' => 'facilities',
                'default' => false,
                'rules'   => 'boolean',
                'icon'    => 'fas fa-tools',
                'column'  => 'col-md-3',
            ],

            // الملاحظات
            [
                'name'     => 'notes',
                'label'    => 'ملاحظات إضافية',
                'type'     => 'textarea',
                'section'  => 'additional',
                'rules'    => 'nullable|string',
                'rows'     => 4,
                'placeholder' => 'أدخل الملاحظات',
                'icon'     => 'fas fa-sticky-note',
                'column'   => 'col-md-12',
            ],

            // الصور
            [
                'name'       => 'images',
                'label'      => 'صور المبنى',
                'type'       => 'file',
                'section'    => 'media',
                'multiple'   => true,
                'accept'     => 'image/*',
                'storage'    => 'public',
                'directory'  => 'buildings/images',
                'rules'      => 'nullable|array',
                'icon'       => 'fas fa-images',
                'column'     => 'col-md-12',
            ],

            // المستندات
            [
                'name'       => 'documents',
                'label'      => 'مستندات المبنى',
                'type'       => 'file',
                'section'    => 'media',
                'multiple'   => true,
                'accept'     => '.pdf,.jpg,.jpeg,.png',
                'storage'    => 'public',
                'directory'  => 'buildings/documents',
                'rules'      => 'nullable|array',
                'icon'       => 'fas fa-file-alt',
                'column'     => 'col-md-12',
            ],

            // الحالة
            [
                'name'    => 'is_active',
                'label'   => 'حالة المبنى',
                'type'    => 'select',
                'section' => 'additional',
                'default' => 1,
                'options' => [
                    1 => 'نشط',
                    0 => 'غير نشط',
                ],
                'rules' => 'boolean',
                'icon'  => 'fas fa-toggle-on',
                'column' => 'col-md-6',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 10. أقسام النموذج
    |--------------------------------------------------------------------------
    */
        'sections' => [
            'basic' => [
                'title' => 'البيانات الأساسية',
                'subtitle' => 'اسم المبنى والمالك والجهة المسؤولة',
                'icon' => 'fas fa-building',
            ],

            'address' => [
                'title' => 'العنوان',
                'subtitle' => 'بيانات العنوان الوطني',
                'icon' => 'fas fa-map-marker-alt',
            ],

            'location' => [
                'title' => 'الموقع الجغرافي',
                'subtitle' => 'إحداثيات موقع المبنى',
                'icon' => 'fas fa-map',
            ],

            'specifications' => [
                'title' => 'مواصفات المبنى',
                'subtitle' => 'المساحات والأدوار ونوع العقار',
                'icon' => 'fas fa-ruler-combined',
            ],

            'facilities' => [
                'title' => 'الخدمات والمرافق',
                'subtitle' => 'التجهيزات المتوفرة في المبنى',
                'icon' => 'fas fa-concierge-bell',
            ],

            'additional' => [
                'title' => 'بيانات إضافية',
                'subtitle' => 'الحالة والملاحظات',
                'icon' => 'fas fa-info-circle',
            ],

            'media' => [
                'title' => 'الصور والمستندات',
                'subtitle' => 'ملفات وصور المبنى',
                'icon' => 'fas fa-folder-open',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 11. الفلاتر
    |--------------------------------------------------------------------------
    */
        'filters' => [

            'status' => [
                'label' => 'حالة المبنى',
                'type' => 'select',
                'column' => 'is_active',
                'options' => [
                    ''  => 'جميع الحالات',
                    '1' => 'نشط',
                    '0' => 'غير نشط',
                ],
            ],

            'property_type' => [
                'label' => 'نوع العقار',
                'type' => 'select',
                'column' => 'property_type',
                'options' => [
                    '' => 'جميع الأنواع',
                    'residential' => 'سكني',
                    'commercial' => 'تجاري',
                    'industrial' => 'صناعي',
                    'agricultural' => 'زراعي',
                    'mixed' => 'متعدد الاستخدامات',
                ],
            ],

            'city' => [
                'label' => 'المدينة',
                'type' => 'text',
                'column' => 'city',
            ],

            'district' => [
                'label' => 'الحي',
                'type' => 'text',
                'column' => 'district',
            ],

            'owner_id' => [
                'label' => 'المالك',
                'type' => 'select',
                'column' => 'owner_id',
                'lookup' => 'owners',
            ],

            'organization_id' => [
                'label' => 'المؤسسة',
                'type' => 'select',
                'column' => 'organization_id',
                'lookup' => 'organizations',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 12. الإحصائيات
    |--------------------------------------------------------------------------
    */
        'stats' => [
            'total' => [
                'label' => 'إجمالي المباني',
                'column' => 'building_id',
                'aggregate' => 'count',
                'icon' => 'fas fa-building',
                'color' => 'primary',
            ],

            'active' => [
                'label' => 'المباني النشطة',
                'column' => 'is_active',
                'aggregate' => 'count',
                'where' => ['is_active', '=', 1],
                'icon' => 'fas fa-check-circle',
                'color' => 'success',
            ],

            'inactive' => [
                'label' => 'المباني غير النشطة',
                'column' => 'is_active',
                'aggregate' => 'count',
                'where' => ['is_active', '=', 0],
                'icon' => 'fas fa-pause-circle',
                'color' => 'secondary',
            ],

            'residential' => [
                'label' => 'المباني السكنية',
                'column' => 'property_type',
                'aggregate' => 'count',
                'where' => ['property_type', '=', 'residential'],
                'icon' => 'fas fa-house',
                'color' => 'info',
            ],

            'commercial' => [
                'label' => 'المباني التجارية',
                'column' => 'property_type',
                'aggregate' => 'count',
                'where' => ['property_type', '=', 'commercial'],
                'icon' => 'fas fa-store',
                'color' => 'warning',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 13. العلاقات
    |--------------------------------------------------------------------------
    */
        'relations' => [

            'owner' => [
                'table' => 'owners',
                'type' => 'belongsTo',
                'foreign_key' => 'owner_id',
                'primary_key' => 'owner_id',
                'name_field' => 'full_name',
                'fields' => [
                    'owner_id',
                    'full_name',
                    'phone',
                ],
                'label' => 'المالك',
            ],

            'organization' => [
                'table' => 'organizations',
                'type' => 'belongsTo',
                'foreign_key' => 'organization_id',
                'primary_key' => 'organization_id',
                'name_field' => 'name',
                'fields' => [
                    'organization_id',
                    'name',
                ],
                'label' => 'المؤسسة',
            ],

            'branch' => [
                'table' => 'agency_branches',
                'type' => 'belongsTo',
                'foreign_key' => 'branch_id',
                'primary_key' => 'branch_id',
                'name_field' => 'branch_name',
                'fields' => [
                    'branch_id',
                    'branch_name',
                ],
                'label' => 'الفرع',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 14. أزرار الإجراءات
    |--------------------------------------------------------------------------
    */
        'actions' => [

            'create' => [
                'enabled' => true,
                'label' => 'إضافة مبنى',
                'icon' => 'fas fa-plus',
                'color' => 'primary',
                'event' => 'open-universal-form',
                'mode' => 'create',
            ],

            'view' => [
                'enabled' => true,
                'label' => 'عرض التفاصيل',
                'icon' => 'fas fa-eye',
                'method' => 'openView',
            ],

            'edit' => [
                'enabled' => true,
                'label' => 'تعديل',
                'icon' => 'fas fa-edit',
                'method' => 'openEdit',
                'event' => 'open-universal-form',
                'mode' => 'edit',
            ],

            'toggle' => [
                'enabled' => true,
                'label' => 'تغيير الحالة',
                'icon' => 'fas fa-toggle-on',
                'method' => 'toggleStatus',
                'confirm' => true,
            ],

            'delete' => [
                'enabled' => true,
                'label' => 'حذف',
                'icon' => 'fas fa-trash',
                'method' => 'deleteRecord',
                'confirm' => true,
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 15. الأحداث والتنقلات
    |--------------------------------------------------------------------------
    */
        'events' => [

            'open_create' => [
                'name' => 'open-universal-form',
                'payload' => [
                    'entityKey' => 'buildings',
                    'mode' => 'create',
                ],
            ],

            'open_edit' => [
                'name' => 'open-universal-form',
                'payload' => [
                    'entityKey' => 'buildings',
                    'mode' => 'edit',
                ],
            ],

            'refresh' => [
                'name' => 'uf-data-updated',
            ],

            'error' => [
                'name' => 'uf-error',
            ],
        ],

        'navigation' => [

            'breadcrumb' => [
                [
                    'label' => 'الرئيسية',
                    'route' => 'organization.dashboard',
                    'icon' => 'fas fa-home',
                ],
                [
                    'label' => 'العقارات',
                    'route' => null,
                    'icon' => 'fas fa-city',
                ],
                [
                    'label' => 'المباني',
                    'route' => null,
                    'icon' => 'fas fa-building',
                ],
            ],

            'back' => [
                'enabled' => true,
                'label' => 'العودة إلى المباني',
            ],
        ],

        /*
    |--------------------------------------------------------------------------
    | 16. إعدادات الواجهة
    |--------------------------------------------------------------------------
    */
        'ui' => [
            'direction' => 'rtl',
            'theme' => 'royal',
            'font' => 'Tajawal',

            'show_stats' => true,
            'show_search' => true,
            'show_filters' => true,
            'show_pagination' => true,
            'show_actions' => true,
            'show_breadcrumb' => true,

            'show_create_button' => true,
            'show_export_button' => true,

            'confirm_delete' => true,
            'confirm_status_change' => true,

            'empty_message' => 'لا توجد مبانٍ لعرضها حالياً',
            'loading_message' => 'جاري تحميل بيانات المباني...',
        ],
    ],

];
