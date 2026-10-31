<aside class="main-sidebar">

  {{-- Sidebar Brand --}}
  <a href="#" class="brand-link">

      <div class="brand-container">

        <div class="logo-container"
             style="
                width: 45px;
                height: 47px;
                min-width: 55px;
                min-height: 55px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                overflow: hidden;
             ">
        
            <img
                src="{{ asset('assets/logo/favicon.png') }}"
                alt="{{ config('app.name') }}"
                class="brand-image"
                style="
                    width: 59px;
                    height: 59px;
                    min-width: 59px;
                    min-height: 59px;
                    object-fit: cover;
                    display: block;
                "
            >
        
        </div>
      
        

          <div class="brand-text-container">
              <span class="brand-text">
                  {{ config('app.name') }}
              </span>

              <small class="brand-context">
                  Global Admin
              </small>
          </div>

      </div>

  </a>


  {{-- Sidebar Navigation --}}
  <div class="sidebar">

      <nav class="mt-2">

          <ul
              class="nav nav-pills nav-sidebar flex-column"
              data-widget="treeview"
              role="menu"
              data-accordion="false"
          >

              {{-- ================================================= --}}
              {{-- الرئيسية --}}
              {{-- ================================================= --}}

              <li class="nav-header">
                  الرئيسية
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>لوحة التحكم</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-chart-bar"></i>
                      <p>التحليلات والإحصاءات</p>
                  </a>
              </li>


              {{-- ================================================= --}}
              {{-- إدارة المنصة --}}
              {{-- ================================================= --}}

              <li class="nav-header">
                  إدارة المنصة
              </li>


              {{-- المؤسسات --}}
              <li class="nav-item has-treeview">

                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-building"></i>

                      <p>
                          المؤسسات
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>

                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="{{ route('admin.organizations.index') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع المؤسسات</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{ route('admin.organizations.create') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>إضافة مؤسسة</p>
                          </a>
                      </li>
                      <li class="nav-item">
                        <a href="{{ route('admin.organizations.trashed') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>  المؤسسات المحذوفه </p>
                        </a>
                    </li>

                      <li class="nav-item">
                          <a href="{{ route('admin.organizations.documents') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>مراجعة مستندات المؤسسات</p>
                          </a>
                      </li>

                  </ul>

              </li>


              {{-- الباقات والاشتراكات --}}
              <li class="nav-item has-treeview">

                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-box"></i>

                      <p>
                          الباقات والاشتراكات
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>

                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع الباقات</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>إضافة باقة</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>الباقات المشترَك عليها</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع الاشتراكات</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>

                              <p>
                                  طلبات الاشتراك
                              </p>
                          </a>
                      </li>

                  </ul>

              </li>


              {{-- ================================================= --}}
              {{-- الإدارة المالية --}}
              {{-- ================================================= --}}

              <li class="nav-header">
                  الإدارة المالية
              </li>

              <li class="nav-item">
                  <a href="{{ route('admin.invoices.index') }}" class="nav-link">
                      <i class="nav-icon fas fa-file-invoice"></i>
                      <p>جميع الفواتير</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="{{ route('admin.payments.index') }}" class="nav-link">
                      <i class="nav-icon fas fa-credit-card"></i>
                      <p>جميع المدفوعات</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="{{ route('admin.cashboxes.index') }}" class="nav-link">
                      <i class="nav-icon fas fa-wallet"></i>
                      <p>الصناديق المالية</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-file-import"></i>
                      <p>سندات القبض</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-file-export"></i>
                      <p>سندات الصرف</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-exchange-alt"></i>
                      <p>الحركات المالية</p>
                  </a>
              </li>


              {{-- ================================================= --}}
              {{-- إدارة الوصول --}}
              {{-- ================================================= --}}

              <li class="nav-header">
                  إدارة الوصول
              </li>


              {{-- المستخدمون والوصول --}}
              <li class="nav-item has-treeview">

                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-users-cog"></i>

                      <p>
                          المستخدمون والوصول
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>

                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="{{ route('admin.users.index') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع المستخدمين</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{ route('admin.users.create') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>إضافة مستخدم</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{ route('admin.access_contexts.index') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>سياقات الوصول</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{ route('admin.user_assignments.index') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>تعيينات المستخدمين</p>
                          </a>
                      </li>

                  </ul>

              </li>


              {{-- الأدوار والصلاحيات --}}
              <li class="nav-item has-treeview">

                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-user-shield"></i>

                      <p>
                          الأدوار والصلاحيات
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>

                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="{{ route('admin.roles.index') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع الأدوار</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>إضافة دور</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{ route('admin.permissions.index') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع الصلاحيات</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>استثناءات صلاحيات المستخدمين</p>
                          </a>
                      </li>

                  </ul>

              </li>


              {{-- ================================================= --}}
              {{-- الحوكمة والمراقبة --}}
              {{-- ================================================= --}}

              <li class="nav-header">
                  الحوكمة والمراقبة
              </li>


              {{-- الإجراءات والتدقيق --}}
              <li class="nav-item has-treeview">

                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-history"></i>

                      <p>
                          الإجراءات والتدقيق
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>

                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع الإجراءات</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>أنواع الإجراءات</p>
                          </a>
                      </li>

                
<li class="nav-item">
    <a href="{{ route('organizations.dashboard') }}" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>سجل التدقيق</p>
    </a>
</li>


                  </ul>

              </li>


              {{-- الإشعارات --}}
              <li class="nav-item has-treeview">

                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-bell"></i>

                      <p>
                          الإشعارات
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>

                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>جميع الإشعارات</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>إشعارات غير مقروءة</p>
                          </a>
                      </li>

                  </ul>

              </li>


              {{-- ================================================= --}}
              {{-- إعدادات المنصة --}}
              {{-- ================================================= --}}

              <li class="nav-header">
                  إعدادات المنصة
              </li>

              <li class="nav-item">
                  <a href="{{ route('admin.settings.actions') }}" class="nav-link">
                      <i class="nav-icon fas fa-cog"></i>
                      <p>إعدادات النظام</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-shield-alt"></i>
                      <p>إعدادات الأمان</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-envelope"></i>
                      <p>إعدادات البريد والإشعارات</p>
                  </a>
              </li>


              {{-- ================================================= --}}
              {{-- حسابي --}}
              {{-- ================================================= --}}

              <li class="nav-header">
                  حسابي
              </li>

              <li class="nav-item">
                  <a href="{{ route('admin.profile.show') }}" class="nav-link">
                      <i class="nav-icon fas fa-user-circle"></i>
                      <p>الملف الشخصي</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-user-cog"></i>
                      <p>إعدادات الحساب</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-lock"></i>
                      <p>الأمان</p>
                  </a>
              </li>


              {{-- ================================================= --}}
              {{-- تسجيل الخروج --}}
              {{-- ================================================= --}}

              <li class="nav-item mt-2">

                  <form action="#" method="POST">
                      @csrf

                      <button
                          type="submit"
                          class="nav-link border-0 bg-transparent w-100 text-right"
                      >
                          <i class="nav-icon fas fa-sign-out-alt"></i>
                          <p>تسجيل الخروج</p>
                      </button>

                  </form>

              </li>

          </ul>

      </nav>

  </div>

</aside>