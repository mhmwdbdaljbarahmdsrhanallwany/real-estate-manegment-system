<aside class="main-sidebar" style="background: #ffffff; box-shadow: 2px 0 10px rgba(0,0,0,0.05);">
  
  <!-- ============================================ -->
  <!-- 🏢 Brand / Logo -->
  <!-- ============================================ -->
  <div class="container_img brand-container" style="padding: 15px 10px; text-align: center; border-bottom: 2px solid #e9ecef;">
    <div class="logo-circle" style="display: inline-block; width: 65px; height: 65px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 8px; margin-bottom: 8px;">
      <img src="dist/img/AdminLTELogo.png" alt="Logo" style="width: 100%; height: 100%; border-radius: 50%; background: white; padding: 5px;">
    </div>
    <a href="index.html" class="brand-text-link" style="display: block; text-decoration: none;">
      <span class="brand-text" style="color: #2c3e50; font-size: 18px; font-weight: 700; display: block;">نظام إدارة العقارات</span>
      <small style="color: #6c757d; font-size: 12px;">لوحة تحكم المؤسسة العقارية</small>
    </a>
  </div>

  <!-- ============================================ -->
  <!-- 📊 معلومات المؤسسة العقارية -->
  <!-- ============================================ -->
  <div class="sidebar" style="padding: 10px 0;">
    <a wire:navigate href="">
      <div class="agency-info" style="padding: 0 15px 15px 15px; border-bottom: 2px solid #f1f3f5; margin-bottom: 10px;">
      <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 12px; border-radius: 10px;">
        <div class="d-flex align-items-center">
          <div class="agency-avatar" style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: bold; margin-left: 10px;">
            م ع
          </div>
          <div class="agency-details" style="flex: 1;">
            <h6 style="margin: 0; font-size: 13px; font-weight: 600; color: #2c3e50;">
              مؤسسة العقارات المتحدة
            </h6>
            <small style="color: #6c757d; font-size: 10px;">
              <i class="fas fa-building"></i> الرياض - السجل التجاري: 123456789
            </small>
            <br>
            <span class="badge" style="background: #28a745; color: white; font-size: 9px; padding: 2px 8px; border-radius: 20px;">
              🟢 باقة احترافية - نشط
            </span>
            <span class="badge" style="background: #17a2b8; color: white; font-size: 9px; padding: 2px 8px; border-radius: 20px;">
              <i class="far fa-calendar-alt"></i> ينتهي الاشتراك بعد 25 يوم
            </span>
          </div>
        </div>
      </div>
    </div>
    </a>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- ================================================================ -->
        <!-- 📊 القسم الأول: لوحة التحكم والرئيسية -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #667eea; font-size: 13px; font-weight: 600; padding: 10px 15px 5px 15px; letter-spacing: 0.5px;">
          <i class="fas fa-grip-lines"></i> الرئيسية ولوحة التحكم
        </li>
        
        <li class="nav-item">
          <a href="dashboard.html" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>لوحة التحكم الرئيسية</p>
            <span class="badge badge-info right">نظرة عامة</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="analytics.html" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>التحليلات والإحصائيات</p>
            <span class="badge badge-success right">متقدم</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="notifications.html" class="nav-link">
            <i class="nav-icon fas fa-bell"></i>
            <p>الإشعارات</p>
            <span class="badge badge-danger right">5</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="tasks.html" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>المهام اليومية</p>
            <span class="badge badge-warning right">3</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="calendar.html" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>التقويم العقاري</p>
            <span class="badge badge-primary right">مواعيد</span>
          </a>
        </li>

        <!-- ================================================================ -->
        <!-- 🏢 القسم الثاني: إدارة العقارات والملاك -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #28a745; font-size: 13px; font-weight: 600; padding: 15px 15px 5px 15px; letter-spacing: 0.5px; border-top: 1px solid #e9ecef; margin-top: 10px;">
          <i class="fas fa-home"></i> إدارة العقارات والملاك
        </li>

        <!-- ===== الملاك ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>الملاك <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-primary right"></span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a wire:navigate href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع الملاك</p>
              </a>
            </li>
            <li class="nav-item">
              <a wire:navigate href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إضافة مالك جديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a  wire:navigate href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>وكلاء الملاك</p>
              </a>
            </li>
            <li class="nav-item">
              <a wire:navigate href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الحسابات البنكية للملاك</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="owners-reports.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقارير الملاك</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="owners-blacklist.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الملاك المحظورين</p>
                <span class="badge badge-danger right">  </span>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== المباني ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-city"></i>
            <p>المباني والعمارات <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-success right">8</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="buildings.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع المباني</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="buildings-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إضافة مبنى جديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="buildings-map.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الخريطة العقارية</p>
                <span class="badge badge-info right">جديد</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="buildings-documents.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>مستندات المباني (صكوك - رخص)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="buildings-owners.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>توزيع الملاك على المباني</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== أنواع الوحدات ===== -->
        <li class="nav-item">
          <a href="unit-types.html" class="nav-link">
            <i class="nav-icon fas fa-tag"></i>
            <p>أنواع الوحدات العقارية</p>
            <span class="badge badge-secondary right">11</span>
          </a>
        </li>

        <!-- ===== الوحدات ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-door-open"></i>
            <p>الوحدات السكنية والتجارية <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-primary right">45</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="units.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع الوحدات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="units-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إضافة وحدة جديدة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="units-available.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الوحدات المتاحة للإيجار</p>
                <span class="badge badge-success right">12</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="units-occupied.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الوحدات المؤجرة</p>
                <span class="badge badge-primary right">28</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="units-maintenance.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>وحدات تحت الصيانة</p>
                <span class="badge badge-warning right">5</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="units-residents.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>السكان (أفراد الأسرة)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="units-photos.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>معرض صور الوحدات</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ================================================================ -->
        <!-- 👥 القسم الثالث: المستأجرين والعقود -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #17a2b8; font-size: 13px; font-weight: 600; padding: 15px 15px 5px 15px; letter-spacing: 0.5px; border-top: 1px solid #e9ecef; margin-top: 10px;">
          <i class="fas fa-users"></i> المستأجرين والعقود
        </li>

        <!-- ===== المستأجرين ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>المستأجرين <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-primary right">30</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="tenants.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع المستأجرين</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="tenants-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إضافة مستأجر جديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="tenants-residents.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>أفراد الأسرة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="tenants-blacklist.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>القائمة السوداء</p>
                <span class="badge badge-danger right">3</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="tenants-history.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>سجل العقود السابقة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="tenants-ratings.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقييمات المستأجرين</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== العقود ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-signature"></i>
            <p>عقود الإيجار <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-info right">25</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="contracts.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع العقود</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="contracts-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إنشاء عقد جديد</p>
                <span class="badge badge-success right">+ جديد</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="contracts-active.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>العقود النشطة</p>
                <span class="badge badge-success right">18</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="contracts-expiring.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>عقود على وشك الانتهاء</p>
                <span class="badge badge-warning right">4</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="contracts-expired.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>العقود المنتهية</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="contracts-renewals.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>طلبات التجديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="contracts-terminations.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>طلبات الإنهاء المبكر</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="guarantors.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الضامنين</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ================================================================ -->
        <!-- 💰 القسم الرابع: النظام المالي والمحاسبة -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #ffc107; font-size: 13px; font-weight: 600; padding: 15px 15px 5px 15px; letter-spacing: 0.5px; border-top: 1px solid #e9ecef; margin-top: 10px;">
          <i class="fas fa-coins"></i> النظام المالي والمحاسبة
        </li>

        <!-- ===== الفواتير ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>الفواتير <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-danger right">6</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="invoices.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع الفواتير</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="invoices-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إنشاء فاتورة جديدة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="invoices-rent.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>فواتير الإيجار</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="invoices-utilities.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>فواتير المرافق (كهرباء - مياه - غاز)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="invoices-late-fees.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>غرامات التأخير</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="invoices-unpaid.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الفواتير غير المدفوعة</p>
                <span class="badge badge-danger right">6</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="invoices-tax.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>التقارير الضريبية (الزكاة والضريبة)</p>
                <span class="badge badge-info right">حكومي</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== المدفوعات ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>المدفوعات والإيرادات <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="payments.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>سجل المدفوعات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="payments-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تسجيل دفعة جديدة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="payments-reports.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقارير الإيرادات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="payments-owners.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تحويلات الملاك</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== التأمينات (الإيداعات) ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shield-alt"></i>
            <p>التأمينات <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="deposits.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع التأمينات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="deposits-rules.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>سياسات التأمين</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="deposits-unpaid.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تأمينات غير مدفوعة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="deposits-refunded.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>التأمينات المستردة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="deposits-deducted.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>استقطاعات من التأمين (أضرار)</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ================================================================ -->
        <!-- 🔧 القسم الخامس: الصيانة والخدمات -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #6c757d; font-size: 13px; font-weight: 600; padding: 15px 15px 5px 15px; letter-spacing: 0.5px; border-top: 1px solid #e9ecef; margin-top: 10px;">
          <i class="fas fa-tools"></i> الصيانة والخدمات
        </li>

        <!-- ===== طلبات الصيانة ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>طلبات الصيانة <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-warning right">7</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="maintenance.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع الطلبات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenance-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>طلب صيانة جديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenance-pending.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>طلبات معلقة</p>
                <span class="badge badge-warning right">4</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenance-progress.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>قيد التنفيذ</p>
                <span class="badge badge-info right">2</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenance-completed.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الطلبات المكتملة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenance-emergency.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الطلبات الطارئة</p>
                <span class="badge badge-danger right">1</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenance-assign.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تعيين الفنيين</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenance-costs.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تكاليف الصيانة</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== التسليم والاستلام ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-handshake"></i>
            <p>التسليم والاستلام <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="handover-delivery.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>طلبات التسليم (للمستأجرين)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="handover-return.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>طلبات الاستلام (من المستأجرين)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="inspections.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>فحص الوحدات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="inspections-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تسجيل فحص جديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="damages.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الأضرار</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="handover-items.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الأغراض والمحتويات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="handover-contract-items.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الأغراض المسلمة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="handover-return-items.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الأغراض المعادة</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ================================================================ -->
        <!-- 📊 القسم السادس: التقارير -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #6610f2; font-size: 13px; font-weight: 600; padding: 15px 15px 5px 15px; letter-spacing: 0.5px; border-top: 1px solid #e9ecef; margin-top: 10px;">
          <i class="fas fa-chart-pie"></i> التقارير والتحليلات
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>التقارير العقارية <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="reports-contracts.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقرير العقود</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-payments.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقرير المدفوعات والإيرادات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-occupancy.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقرير الإشغال (نسبة التأجير)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-maintenance.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقرير الصيانة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-financial.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>التقارير المالية (الأرباح والخسائر)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-owners.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقارير الملاك</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-saved.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>التقارير المحفوظة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-schedule.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جدولة التقارير</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="reports-export.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>سجل التصدير</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ================================================================ -->
        <!-- ⚡ القسم السابع: المرافق -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #dc3545; font-size: 13px; font-weight: 600; padding: 15px 15px 5px 15px; letter-spacing: 0.5px; border-top: 1px solid #e9ecef; margin-top: 10px;">
          <i class="fas fa-cogs"></i> المرافق (كهرباء - مياه - غاز)
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tint"></i>
            <p>قراءات المرافق <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="utilities-meter.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>قراءات العدادات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="utilities-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تسجيل قراءة جديدة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="utilities-water.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>قراءات المياه</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="utilities-electricity.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>قراءات الكهرباء</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="utilities-gas.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>قراءات الغاز</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="utilities-consumption.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقارير الاستهلاك</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="utilities-billing.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>فوترة المرافق</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ================================================================ -->
        <!-- 👤 القسم الثامن: حسابي والإعدادات -->
        <!-- ================================================================ -->
        <li class="nav-header" style="color: #343a40; font-size: 13px; font-weight: 600; padding: 15px 15px 5px 15px; letter-spacing: 0.5px; border-top: 1px solid #e9ecef; margin-top: 10px;">
          <i class="fas fa-user-cog"></i> حسابي وإعدادات المؤسسة
        </li>

        <!-- ===== حساب المستخدم ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-circle"></i>
            <p>حسابي <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="profile.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الملف الشخصي</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="profile-edit.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تعديل الملف الشخصي</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="profile-password.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تغيير كلمة المرور</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="profile-2fa.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>التحقق بخطوتين (2FA)</p>
                <span class="badge badge-info right">أمان</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="profile-sessions.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الجلسات النشطة</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="profile-notifications.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تفضيلات الإشعارات</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== إعدادات المؤسسة ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>إعدادات المؤسسة <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="settings-general.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الإعدادات العامة (بيانات المؤسسة)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings-billing.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إعدادات الفواتير والضرائب</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings-security.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إعدادات الأمان</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings-email.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إعدادات البريد الإلكتروني</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings-notifications.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إعدادات الإشعارات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings-subscription.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الاشتراك والباقة</p>
                <span class="badge badge-warning right">احترافية</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- ===== إدارة المستخدمين ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>المستخدمين <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-primary right">8</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="users.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع المستخدمين</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="users-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إضافة مستخدم جديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="roles.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>الأدوار والصلاحيات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="permissions.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إدارة الصلاحيات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="users-activity.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>سجل نشاط المستخدمين</p>
              </a>
            </li>
          </ul>
        </li>
<li class="nav-item">

    <form
        method="POST"
        action="{{ route('organizations.logout', [
            'subdomain' => request()->route('subdomain')
        ]) }}"
        style="margin: 0;"
    >
        @csrf

        <button
            type="submit"
            class="nav-link"
            style="
                width: 100%;
                border: none;
                background: transparent;
                color: #dc3545;
                text-align: right;
                cursor: pointer;
                padding: 10px 15px;
            "
        >
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>تسجيل الخروج</p>
        </button>

    </form>

</li>
        <!-- ===== الفروع ===== -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-store"></i>
            <p>فروع المؤسسة <i class="right fas fa-angle-left"></i></p>
            <span class="badge badge-success right">3</span>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="branches.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>جميع الفروع</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="branches-create.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>إضافة فرع جديد</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="branches-managers.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>مديري الفروع</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="branches-reports.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>تقارير الفروع</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- ================================================================ -->
        <!-- 🚪 تسجيل الخروج -->
        <!-- ================================================================ -->
       <li class="nav-item">

    <form
        method="POST"
        action="{{ route('organizations.logout', [
            'subdomain' => request()->route('subdomain')
        ]) }}"
        style="margin: 0;"
    >
        @csrf

        <button
            type="submit"
            class="nav-link"
            style="
                width: 100%;
                border: none;
                background: transparent;
                color: #dc3545;
                text-align: right;
                cursor: pointer;
                padding: 10px 15px;
            "
        >
            <i class="nav-icon fas fa-sign-out-alt"></i>

            <p>تسجيل الخروج</p>

        </button>

    </form>

</li>

        <li class="nav-item">
          <a href="login.html" class="nav-link" style="color: #dc3545;">
            <i class="nav-icon fas fa-sign-out-alt" style="color: #dc3545;"></i>
            <p>تسجيل الخروج من النظام</p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>