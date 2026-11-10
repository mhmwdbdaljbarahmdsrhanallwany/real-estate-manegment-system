
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

        'status' => [
            'column'      => 'status',
            'values'      => ['active', 'blocked'],
            'boolean_map' => null,
            'labels'      => [
                'active'   => 'نشط',
                'inactive' => 'غير نشط',
                'blocked'  => 'محظور',
            ],
        ],

        'title'              => '',
        'subtitle'           => '',
        'icon'               => 'fas fa-table',
        'entity_name'        => 'سجل',
        'entity_singular'    => 'سجل',
        'entity_sub'         => 'عنصر',
        'btn_add_main'       => 'إضافة',
        'btn_add_sub'        => 'إضافة عنصر',
        'sub_section_title'  => 'عناصر مرتبطة',
        'sub_section_icon'   => 'fas fa-list',

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
            'enabled'  => true,
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        'main_fields' => [],
        'sub_fields'  => [],
        'filters'     => [],
        'relations'   => [],
        'lookups'     => [],
        'stats'       => [],

        // رسائل الإرشاد والمعلومات والتحذيرات
        'field_hints'   => [],
        'field_help'    => [],
        'field_info'    => [],
        'field_warning' => [],

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

        'table'               => 'owners',
        'primary_key'         => 'owner_id',
        'organization_column' => 'organization_id',
        'name_field'          => 'full_name',
        'has_soft_deletes'    => true,
        'deleted_at_column'   => 'deleted_at',

        'tenant_scoped' => true,
        'allow_shared'  => false,

        'title'              => 'إدارة المالكين',
        'subtitle'           => 'عرض، إضافة، تعديل، حظر وإدارة وكلاء المالكين',
        'icon'               => 'fas fa-users-gear',
        'entity_name'        => 'المالكين',
        'entity_singular'    => 'مالك',
        'entity_sub'         => 'وكيل',
        'btn_add_main'       => 'إضافة مالك',
        'btn_add_sub'        => 'إضافة وكيل',
        'sub_section_title'  => 'وكلاء المالك',
        'sub_section_icon'   => 'fas fa-user-tie',

        'status' => [
            'column'      => 'is_blacklisted',
            'boolean_map' => [
                'true'  => 'blocked',
                'false' => 'active',
            ],
            'values' => ['active', 'inactive', 'blocked'],
            'labels' => [
                'active'   => 'نشط',
                'inactive' => 'غير نشط',
                'blocked'  => 'محظور',
            ],
        ],

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
            'status' => true,
        ],

        'search' => [
            'enabled' => true,
            'fields'  => [
                'full_name',
                'phone',
                'phone2',
                'email',
                'id_number',
            ],
        ],

        'pagination' => [
            'enabled'  => true,
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        'lookups' => [
            'organization_id' => [
                'table'   => 'organizations',
                'key'     => 'organization_id',
                'display' => 'name',
            ],
        ],

        // رسائل الحقول
        'field_hints' => [
            'full_name' => 'أدخل الاسم الكامل للمالك كما هو مسجل في وثيقة الهوية.',
            'phone' => 'أدخل رقم الجوال الأساسي للمالك.',
            'phone2' => 'يمكنك إدخال رقم جوال إضافي للتواصل مع المالك.',
            'email' => 'أدخل بريداً إلكترونياً صحيحاً للمالك.',
            'id_type' => 'حدد نوع وثيقة الهوية الخاصة بالمالك.',
            'id_number' => 'أدخل رقم الهوية أو الإقامة أو جواز السفر.',
            'nationality' => 'أدخل جنسية المالك، مثل سعودي أو يمني.',
            'address' => 'أدخل عنوان إقامة المالك بالتفصيل.',
            'bank_name' => 'أدخل اسم البنك الذي يوجد فيه حساب المالك.',
            'bank_account_name' => 'أدخل اسم صاحب الحساب كما يظهر في بيانات البنك.',
            'bank_account_number' => 'أدخل رقم الحساب البنكي.',
            'bank_iban' => 'أدخل رقم الآيبان كاملاً وتأكد من صحته.',
            'preferred_payment_method' => 'حدد طريقة استلام المالك لمستحقاته.',
            'status' => 'حدد حالة المالك في النظام.',
            'notes' => 'أضف أي معلومات أو ملاحظات إضافية تخص المالك.',
        ],

        'field_info' => [],
        'field_warning' => [],

        'stats' => [

            'active' => [
                'type'  => 'count_status',
                'value' => 'active',
                'label' => 'نشط',
                'icon'  => 'fas fa-user-check',
                'color' => 'emerald',
            ],

            'blocked' => [
                'type'  => 'count_status',
                'value' => 'blocked',
                'label' => 'محظور',
                'icon'  => 'fas fa-user-slash',
                'color' => 'cherry',
            ],

            'agents' => [
                'type'     => 'sum_relation',
                'relation' => 'agents',
                'label'    => 'الوكلاء',
                'icon'     => 'fas fa-user-tie',
                'color'    => 'sky',
            ],

            'agencies' => [
                'type'  => 'unique',
                'field' => 'organization_id',
                'label' => 'المؤسسات',
                'icon'  => 'fas fa-building',
                'color' => 'gold',
            ],
        ],

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

                    [
                        'key'      => 'full_name',
                        'label'    => 'اسم الوكيل',
                        'type'     => 'text',
                        'icon'     => 'fas fa-user',
                        'required' => true,
                        'hint'     => 'أدخل الاسم الكامل للوكيل.',
                        'help'     => 'أدخل الاسم الكامل للوكيل.',
                    ],

                    [
                        'key'   => 'phone',
                        'label' => 'رقم الجوال',
                        'type'  => 'tel',
                        'icon'  => 'fas fa-mobile-screen',
                        'hint'  => 'أدخل رقم جوال الوكيل.',
                        'help'  => 'أدخل رقم جوال الوكيل.',
                    ],

                    [
                        'key'   => 'email',
                        'label' => 'البريد',
                        'type'  => 'email',
                        'icon'  => 'fas fa-envelope',
                        'hint'  => 'أدخل البريد الإلكتروني الصحيح للوكيل.',
                        'help'  => 'أدخل البريد الإلكتروني الصحيح للوكيل.',
                    ],

                    [
                        'key'     => 'role',
                        'label'   => 'دور الوكيل',
                        'type'    => 'select',
                        'icon'    => 'fas fa-briefcase',
                        'default' => 'administrative',
                        'hint'    => 'حدد الصفة أو الصلاحية التي يمثل بها الوكيل المالك.',
                        'help'    => 'حدد الصفة أو الصلاحية التي يمثل بها الوكيل المالك.',
                        'options' => [
                            ['value' => 'administrative', 'label' => 'وكيل إداري'],
                            ['value' => 'legal', 'label' => 'وكيل قانوني'],
                            ['value' => 'property_manager', 'label' => 'مدير عقارات'],
                        ],
                    ],

                    [
                        'key'    => 'power_of_attorney_file',
                        'label'  => 'ملف التوكيل',
                        'type'   => 'file',
                        'icon'   => 'fas fa-file-contract',
                        'full'   => true,
                        'hint'   => 'ارفع وثيقة التوكيل بصيغة PDF أو JPG أو JPEG أو PNG.',
                        'help'   => 'ارفع وثيقة التوكيل بصيغة PDF أو JPG أو JPEG أو PNG.',
                        'upload' => [
                            'disk'       => 'public',
                            'directory' => 'organization/owners/agents/powers',
                            'mimes'      => ['pdf', 'jpg', 'jpeg', 'png'],
                            'max_kb'     => 10240,
                            'delete_old' => false,
                        ],
                    ],

                    [
                        'key'   => 'authority_start_date',
                        'label' => 'بداية التفويض',
                        'type'  => 'date',
                        'icon'  => 'fas fa-calendar-plus',
                        'hint'  => 'حدد تاريخ بداية صلاحية التفويض.',
                        'help'  => 'حدد تاريخ بداية صلاحية التفويض.',
                    ],

                    [
                        'key'   => 'authority_end_date',
                        'label' => 'نهاية التفويض',
                        'type'  => 'date',
                        'icon'  => 'fas fa-calendar-check',
                        'hint'  => 'حدد تاريخ انتهاء صلاحية التفويض.',
                        'help'  => 'حدد تاريخ انتهاء صلاحية التفويض.',
                    ],

                    [
                        'key'   => 'notes',
                        'label' => 'ملاحظات',
                        'type'  => 'textarea',
                        'full'  => true,
                        'hint'  => 'أضف أي معلومات إضافية تخص الوكيل.',
                        'help'  => 'أضف أي معلومات إضافية تخص الوكيل.',
                    ],
                ],
            ],
        ],

        'main_fields' => [

            [
                'key'      => 'full_name',
                'label'    => 'الاسم الكامل',
                'type'     => 'text',
                'icon'     => 'fas fa-user',
                'required' => true,
                'hint'     => 'أدخل الاسم الكامل للمالك كما هو مسجل في وثيقة الهوية.',
                'help'     => 'أدخل الاسم الكامل للمالك كما هو مسجل في وثيقة الهوية.',
            ],

            [
                'key'      => 'phone',
                'label'    => 'رقم الجوال',
                'type'     => 'tel',
                'icon'     => 'fas fa-mobile-screen',
                'required' => true,
                'hint'     => 'أدخل رقم الجوال الأساسي للمالك.',
                'help'     => 'أدخل رقم الجوال الأساسي للمالك.',
            ],

            [
                'key'   => 'phone2',
                'label' => 'جوال ثانوي',
                'type'  => 'tel',
                'icon'  => 'fas fa-mobile-screen-button',
                'hint'  => 'أدخل رقم جوال إضافياً إن وجد.',
                'help'  => 'أدخل رقم جوال إضافياً إن وجد.',
            ],

            [
                'key'   => 'email',
                'label' => 'البريد الإلكتروني',
                'type'  => 'email',
                'icon'  => 'fas fa-envelope',
                'hint'  => 'أدخل بريداً إلكترونياً صحيحاً.',
                'help'  => 'أدخل بريداً إلكترونياً صحيحاً.',
            ],

            [
                'key'     => 'id_type',
                'label'   => 'نوع الهوية',
                'type'    => 'select',
                'icon'    => 'fas fa-id-card',
                'default' => 'national_id',
                'hint'    => 'حدد نوع وثيقة الهوية الخاصة بالمالك.',
                'help'    => 'حدد نوع وثيقة الهوية الخاصة بالمالك.',
                'options' => [
                    ['value' => 'national_id', 'label' => 'هوية وطنية'],
                    ['value' => 'iqama', 'label' => 'إقامة'],
                    ['value' => 'passport', 'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],

            [
                'key'   => 'id_number',
                'label' => 'رقم الهوية',
                'type'  => 'text',
                'icon'  => 'fas fa-hashtag',
                'hint'  => 'أدخل رقم الهوية أو الإقامة أو جواز السفر.',
                'help'  => 'أدخل رقم الهوية أو الإقامة أو جواز السفر.',
            ],

            [
                'key'     => 'nationality',
                'label'   => 'الجنسية',
                'type'    => 'text',
                'icon'    => 'fas fa-globe',
                'default' => 'سعودي',
                'hint'    => 'أدخل جنسية المالك، مثل سعودي أو يمني.',
                'help'    => 'أدخل جنسية المالك، مثل سعودي أو يمني.',
            ],

            [
                'key'   => 'address',
                'label' => 'العنوان',
                'type'  => 'text',
                'icon'  => 'fas fa-location-dot',
                'full'  => true,
                'hint'  => 'أدخل عنوان إقامة المالك بالتفصيل.',
                'help'  => 'أدخل عنوان إقامة المالك بالتفصيل.',
            ],

            [
                'key'   => 'bank_name',
                'label' => 'اسم البنك',
                'type'  => 'text',
                'icon'  => 'fas fa-building-columns',
                'hint'  => 'أدخل اسم البنك الذي يوجد فيه حساب المالك.',
                'help'  => 'أدخل اسم البنك الذي يوجد فيه حساب المالك.',
            ],

            [
                'key'   => 'bank_account_name',
                'label' => 'اسم صاحب الحساب',
                'type'  => 'text',
                'icon'  => 'fas fa-user-check',
                'hint'  => 'أدخل اسم صاحب الحساب كما يظهر في بيانات البنك.',
                'help'  => 'أدخل اسم صاحب الحساب كما يظهر في بيانات البنك.',
            ],

            [
                'key'   => 'bank_account_number',
                'label' => 'رقم الحساب',
                'type'  => 'text',
                'icon'  => 'fas fa-credit-card',
                'hint'  => 'أدخل رقم الحساب البنكي.',
                'help'  => 'أدخل رقم الحساب البنكي.',
            ],

            [
                'key'   => 'bank_iban',
                'label' => 'رقم الآيبان',
                'type'  => 'text',
                'icon'  => 'fas fa-barcode',
                'hint'  => 'أدخل رقم الآيبان كاملاً وتأكد من صحته.',
                'help'  => 'أدخل رقم الآيبان كاملاً وتأكد من صحته.',
            ],

            [
                'key'     => 'preferred_payment_method',
                'label'   => 'طريقة الدفع',
                'type'    => 'select',
                'icon'    => 'fas fa-wallet',
                'default' => 'bank_transfer',
                'hint'    => 'حدد طريقة استلام المالك لمستحقاته.',
                'help'    => 'حدد طريقة استلام المالك لمستحقاته.',
                'options' => [
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque', 'label' => 'شيك'],
                    ['value' => 'cash', 'label' => 'نقداً'],
                ],
            ],

            [
                'key'       => 'status',
                'label'     => 'الحالة',
                'type'      => 'select',
                'icon'      => 'fas fa-toggle-on',
                'default'   => 'active',
                'is_status' => true,
                'hint'      => 'حدد حالة المالك في النظام.',
                'help'      => 'حدد حالة المالك في النظام.',
                'options'   => [
                    ['value' => 'active', 'label' => 'نشط'],
                    ['value' => 'blocked', 'label' => 'محظور'],
                ],
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'full'  => true,
                'hint'  => 'أضف أي معلومات أو ملاحظات إضافية تخص المالك.',
                'help'  => 'أضف أي معلومات أو ملاحظات إضافية تخص المالك.',
            ],
        ],

        'filters' => [

            [
                'key'   => 'status',
                'label' => 'الحالة',
                'icon'  => 'fas fa-toggle-on',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'active', 'label' => 'نشط'],
                    ['value' => 'blocked', 'label' => 'محظور'],
                ],
            ],

            [
                'key'   => 'id_type',
                'label' => 'نوع الهوية',
                'icon'  => 'fas fa-id-card',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'national_id', 'label' => 'هوية وطنية'],
                    ['value' => 'iqama', 'label' => 'إقامة'],
                    ['value' => 'passport', 'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],

            [
                'key'   => 'organization_id',
                'label' => 'المؤسسة',
                'icon'  => 'fas fa-building',
                'type'  => 'select',
                'options_source' => 'organization_id',
            ],

            [
                'key'   => 'nationality',
                'label' => 'الجنسية',
                'icon'  => 'fas fa-globe',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'سعودي', 'label' => 'سعودي'],
                    ['value' => 'يمني', 'label' => 'يمني'],
                    ['value' => 'مقيم', 'label' => 'مقيم'],
                ],
            ],

            [
                'key'    => 'dateFrom',
                'label'  => 'من تاريخ',
                'icon'   => 'fas fa-calendar-plus',
                'type'   => 'date',
                'column' => 'created_at',
            ],

            [
                'key'    => 'dateTo',
                'label'  => 'إلى تاريخ',
                'icon'   => 'fas fa-calendar-check',
                'type'   => 'date',
                'column' => 'created_at',
            ],

            [
                'key'   => 'preferred_payment_method',
                'label' => 'طريقة الدفع',
                'icon'  => 'fas fa-wallet',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque', 'label' => 'شيك'],
                    ['value' => 'cash', 'label' => 'نقداً'],
                ],
            ],

            [
                'key'      => 'hasSub',
                'label'    => 'الوكلاء',
                'icon'     => 'fas fa-user-tie',
                'type'     => 'relation_exists',
                'relation' => 'agents',
                'options'  => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'has', 'label' => 'لديه وكلاء'],
                    ['value' => 'none', 'label' => 'بدون وكلاء'],
                ],
            ],

            [
                'key'   => 'sort',
                'label' => 'الترتيب',
                'icon'  => 'fas fa-sort',
                'type'  => 'sort',
                'options' => [
                    ['value' => 'newest', 'label' => 'الأحدث أولاً'],
                    ['value' => 'oldest', 'label' => 'الأقدم أولاً'],
                    ['value' => 'name_asc', 'label' => 'الاسم (أ-ي)'],
                    ['value' => 'name_desc', 'label' => 'الاسم (ي-أ)'],
                ],
            ],
        ],

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
                    [
                        'field' => 'id_number',
                        'label' => 'رقم الهوية',
                        'icon'  => 'fas fa-hashtag',
                    ],
                    [
                        'field' => 'organization_id',
                        'label' => 'المؤسسة',
                        'icon'  => 'fas fa-building',
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
                        'label' => 'البريد',
                        'icon'  => 'fas fa-envelope',
                        'color' => 'gold',
                    ],
                    [
                        'field' => 'nationality',
                        'label' => 'الجنسية',
                        'icon'  => 'fas fa-globe',
                        'color' => 'green',
                    ],
                    [
                        'field' => 'id_type',
                        'label' => 'نوع الهوية',
                        'icon'  => 'fas fa-id-card',
                        'color' => 'orange',
                        'type'  => 'select',
                    ],
                    [
                        'field' => 'address',
                        'label' => 'العنوان',
                        'icon'  => 'fas fa-location-dot',
                        'color' => 'purple',
                    ],
                    [
                        'field' => 'bank_name',
                        'label' => 'البنك',
                        'icon'  => 'fas fa-building-columns',
                        'color' => 'sky',
                    ],
                    [
                        'field' => 'bank_iban',
                        'label' => 'الآيبان',
                        'icon'  => 'fas fa-credit-card',
                        'color' => 'blue',
                    ],
                    [
                        'field' => 'preferred_payment_method',
                        'label' => 'طريقة الدفع',
                        'icon'  => 'fas fa-wallet',
                        'color' => 'green',
                        'type'  => 'select',
                    ],
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

        'sub_fields' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | كيان: وكلاء الملاك
    |--------------------------------------------------------------------------
    */

    'owner_agents' => [

        'table'             => 'owner_agents',
        'primary_key'       => 'agent_id',
        'name_field'        => 'full_name',
        'has_soft_deletes'  => false,
        'deleted_at_column' => 'deleted_at',

        'tenant_scoped' => true,
        'allow_shared'  => false,

        'tenant_scope' => [
            'type'                  => 'belongs_to',
            'table'                 => 'owners',
            'local_key'             => 'owner_id',
            'owner_key'             => 'owner_id',
            'organization_column'   => 'organization_id',
            'parent_soft_deletes'   => true,
            'parent_deleted_column' => 'deleted_at',
        ],

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
            'status' => true,
        ],

        'search' => [
            'enabled' => true,
            'fields'  => [
                'full_name',
                'phone',
                'email',
            ],
        ],

        'pagination' => [
            'enabled'  => true,
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        'lookups' => [
            'owner_id' => [
                'table'               => 'owners',
                'key'                 => 'owner_id',
                'display'             => 'full_name',
                'organization_column' => 'organization_id',
            ],
        ],

        'field_hints' => [
            'owner_id' => 'اختر المالك الذي يتبع له هذا الوكيل.',
            'full_name' => 'أدخل الاسم الكامل للوكيل كما هو مسجل في وثيقة الهوية.',
            'phone' => 'أدخل رقم جوال الوكيل للتواصل معه.',
            'email' => 'أدخل البريد الإلكتروني الصحيح للوكيل.',
            'role' => 'حدد صفة الوكيل: إداري أو قانوني أو مدير عقارات.',
            'power_of_attorney_file' => 'ارفع وثيقة التوكيل بصيغة PDF أو JPG أو JPEG أو PNG.',
            'authority_start_date' => 'حدد تاريخ بداية صلاحية التفويض.',
            'authority_end_date' => 'حدد تاريخ انتهاء صلاحية التفويض.',
            'is_active' => 'حدد ما إذا كان الوكيل نشطاً أو غير نشط.',
            'notes' => 'أدخل أي ملاحظات أو تفاصيل إضافية عن الوكيل.',
        ],

        'field_info' => [],
        'field_warning' => [],

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

        'main_fields' => [

            [
                'key'      => 'owner_id',
                'label'    => 'المالك',
                'type'     => 'select',
                'icon'     => 'fas fa-user',
                'required' => true,
                'options_source' => 'owner_id',
                'hint'     => 'اختر المالك الذي يتبع له هذا الوكيل.',
                'help'     => 'اختر المالك الذي يتبع له هذا الوكيل.',
            ],

            [
                'key'      => 'full_name',
                'label'    => 'اسم الوكيل',
                'type'     => 'text',
                'icon'     => 'fas fa-user-tie',
                'required' => true,
                'hint'     => 'أدخل الاسم الكامل للوكيل.',
                'help'     => 'أدخل الاسم الكامل للوكيل.',
            ],

            [
                'key'   => 'phone',
                'label' => 'رقم الجوال',
                'type'  => 'tel',
                'icon'  => 'fas fa-mobile-screen',
                'hint'  => 'أدخل رقم جوال الوكيل.',
                'help'  => 'أدخل رقم جوال الوكيل.',
            ],

            [
                'key'   => 'email',
                'label' => 'البريد الإلكتروني',
                'type'  => 'email',
                'icon'  => 'fas fa-envelope',
                'hint'  => 'أدخل البريد الإلكتروني الصحيح للوكيل.',
                'help'  => 'أدخل البريد الإلكتروني الصحيح للوكيل.',
            ],

            [
                'key'     => 'role',
                'label'   => 'صفة الوكيل',
                'type'    => 'select',
                'icon'    => 'fas fa-briefcase',
                'default' => 'administrative',
                'hint'    => 'حدد الصفة أو الصلاحية التي يمثل بها الوكيل المالك.',
                'help'    => 'حدد الصفة أو الصلاحية التي يمثل بها الوكيل المالك.',
                'options' => [
                    ['value' => 'administrative', 'label' => 'وكيل إداري'],
                    ['value' => 'legal', 'label' => 'وكيل قانوني'],
                    ['value' => 'property_manager', 'label' => 'مدير عقارات'],
                ],
            ],

            [
                'key'   => 'power_of_attorney_file',
                'label' => 'ملف التوكيل',
                'type'  => 'file',
                'icon'  => 'fas fa-file-contract',
                'hint'  => 'ارفع وثيقة التوكيل بصيغة PDF أو JPG أو JPEG أو PNG.',
                'help'  => 'ارفع وثيقة التوكيل بصيغة PDF أو JPG أو JPEG أو PNG.',
                'upload' => [
                    'disk'       => 'public',
                    'directory' => 'organization/owner-agents/powers',
                    'mimes'      => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_kb'     => 10240,
                    'delete_old' => false,
                ],
                'full' => true,
            ],

            [
                'key'   => 'authority_start_date',
                'label' => 'بداية التفويض',
                'type'  => 'date',
                'icon'  => 'fas fa-calendar-plus',
                'hint'  => 'حدد تاريخ بداية صلاحية التفويض.',
                'help'  => 'حدد تاريخ بداية صلاحية التفويض.',
            ],

            [
                'key'   => 'authority_end_date',
                'label' => 'نهاية التفويض',
                'type'  => 'date',
                'icon'  => 'fas fa-calendar-check',
                'hint'  => 'حدد تاريخ انتهاء صلاحية التفويض.',
                'help'  => 'حدد تاريخ انتهاء صلاحية التفويض.',
            ],

            [
                'key'       => 'is_active',
                'label'     => 'الحالة',
                'type'      => 'select',
                'icon'      => 'fas fa-toggle-on',
                'default'   => 1,
                'is_status' => true,
                'hint'      => 'حدد ما إذا كان الوكيل نشطاً أو غير نشط.',
                'help'      => 'حدد ما إذا كان الوكيل نشطاً أو غير نشط.',
                'options'   => [
                    ['value' => 1, 'label' => 'نشط'],
                    ['value' => 0, 'label' => 'غير نشط'],
                ],
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'icon'  => 'fas fa-note-sticky',
                'full'  => true,
                'hint'  => 'أدخل أي ملاحظات أو تفاصيل إضافية عن الوكيل.',
                'help'  => 'أدخل أي ملاحظات أو تفاصيل إضافية عن الوكيل.',
            ],
        ],

        'filters' => [

            [
                'key'   => 'status',
                'label' => 'الحالة',
                'icon'  => 'fas fa-toggle-on',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'active', 'label' => 'نشط'],
                    ['value' => 'inactive', 'label' => 'غير نشط'],
                    ['value' => 'blocked', 'label' => 'محظور'],
                ],
            ],

            [
                'key'   => 'id_type',
                'label' => 'نوع الهوية',
                'icon'  => 'fas fa-id-card',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'national_id', 'label' => 'هوية وطنية'],
                    ['value' => 'iqama', 'label' => 'إقامة'],
                    ['value' => 'passport', 'label' => 'جواز سفر'],
                    ['value' => 'commercial_reg', 'label' => 'سجل تجاري'],
                ],
            ],

            [
                'key'   => 'organization_id',
                'label' => 'المؤسسة',
                'icon'  => 'fas fa-building',
                'type'  => 'select',
                'options_source' => 'organization_id',
            ],

            [
                'key'   => 'nationality',
                'label' => 'الجنسية',
                'icon'  => 'fas fa-globe',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'سعودي', 'label' => 'سعودي'],
                    ['value' => 'يمني', 'label' => 'يمني'],
                    ['value' => 'مقيم', 'label' => 'مقيم'],
                ],
            ],

            [
                'key'    => 'dateFrom',
                'label'  => 'من تاريخ',
                'icon'   => 'fas fa-calendar-plus',
                'type'   => 'date',
                'column' => 'created_at',
            ],

            [
                'key'    => 'dateTo',
                'label'  => 'إلى تاريخ',
                'icon'   => 'fas fa-calendar-check',
                'type'   => 'date',
                'column' => 'created_at',
            ],

            [
                'key'   => 'preferred_payment_method',
                'label' => 'طريقة الدفع',
                'icon'  => 'fas fa-wallet',
                'type'  => 'select',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'bank_transfer', 'label' => 'تحويل بنكي'],
                    ['value' => 'cheque', 'label' => 'شيك'],
                    ['value' => 'cash', 'label' => 'نقداً'],
                ],
            ],

            [
                'key'      => 'hasSub',
                'label'    => 'الوكلاء',
                'icon'     => 'fas fa-user-tie',
                'type'     => 'relation_exists',
                'relation' => 'agents',
                'options'  => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'has', 'label' => 'لديه وكلاء'],
                    ['value' => 'none', 'label' => 'بدون وكلاء'],
                ],
            ],

            [
                'key'   => 'sort',
                'label' => 'الترتيب',
                'icon'  => 'fas fa-sort',
                'type'  => 'sort',
                'options' => [
                    ['value' => 'newest', 'label' => 'الأحدث أولاً'],
                    ['value' => 'oldest', 'label' => 'الأقدم أولاً'],
                    ['value' => 'name_asc', 'label' => 'الاسم (أ-ي)'],
                    ['value' => 'name_desc', 'label' => 'الاسم (ي-أ)'],
                ],
            ],
        ],

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

        'relations'  => [],
        'sub_fields' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | كيان: المباني
    |--------------------------------------------------------------------------
    */

    'buildings' => [

        'table'               => 'buildings',
        'primary_key'         => 'building_id',
        'organization_column' => 'organization_id',
        'name_field'          => 'building_name',
        'has_soft_deletes'    => true,
        'deleted_at_column'   => 'deleted_at',

        'location_picker' => [
    'enabled'           => true,
    'latitude_field'    => 'latitude',
    'longitude_field'   => 'longitude',

    'default_latitude'  => 24.7136,
    'default_longitude' => 46.6753,

    'zoom'              => 13,
    'height'            => '320px',

    'tile_url'          => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    'tile_attribution'  => '&copy; OpenStreetMap contributors',

    'marker_fields' => [
        'city'          => 'city',
        'district'      => 'district',
        'property_type' => 'property_type',
        'area'          => 'building_area',
        'image'         => 'images',
    ],
],

        'tenant_scoped' => true,
        'allow_shared'  => false,

        'title'              => 'إدارة المباني',
        'subtitle'           => 'إدارة المباني والعمارات والمواقع والمواصفات العقارية',
        'icon'               => 'fas fa-building',
        'entity_name'        => 'المباني والعقارات',
        'entity_singular'    => 'مبنى',
        'btn_add_main'       => 'إضافة مبنى',

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
            'status' => true,
        ],

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

        'pagination' => [
            'enabled'  => true,
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        'lookups' => [

            'owner_id' => [
                'table'   => 'owners',
                'key'     => 'owner_id',
                'display' => 'full_name',
            ],

'unit_types' => [
    'table'   => 'unit_types',
    'key'     => 'type_id',
    'display' => 'name',
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

        // رسائل الحقول
        'field_hints' => [

            'building_name' => 'أدخل اسماً واضحاً للمبنى ليسهل التعرف عليه.',
            'building_number' => 'أدخل رقم المبنى الوطني إن كان متوفراً.',
            'owner_id' => 'اختر المالك المسجل لهذا المبنى.',
            'organization_id' => 'حدد المؤسسة المسؤولة عن إدارة المبنى.',
            'branch_id' => 'اختر الفرع المسؤول عن متابعة المبنى.',

            'address_line1' => 'أدخل العنوان الرئيسي للمبنى.',
            'address_line2' => 'أدخل تفاصيل إضافية للعنوان إن وجدت.',
            'city' => 'أدخل اسم المدينة التي يقع فيها المبنى.',
            'district' => 'أدخل اسم الحي الذي يقع فيه المبنى.',
            'street' => 'أدخل اسم الشارع أو الطريق.',
            'postal_code' => 'أدخل الرمز البريدي للموقع.',
            'country' => 'أدخل الدولة التي يقع فيها المبنى.',

            'latitude' => 'خط العرض الجغرافي للمبنى. يمكنك تحديده بالنقر على موقع المبنى في الخريطة.',
            'longitude' => 'خط الطول الجغرافي للمبنى. يمكنك تحديده بالنقر على موقع المبنى في الخريطة.',

            'number_of_floors' => 'أدخل إجمالي عدد الأدوار في المبنى.',
            'number_of_units' => 'أدخل إجمالي عدد الوحدات أو الشقق داخل المبنى.',
            'year_built' => 'أدخل سنة إنشاء المبنى.',
            'land_area' => 'أدخل مساحة الأرض بالمتر المربع.',
            'building_area' => 'أدخل مساحة البناء بالمتر المربع.',
            'property_type' => 'حدد نوع استخدام العقار.',

            'has_elevator' => 'فعّل الخيار إذا كان المبنى يحتوي على مصعد.',
            'has_parking' => 'فعّل الخيار إذا كان المبنى يحتوي على مواقف سيارات.',
            'has_security' => 'فعّل الخيار إذا كانت هناك حراسة أمنية للمبنى.',
            'has_maintenance' => 'فعّل الخيار إذا كانت تتوفر خدمات صيانة للمبنى.',

            'images' => 'يمكنك رفع صور المبنى بصيغ JPG أو JPEG أو PNG أو WEBP.',
            'documents' => 'ارفع مستندات المبنى مثل الصكوك والعقود والمخططات بالامتدادات المسموح بها.',

            'notes' => 'أدخل أي معلومات إضافية عن المبنى.',
            'is_active' => 'حدد ما إذا كان المبنى نشطاً أو غير نشط.',
        ],

        'field_info' => [
            'latitude'  => 'تقبل الإحداثيات القيم العشرية مثل 15.369445.',
            'longitude' => 'تقبل الإحداثيات القيم العشرية مثل 44.191007.',
            'land_area' => 'يمكنك إدخال قيمة عشرية مثل 250.75.',
            'building_area' => 'يمكنك إدخال قيمة عشرية مثل 180.50.',
        ],

        'field_warning' => [
            'latitude'  => 'يجب أن تكون قيمة خط العرض بين -90 و90.',
            'longitude' => 'يجب أن تكون قيمة خط الطول بين -180 و180.',
            'year_built' => 'تأكد من إدخال التاريخ بما يتوافق مع نوع الحقل في قاعدة البيانات.',
        ],

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

       
'relations' => [

    // علاقات المبنى المرجعية
    'owner' => [
        'type'        => 'belongsTo',
        'table'       => 'owners',
        'primary_key' => 'owner_id',
        'foreign_key' => 'owner_id',
        'name_field'  => 'full_name',
        'enabled'     => true,
        'fields'      => ['owner_id', 'full_name', 'phone'],
        'label'       => 'المالك',
    ],

    'organization' => [
        'type'        => 'belongsTo',
        'table'       => 'organizations',
        'primary_key' => 'organization_id',
        'foreign_key' => 'organization_id',
        'name_field'  => 'name',
        'enabled'     => true,
        'fields'      => ['organization_id', 'name'],
        'label'       => 'المؤسسة',
    ],

    'branch' => [
        'type'        => 'belongsTo',
        'table'       => 'agency_branches',
        'primary_key' => 'branch_id',
        'foreign_key' => 'branch_id',
        'name_field'  => 'branch_name',
        'enabled'     => true,
        'fields'      => ['branch_id', 'branch_name'],
        'label'       => 'الفرع',
    ],

    // وحدات وشقق المبنى
    'units' => [
        'type'        => 'hasMany',
        'table'       => 'units',
        'primary_key' => 'unit_id',
        'foreign_key' => 'building_id',
        'enabled'     => true,

        'has_soft_deletes'  => true,
        'deleted_at_column' => 'deleted_at',

        'status_column' => 'status',

        'label' => 'وحدات المبنى',

        'fields' => [

            [
                'key'      => 'unit_number',
                'label'    => 'رقم الوحدة أو الشقة',
                'type'     => 'text',
                'required' => true,
                'full'     => false,
            ],

            [
                'key'      => 'type_id',
                'label'    => 'نوع الوحدة',
                'type'     => 'select',
                'lookup'   => 'unit_types',
                'required' => false,
            ],

            [
                'key'   => 'floor',
                'label' => 'رقم الطابق',
                'type'  => 'number',
                'step'  => 1,
                'min'   => 0,
            ],

            [
                'key'   => 'size',
                'label' => 'المساحة بالمتر المربع',
                'type'  => 'number',
                'step'  => '0.01',
                'min'   => 0,
            ],

            [
                'key'   => 'bedrooms',
                'label' => 'عدد غرف النوم',
                'type'  => 'number',
                'default' => 0,
                'step'  => 1,
                'min'   => 0,
            ],

            [
                'key'   => 'bathrooms',
                'label' => 'عدد دورات المياه',
                'type'  => 'number',
                'default' => 0,
                'step'  => 1,
                'min'   => 0,
            ],

            [
                'key'   => 'halls',
                'label' => 'عدد الصالات',
                'type'  => 'number',
                'default' => 0,
                'step'  => 1,
                'min'   => 0,
            ],

            [
                'key'   => 'kitchen',
                'label' => 'يوجد مطبخ',
                'type'  => 'checkbox',
                'default' => true,
            ],

            [
                'key'   => 'balcony',
                'label' => 'يوجد شرفة',
                'type'  => 'checkbox',
                'default' => false,
            ],

            [
                'key'   => 'furnished',
                'label' => 'مؤثثة',
                'type'  => 'checkbox',
                'default' => false,
            ],

            [
                'key'   => 'rent_price',
                'label' => 'قيمة الإيجار',
                'type'  => 'number',
                'step'  => '0.01',
                'min'   => 0,
            ],

            [
                'key'      => 'status',
                'label'    => 'حالة الوحدة',
                'type'     => 'select',
                'required' => true,
                'default'  => 'available',
                'options'  => [
                    ['value' => 'available',   'label' => 'متاحة'],
                    ['value' => 'occupied',    'label' => 'مؤجرة'],
                    ['value' => 'maintenance', 'label' => 'تحت الصيانة'],
                    ['value' => 'reserved',    'label' => 'محجوزة'],
                    ['value' => 'closed',      'label' => 'مغلقة'],
                ],
            ],

            [
                'key'   => 'last_maintenance_date',
                'label' => 'تاريخ آخر صيانة',
                'type'  => 'date',
            ],

            [
                'key'   => 'next_maintenance_date',
                'label' => 'موعد الصيانة القادمة',
                'type'  => 'date',
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'full'  => true,
            ],
        ],

        'ui' => [
            'title_field'    => 'unit_number',
            'subtitle_field' => 'rent_price',
            'type_field'     => 'status',
            'status_icon'    => 'fas fa-house',
        ],
    ],
],

'sub_section_title' => 'شقق ووحدات المبنى',
'sub_section_icon'  => 'fas fa-house',
'entity_sub'        => 'وحدة',
'btn_add_sub'       => 'إضافة شقة',


        'main_fields' => [

            [
                'key'      => 'building_name',
                'label'    => 'اسم المبنى',
                'type'     => 'text',
                'icon'     => 'fas fa-building',
                'required' => true,
                'hint'     => 'أدخل اسماً واضحاً للمبنى ليسهل التعرف عليه.',
                'help'     => 'أدخل اسماً واضحاً للمبنى ليسهل التعرف عليه.',
            ],

            [
                'key'   => 'building_number',
                'label' => 'رقم المبنى الوطني',
                'type'  => 'text',
                'icon'  => 'fas fa-hashtag',
                'hint'  => 'أدخل رقم المبنى الوطني إن كان متوفراً.',
                'help'  => 'أدخل رقم المبنى الوطني إن كان متوفراً.',
            ],

            [
                'key'      => 'owner_id',
                'label'    => 'مالك المبنى',
                'type'     => 'select',
                'lookup'   => 'owner_id',
                'icon'     => 'fas fa-user',
                'required' => true,
                'hint'     => 'اختر المالك المسجل لهذا المبنى.',
                'help'     => 'اختر المالك المسجل لهذا المبنى.',
            ],

            [
                'key'    => 'organization_id',
                'label'  => 'المؤسسة المسؤولة',
                'type'   => 'select',
                'lookup' => 'organization_id',
                'icon'   => 'fas fa-sitemap',
                'hint'   => 'حدد المؤسسة المسؤولة عن إدارة المبنى.',
                'help'   => 'حدد المؤسسة المسؤولة عن إدارة المبنى.',
            ],

            [
                'key'    => 'branch_id',
                'label'  => 'الفرع المسؤول',
                'type'   => 'select',
                'lookup' => 'branch_id',
                'icon'   => 'fas fa-code-branch',
                'hint'   => 'اختر الفرع المسؤول عن متابعة المبنى.',
                'help'   => 'اختر الفرع المسؤول عن متابعة المبنى.',
            ],

            [
                'key'   => 'address_line1',
                'label' => 'العنوان الرئيسي',
                'type'  => 'text',
                'icon'  => 'fas fa-map-marker-alt',
                'hint'  => 'أدخل العنوان الرئيسي للمبنى.',
                'help'  => 'أدخل العنوان الرئيسي للمبنى.',
            ],

            [
                'key'   => 'address_line2',
                'label' => 'العنوان الإضافي',
                'type'  => 'text',
                'icon'  => 'fas fa-location-arrow',
                'hint'  => 'أدخل تفاصيل إضافية للعنوان إن وجدت.',
                'help'  => 'أدخل تفاصيل إضافية للعنوان إن وجدت.',
            ],

            [
                'key'   => 'city',
                'label' => 'المدينة',
                'type'  => 'text',
                'icon'  => 'fas fa-city',
                'hint'  => 'أدخل اسم المدينة التي يقع فيها المبنى.',
                'help'  => 'أدخل اسم المدينة التي يقع فيها المبنى.',
            ],

            [
                'key'   => 'district',
                'label' => 'الحي',
                'type'  => 'text',
                'icon'  => 'fas fa-map',
                'hint'  => 'أدخل اسم الحي الذي يقع فيه المبنى.',
                'help'  => 'أدخل اسم الحي الذي يقع فيه المبنى.',
            ],

            [
                'key'   => 'street',
                'label' => 'الشارع',
                'type'  => 'text',
                'icon'  => 'fas fa-road',
                'hint'  => 'أدخل اسم الشارع أو الطريق.',
                'help'  => 'أدخل اسم الشارع أو الطريق.',
            ],

            [
                'key'   => 'postal_code',
                'label' => 'الرمز البريدي',
                'type'  => 'text',
                'icon'  => 'fas fa-mail-bulk',
                'hint'  => 'أدخل الرمز البريدي للموقع.',
                'help'  => 'أدخل الرمز البريدي للموقع.',
            ],

            [
                'key'     => 'country',
                'label'   => 'الدولة',
                'type'    => 'text',
                'default' => 'السعودية',
                'icon'    => 'fas fa-globe',
                'hint'    => 'أدخل الدولة التي يقع فيها المبنى.',
                'help'    => 'أدخل الدولة التي يقع فيها المبنى.',
            ],

            [
                'key'   => 'latitude',
                'label' => 'خط العرض',
                'type'  => 'number',
                'icon'  => 'fas fa-map-pin',
                'step'  => 'any',
                'hint'  => 'خط العرض الجغرافي للمبنى. يمكنك تحديده بالنقر على موقع المبنى في الخريطة.',
                'help'  => 'خط العرض الجغرافي للمبنى. يمكنك تحديده بالنقر على موقع المبنى في الخريطة.',
                'info'  => 'تقبل الإحداثيات القيم العشرية مثل 15.369445.',
                'warning' => 'يجب أن تكون قيمة خط العرض بين -90 و90.',
            ],

            [
                'key'   => 'longitude',
                'label' => 'خط الطول',
                'type'  => 'number',
                'icon'  => 'fas fa-map-pin',
                'step'  => 'any',
                'hint'  => 'خط الطول الجغرافي للمبنى. يمكنك تحديده بالنقر على موقع المبنى في الخريطة.',
                'help'  => 'خط الطول الجغرافي للمبنى. يمكنك تحديده بالنقر على موقع المبنى في الخريطة.',
                'info'  => 'تقبل الإحداثيات القيم العشرية مثل 44.191007.',
                'warning' => 'يجب أن تكون قيمة خط الطول بين -180 و180.',
            ],

            [
                'key'   => 'number_of_floors',
                'label' => 'عدد الأدوار',
                'type'  => 'number',
                'icon'  => 'fas fa-layer-group',
                'min'   => 0,
                'hint'  => 'أدخل إجمالي عدد الأدوار في المبنى.',
                'help'  => 'أدخل إجمالي عدد الأدوار في المبنى.',
            ],

            [
                'key'   => 'number_of_units',
                'label' => 'عدد الوحدات',
                'type'  => 'number',
                'icon'  => 'fas fa-door-open',
                'min'   => 0,
                'hint'  => 'أدخل إجمالي عدد الوحدات أو الشقق داخل المبنى.',
                'help'  => 'أدخل إجمالي عدد الوحدات أو الشقق داخل المبنى.',
            ],

            [
                'key'   => 'year_built',
                'label' => 'سنة البناء',
                'type'  => 'number',
                'icon'  => 'fas fa-calendar',
                'hint'  => 'حدد تاريخ إنشاء المبنى.',
                'help'  => 'حدد تاريخ إنشاء المبنى.',
                'warning' => 'تأكد من إدخال التاريخ بما يتوافق مع نوع الحقل في قاعدة البيانات.',
            ],

            [
                'key'   => 'land_area',
                'label' => 'مساحة الأرض (م²)',
                'type'  => 'number',
                'icon'  => 'fas fa-ruler-combined',
                'step'  => 'any',
                'min'   => 0,
                'hint'  => 'أدخل مساحة الأرض بالمتر المربع، ويمكنك إدخال قيمة عشرية.',
                'help'  => 'أدخل مساحة الأرض بالمتر المربع، ويمكنك إدخال قيمة عشرية.',
                'info'  => 'يمكنك إدخال قيمة عشرية مثل 250.75.',
            ],

            [
                'key'   => 'building_area',
                'label' => 'مساحة المبنى (م²)',
                'type'  => 'number',
                'icon'  => 'fas fa-vector-square',
                'step'  => 'any',
                'min'   => 0,
                'hint'  => 'أدخل مساحة البناء بالمتر المربع، ويمكنك إدخال قيمة عشرية.',
                'help'  => 'أدخل مساحة البناء بالمتر المربع، ويمكنك إدخال قيمة عشرية.',
                'info'  => 'يمكنك إدخال قيمة عشرية مثل 180.50.',
            ],

            [
                'key'     => 'property_type',
                'label'   => 'نوع العقار',
                'type'    => 'select',
                'icon'    => 'fas fa-house',
                'default' => 'residential',
                'hint'    => 'حدد نوع استخدام العقار.',
                'help'    => 'حدد نوع استخدام العقار.',
                'options' => [
                    ['value' => 'residential', 'label' => 'سكني'],
                    ['value' => 'commercial', 'label' => 'تجاري'],
                    ['value' => 'industrial', 'label' => 'صناعي'],
                    ['value' => 'agricultural', 'label' => 'زراعي'],
                    ['value' => 'mixed', 'label' => 'متعدد الاستخدامات'],
                ],
            ],

            [
                'key'     => 'has_elevator',
                'label'   => 'يوجد مصعد',
                'type'    => 'checkbox',
                'default' => 0,
                'icon'    => 'fas fa-elevator',
                'hint'    => 'فعّل الخيار إذا كان المبنى يحتوي على مصعد.',
                'help'    => 'فعّل الخيار إذا كان المبنى يحتوي على مصعد.',
            ],

            [
                'key'     => 'has_parking',
                'label'   => 'يوجد موقف سيارات',
                'type'    => 'checkbox',
                'default' => 0,
                'icon'    => 'fas fa-square-parking',
                'hint'    => 'فعّل الخيار إذا كان المبنى يحتوي على مواقف سيارات.',
                'help'    => 'فعّل الخيار إذا كان المبنى يحتوي على مواقف سيارات.',
            ],

            [
                'key'     => 'has_security',
                'label'   => 'توجد حراسة أمنية',
                'type'    => 'checkbox',
                'default' => 0,
                'icon'    => 'fas fa-shield-alt',
                'hint'    => 'فعّل الخيار إذا كانت هناك حراسة أمنية للمبنى.',
                'help'    => 'فعّل الخيار إذا كانت هناك حراسة أمنية للمبنى.',
            ],

            [
                'key'     => 'has_maintenance',
                'label'   => 'توجد خدمات صيانة',
                'type'    => 'checkbox',
                'default' => 0,
                'icon'    => 'fas fa-tools',
                'hint'    => 'فعّل الخيار إذا كانت تتوفر خدمات صيانة للمبنى.',
                'help'    => 'فعّل الخيار إذا كانت تتوفر خدمات صيانة للمبنى.',
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'icon'  => 'fas fa-sticky-note',
                'full'  => true,
                'hint'  => 'أدخل أي معلومات أو ملاحظات إضافية عن المبنى.',
                'help'  => 'أدخل أي معلومات أو ملاحظات إضافية عن المبنى.',
            ],

            [
                'key'    => 'images',
                'label'  => 'صور المبنى',
                'type'   => 'file',
                'icon'   => 'fas fa-images',
                'hint'   => 'يمكنك رفع صور المبنى بصيغ JPG أو JPEG أو PNG أو WEBP.',
                'help'   => 'يمكنك رفع صور المبنى بصيغ JPG أو JPEG أو PNG أو WEBP.',
                'upload' => [
                    'multiple'   => true,
                    'disk'       => 'public',
                    'directory' => 'organization/buildings/images',
                    'mimes'      => ['jpg', 'jpeg', 'png', 'webp'],
                    'max_kb'     => 10240,
                    'delete_old' => false,
                ],
            ],

            [
                'key'    => 'documents',
                'label'  => 'مستندات المبنى',
                'type'   => 'file',
                'icon'   => 'fas fa-file-alt',
                'hint'   => 'ارفع مستندات المبنى مثل الصكوك والعقود والمخططات بالامتدادات المسموح بها.',
                'help'   => 'ارفع مستندات المبنى مثل الصكوك والعقود والمخططات بالامتدادات المسموح بها.',
                'upload' => [
                    'multiple'   => true,
                    'disk'       => 'public',
                    'directory' => 'organization/buildings/documents',
                    'mimes'      => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                    'max_kb'     => 20480,
                    'delete_old' => false,
                ],
            ],

            [
                'key'       => 'is_active',
                'label'     => 'حالة المبنى',
                'type'      => 'select',
                'default'   => 1,
                'is_status' => true,
                'hint'      => 'حدد ما إذا كان المبنى نشطاً أو غير نشط.',
                'help'      => 'حدد ما إذا كان المبنى نشطاً أو غير نشط.',
                'options'   => [
                    ['value' => 1, 'label' => 'نشط'],
                    ['value' => 0, 'label' => 'غير نشط'],
                ],
            ],
        ],

        'filters' => [

            [
                'key'     => 'status',
                'label'   => 'الحالة',
                'type'    => 'select',
                'column'  => 'is_active',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => '1', 'label' => 'نشط'],
                    ['value' => '0', 'label' => 'غير نشط'],
                ],
            ],

            [
                'key'     => 'property_type',
                'label'   => 'نوع العقار',
                'type'    => 'select',
                'column'  => 'property_type',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'residential', 'label' => 'سكني'],
                    ['value' => 'commercial', 'label' => 'تجاري'],
                    ['value' => 'industrial', 'label' => 'صناعي'],
                    ['value' => 'agricultural', 'label' => 'زراعي'],
                    ['value' => 'mixed', 'label' => 'متعدد الاستخدامات'],
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




    /*
    |--------------------------------------------------------------------------
    | أنواع الوحدات
    |--------------------------------------------------------------------------
    */

    'unit_types' => [

        'table'             => 'unit_types',
        'primary_key'       => 'type_id',
        'name_field'        => 'name',
        'has_soft_deletes'  => false,
        'tenant_scoped'     => false,
        'allow_shared'      => true,

        'title'    => 'إدارة أنواع الوحدات',
        'subtitle' => 'إضافة وإدارة أنواع العقارات والوحدات',
        'singular' => 'نوع الوحدة',

        'status' => [
            'column' => 'is_active',
            'type'   => 'boolean',
            'values' => [
                'active'   => 1,
                'inactive' => 0,
            ],
        ],

        'display' => [
            'list'         => true,
            'details'      => true,
            'statistics'   => true,
            'search'       => true,
            'filters'      => true,
            'pagination'   => true,
            'create'       => true,
            'edit'         => true,
            'delete'       => true,
            'status'       => true,
        ],

        'actions' => [
            'create' => true,
            'edit'   => true,
            'delete' => true,
            'view'   => true,
            'status' => true,
        ],

        'search_fields' => [
            'name',
            'name_en',
            'description',
        ],

        'pagination' => [
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        'main_fields' => [

            [
                'key'      => 'name',
                'label'    => 'اسم النوع',
                'type'     => 'text',
                'required' => true,
                'icon'     => 'fas fa-tag',
                'column'   => true,
            ],

            [
                'key'   => 'name_en',
                'label' => 'الاسم بالإنجليزية',
                'type'  => 'text',
                'icon'  => 'fas fa-language',
            ],

            [
                'key'   => 'description',
                'label' => 'الوصف',
                'type'  => 'textarea',
                'icon'  => 'fas fa-align-right',
            ],

            [
                'key'   => 'icon',
                'label' => 'الأيقونة',
                'type'  => 'text',
                'icon'  => 'fas fa-icons',
            ],

            [
                'key'      => 'sort_order',
                'label'    => 'ترتيب العرض',
                'type'     => 'number',
                'default'  => 0,
                'min'      => 0,
                'step'     => 1,
                'icon'     => 'fas fa-sort-numeric-down',
            ],

            [
                'key'     => 'is_active',
                'label'   => 'نشط',
                'type'    => 'checkbox',
                'default' => true,
                'icon'    => 'fas fa-toggle-on',
            ],
        ],

        'filters' => [
            [
                'key'     => 'is_active',
                'label'   => 'حالة النوع',
                'type'    => 'select',
                'column'  => 'is_active',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => '1', 'label' => 'نشط'],
                    ['value' => '0', 'label' => 'غير نشط'],
                ],
            ],
        ],

        'ui' => [
            'list' => [
                'avatar_field'   => 'icon',
                'title_field'    => 'name',
                'subtitle_field' => 'name_en',
                'status_field'   => 'is_active',
            ],

            'details' => [
                'avatar_field' => 'icon',
                'title_field'  => 'name',
                'status_field' => 'is_active',

                'header_fields' => [
                    [
                        'field' => 'name_en',
                        'label' => 'الاسم الإنجليزي',
                        'icon'  => 'fas fa-language',
                    ],
                    [
                        'field' => 'sort_order',
                        'label' => 'ترتيب العرض',
                        'icon'  => 'fas fa-sort',
                    ],
                    [
                        'field' => 'created_at',
                        'label' => 'تاريخ الإنشاء',
                        'icon'  => 'fas fa-calendar',
                    ],
                ],

                'info_fields' => [
                    [
                        'field' => 'description',
                        'label' => 'الوصف',
                        'icon'  => 'fas fa-align-right',
                    ],
                    [
                        'field' => 'is_active',
                        'label' => 'الحالة',
                        'icon'  => 'fas fa-toggle-on',
                    ],
                ],

                'notes_field' => 'description',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | الوحدات العقارية
    |--------------------------------------------------------------------------
    */

    'units' => [

        'table'             => 'units',
        'primary_key'       => 'unit_id',
        'name_field'        => 'unit_number',
        'has_soft_deletes'  => true,
        'deleted_at_column' => 'deleted_at',

        // لا يوجد organization_id في جدول الوحدات.
        // عزل الوحدات يجب أن يتم من خلال building_id.
        'tenant_scoped' => false,
        'allow_shared'  => false,

        'title'    => 'إدارة الوحدات العقارية',
        'subtitle' => 'إدارة الشقق والفلل والمكاتب والمحلات والوحدات التابعة للمباني',
        'singular' => 'الوحدة',

        'status' => [
            'column' => 'status',
            'type'   => 'enum',
            'values' => [
                'available'   => 'متاحة',
                'occupied'    => 'مؤجرة',
                'maintenance' => 'تحت الصيانة',
                'reserved'    => 'محجوزة',
                'closed'      => 'مغلقة',
            ],
        ],

        'display' => [
            'list'       => true,
            'details'    => true,
            'statistics' => true,
            'search'     => true,
            'filters'    => true,
            'pagination' => true,
            'create'     => true,
            'edit'       => true,
            'delete'     => true,
            'status'     => true,
        ],

        'actions' => [
            'create' => true,
            'edit'   => true,
            'delete' => true,
            'view'   => true,
            'status' => true,
        ],

        'search_fields' => [
            'unit_number',
            'floor',
            'notes',
        ],

        'pagination' => [
            'per_page' => 10,
            'options'  => [10, 25, 50, 100],
        ],

        'lookups' => [

            'building_id' => [
                'table'              => 'buildings',
                'key'                => 'building_id',
                'display'            => 'building_name',
                'organization_column' => 'organization_id',
            ],

            'type_id' => [
                'table'   => 'unit_types',
                'key'     => 'type_id',
                'display' => 'name',
            ],
        ],

        'main_fields' => [

            [
                'key'      => 'building_id',
                'label'    => 'المبنى',
                'type'     => 'select',
                'lookup'   => 'building_id',
                'required' => true,
                'icon'     => 'fas fa-building',
            ],

            [
                'key'    => 'type_id',
                'label'  => 'نوع الوحدة',
                'type'   => 'select',
                'lookup' => 'type_id',
                'icon'   => 'fas fa-tags',
            ],

            [
                'key'   => 'unit_number',
                'label' => 'رقم الوحدة',
                'type'  => 'text',
                'icon'  => 'fas fa-hashtag',
            ],

            [
                'key'   => 'floor',
                'label' => 'الطابق',
                'type'  => 'number',
                'min'   => 0,
                'step'  => 1,
                'icon'  => 'fas fa-layer-group',
            ],

            [
                'key'   => 'size',
                'label' => 'المساحة بالمتر المربع',
                'type'  => 'number',
                'min'   => 0,
                'step'  => 'any',
                'icon'  => 'fas fa-ruler-combined',
            ],

            [
                'key'   => 'bedrooms',
                'label' => 'غرف النوم',
                'type'  => 'number',
                'min'   => 0,
                'step'  => 1,
                'default' => 0,
                'icon'  => 'fas fa-bed',
            ],

            [
                'key'   => 'bathrooms',
                'label' => 'دورات المياه',
                'type'  => 'number',
                'min'   => 0,
                'step'  => 1,
                'default' => 0,
                'icon'  => 'fas fa-bath',
            ],

            [
                'key'   => 'halls',
                'label' => 'الصالات',
                'type'  => 'number',
                'min'   => 0,
                'step'  => 1,
                'default' => 0,
                'icon'  => 'fas fa-couch',
            ],

            [
                'key'     => 'kitchen',
                'label'   => 'مطبخ',
                'type'    => 'checkbox',
                'default' => true,
                'icon'    => 'fas fa-utensils',
            ],

            [
                'key'     => 'balcony',
                'label'   => 'شرفة',
                'type'    => 'checkbox',
                'default' => false,
                'icon'    => 'fas fa-door-open',
            ],

            [
                'key'     => 'furnished',
                'label'   => 'مفروشة',
                'type'    => 'checkbox',
                'default' => false,
                'icon'    => 'fas fa-couch',
            ],

            [
                'key'   => 'rent_price',
                'label' => 'قيمة الإيجار',
                'type'  => 'number',
                'min'   => 0,
                'step'  => 'any',
                'icon'  => 'fas fa-money-bill',
            ],

            [
                'key'     => 'status',
                'label'   => 'حالة الوحدة',
                'type'    => 'select',
                'default' => 'available',
                'options' => [
                    ['value' => 'available', 'label' => 'متاحة'],
                    ['value' => 'occupied', 'label' => 'مؤجرة'],
                    ['value' => 'maintenance', 'label' => 'تحت الصيانة'],
                    ['value' => 'reserved', 'label' => 'محجوزة'],
                    ['value' => 'closed', 'label' => 'مغلقة'],
                ],
                'icon' => 'fas fa-info-circle',
            ],

            [
                'key'   => 'notes',
                'label' => 'ملاحظات',
                'type'  => 'textarea',
                'icon'  => 'fas fa-sticky-note',
            ],

            [
                'key'       => 'images',
                'label'     => 'صور الوحدة',
                'type'      => 'file',
                'multiple'  => true,
                'disk'      => 'public',
                'directory' => 'organization/units/images',
                'mimes'     => ['jpg', 'jpeg', 'png', 'webp'],
                'max'       => 10240,
                'delete_old' => false,
                'icon'      => 'fas fa-images',
            ],

            [
                'key'       => 'documents',
                'label'     => 'مستندات الوحدة',
                'type'      => 'file',
                'multiple'  => true,
                'disk'      => 'public',
                'directory' => 'organization/units/documents',
                'mimes'     => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                'max'       => 20480,
                'delete_old' => false,
                'icon'      => 'fas fa-file-alt',
            ],

            [
                'key'   => 'last_maintenance_date',
                'label' => 'آخر صيانة',
                'type'  => 'date',
                'icon'  => 'fas fa-calendar-check',
            ],

            [
                'key'   => 'next_maintenance_date',
                'label' => 'الصيانة القادمة',
                'type'  => 'date',
                'icon'  => 'fas fa-calendar-alt',
            ],
        ],

        'filters' => [

            [
                'key'     => 'status',
                'label'   => 'حالة الوحدة',
                'type'    => 'select',
                'column'  => 'status',
                'options' => [
                    ['value' => 'all', 'label' => 'الكل'],
                    ['value' => 'available', 'label' => 'متاحة'],
                    ['value' => 'occupied', 'label' => 'مؤجرة'],
                    ['value' => 'maintenance', 'label' => 'تحت الصيانة'],
                    ['value' => 'reserved', 'label' => 'محجوزة'],
                    ['value' => 'closed', 'label' => 'مغلقة'],
                ],
            ],

            [
                'key'     => 'type_id',
                'label'   => 'نوع الوحدة',
                'type'    => 'select',
                'column'  => 'type_id',
                'lookup'  => 'type_id',
            ],

            [
                'key'     => 'building_id',
                'label'   => 'المبنى',
                'type'    => 'select',
                'column'  => 'building_id',
                'lookup'  => 'building_id',
            ],
        ],

        'ui' => [

            'list' => [
                'avatar_field'   => 'unit_number',
                'title_field'    => 'unit_number',
                'subtitle_field' => 'floor',
                'subtitle_icon'  => 'fas fa-layer-group',
                'status_field'   => 'status',
            ],

            'details' => [

                'avatar_field' => 'unit_number',
                'title_field'  => 'unit_number',
                'status_field' => 'status',

                'header_fields' => [
                    [
                        'field' => 'building_id',
                        'label' => 'المبنى',
                        'icon'  => 'fas fa-building',
                    ],
                    [
                        'field' => 'type_id',
                        'label' => 'نوع الوحدة',
                        'icon'  => 'fas fa-tags',
                    ],
                    [
                        'field' => 'rent_price',
                        'label' => 'الإيجار',
                        'icon'  => 'fas fa-money-bill',
                    ],
                ],

                'info_fields' => [
                    [
                        'field' => 'floor',
                        'label' => 'الطابق',
                        'icon'  => 'fas fa-layer-group',
                    ],
                    [
                        'field' => 'size',
                        'label' => 'المساحة',
                        'icon'  => 'fas fa-ruler-combined',
                    ],
                    [
                        'field' => 'bedrooms',
                        'label' => 'غرف النوم',
                        'icon'  => 'fas fa-bed',
                    ],
                    [
                        'field' => 'bathrooms',
                        'label' => 'دورات المياه',
                        'icon'  => 'fas fa-bath',
                    ],
                    [
                        'field' => 'halls',
                        'label' => 'الصالات',
                        'icon'  => 'fas fa-couch',
                    ],
                    [
                        'field' => 'kitchen',
                        'label' => 'مطبخ',
                        'icon'  => 'fas fa-utensils',
                    ],
                    [
                        'field' => 'balcony',
                        'label' => 'شرفة',
                        'icon'  => 'fas fa-door-open',
                    ],
                    [
                        'field' => 'furnished',
                        'label' => 'مفروشة',
                        'icon'  => 'fas fa-couch',
                    ],
                    [
                        'field' => 'last_maintenance_date',
                        'label' => 'آخر صيانة',
                        'icon'  => 'fas fa-calendar-check',
                    ],
                    [
                        'field' => 'next_maintenance_date',
                        'label' => 'الصيانة القادمة',
                        'icon'  => 'fas fa-calendar-alt',
                    ],
                ],

                'notes_field' => 'notes',
            ],
        ],
    ],


];
