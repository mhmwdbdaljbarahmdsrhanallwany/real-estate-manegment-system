<aside class="main-sidebar" style="background: #ffffff">
  <!-- Brand Logo -->
  <div class="container_img brand-container">
    <div class="logo-circle">
      <img src="dist/img/AdminLTELogo.png" alt="Logo">
    </div>
    <a href="#" class="brand-text-link">
      <span class="brand-text" style="color: blue">نظام إدارة العقارات</span>
    </a>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- ============================================ -->
        <!--الرئيسية (Dashboard) -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #17a2b8; font-size: 14px;">الرئيسية</li>
        
        <li class="nav-item">
         <a href="{{ route('admin.home') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>لوحة التحكم الرئيسية</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="analytics" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>التحليلات والإحصائيات</p>
          </a>
        </li>

        <!-- ============================================ -->
        <!-- 🏢 المستوى الأول: النظام الأساسي (Core System) -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #28a745; font-size: 14px;">النظام الأساسي</li>

        <!-- المؤسسات العقارية -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>المؤسسات العقارية <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="{{ route('admin.agencies.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع المؤسسات</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.agencies.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة مؤسسة جديدة</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.agencies.verification') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>طلبات التوثيق</p><span class="badge badge-warning right">جديد</span></a></li>
            <li class="nav-item"><a href="{{ route('admin.subscription.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p> باقات الاشتراك</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.agencysubscription.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>اشتراكات المؤسسات</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.agencies.documents') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>مستندات المؤسسات</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.agencies.access_history') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>سجل عمليات المؤسسات</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.agencies.blocked') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>المؤسسات المحظورة</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.agencysubscription.subscription_entities') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>المؤسسات والباقات قيد الاشتراك  </p></a></li>

          </ul>
        </li>

        <!-- المستخدمين والصلاحيات -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>المستخدمين والصلاحيات <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع المستخدمين</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.users.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة مستخدم جديد</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.roles.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الأدوار والصلاحيات</p></a></li>
            <li class="nav-item"><a href="permissions.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إدارة الصلاحيات</p></a></li>
            <li class="nav-item"><a href="user-sessions" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الجلسات النشطة</p></a></li>
            <li class="nav-item"><a href="audit-logs" class="nav-link"><i class="far fa-circle nav-icon"></i><p>سجل التدقيق</p></a></li>
            <li class="nav-item"><a href="blacklisted-tokens" class="nav-link"><i class="far fa-circle nav-icon"></i><p>التوكنات المحظورة</p></a></li>
          </ul>
        </li>

        <!-- الملاك -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>الملاك <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="owners.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع الملاك</p></a></li>
            <li class="nav-item"><a href="owners.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة مالك جديد</p></a></li>
            <li class="nav-item"><a href="owners.agents" class="nav-link"><i class="far fa-circle nav-icon"></i><p>وكلاء الملاك</p></a></li>
            <li class="nav-item"><a href="owners.bank-accounts" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الحسابات البنكية للملاك</p></a></li>
            <li class="nav-item"><a href="owners.reports" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقارير الملاك</p></a></li>
            <li class="nav-item"><a href="owners.blacklist" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الملاك المحظورين</p></a></li>
          </ul>
        </li>

        <!-- المباني والوحدات -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-city"></i>
            <p>المباني والوحدات <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="buildings.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع المباني</p></a></li>
            <li class="nav-item"><a href="buildings.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة مبنى جديد</p></a></li>
            <li class="nav-item"><a href="units.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع الوحدات</p></a></li>
            <li class="nav-item"><a href="units.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة وحدة جديدة</p></a></li>
            <li class="nav-item"><a href="unit-types.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>أنواع الوحدات</p></a></li>
            <li class="nav-item"><a href="units.map" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الخريطة العقارية</p></a></li>
            <li class="nav-item"><a href="units.availability" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الوحدات المتاحة</p><span class="badge badge-success right">متاحة</span></a></li>
            <li class="nav-item"><a href="units.maintenance" class="nav-link"><i class="far fa-circle nav-icon"></i><p>وحدات تحت الصيانة</p></a></li>
          </ul>
        </li>

        <!-- ============================================ -->
        <!-- 📋 المستوى الثاني: النظام التشغيلي (Operational System) -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #17a2b8; font-size: 14px;">النظام التشغيلي</li>

        <!-- المستأجرين -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>المستأجرين <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="tenants.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع المستأجرين</p></a></li>
            <li class="nav-item"><a href="tenants.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة مستأجر جديد</p></a></li>
            <li class="nav-item"><a href="tenants.residents" class="nav-link"><i class="far fa-circle nav-icon"></i><p>أفراد الأسرة (السكان)</p></a></li>
            <li class="nav-item"><a href="tenants.blacklist" class="nav-link"><i class="far fa-circle nav-icon"></i><p>القائمة السوداء</p><span class="badge badge-danger right">محظورون</span></a></li>
            <li class="nav-item"><a href="tenants.contracts-history" class="nav-link"><i class="far fa-circle nav-icon"></i><p>سجل عقود المستأجرين</p></a></li>
            <li class="nav-item"><a href="tenants.ratings" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقييمات المستأجرين</p></a></li>
          </ul>
        </li>

        <!-- العقود -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-signature"></i>
            <p>العقود <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="contracts.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع العقود</p></a></li>
            <li class="nav-item"><a href="contracts.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إنشاء عقد جديد</p></a></li>
            <li class="nav-item"><a href="contracts.active" class="nav-link"><i class="far fa-circle nav-icon"></i><p>العقود النشطة</p><span class="badge badge-success right">نشطة</span></a></li>
            <li class="nav-item"><a href="contracts.expiring" class="nav-link"><i class="far fa-circle nav-icon"></i><p>عقود على وشك الانتهاء</p><span class="badge badge-warning right">تنبيه</span></a></li>
            <li class="nav-item"><a href="contracts.expired" class="nav-link"><i class="far fa-circle nav-icon"></i><p>العقود المنتهية</p></a></li>
            <li class="nav-item"><a href="contracts.renewals" class="nav-link"><i class="far fa-circle nav-icon"></i><p>طلبات التجديد</p></a></li>
            <li class="nav-item"><a href="contracts.terminations" class="nav-link"><i class="far fa-circle nav-icon"></i><p>طلبات الإنهاء</p></a></li>
            <li class="nav-item"><a href="guarantors.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الضامنين</p></a></li>
          </ul>
        </li>

        <!-- الصيانة -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>الصيانة <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="maintenance.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع طلبات الصيانة</p></a></li>
            <li class="nav-item"><a href="maintenance.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>طلب صيانة جديد</p></a></li>
            <li class="nav-item"><a href="maintenance.pending" class="nav-link"><i class="far fa-circle nav-icon"></i><p>طلبات معلقة</p><span class="badge badge-warning right">قيد الانتظار</span></a></li>
            <li class="nav-item"><a href="maintenance.in-progress" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قيد التنفيذ</p></a></li>
            <li class="nav-item"><a href="maintenance.completed" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الطلبات المكتملة</p></a></li>
            <li class="nav-item"><a href="maintenance.emergency" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الطلبات الطارئة</p><span class="badge badge-danger right">طارئ</span></a></li>
            <li class="nav-item"><a href="maintenance.assign" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تعيين الفنيين</p></a></li>
          </ul>
        </li>

        <!-- التسليم والاستلام والفحص -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-handshake"></i>
            <p>التسليم والاستلام <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="handover.delivery" class="nav-link"><i class="far fa-circle nav-icon"></i><p>طلبات التسليم</p></a></li>
            <li class="nav-item"><a href="handover.return" class="nav-link"><i class="far fa-circle nav-icon"></i><p>طلبات الاستلام</p></a></li>
            <li class="nav-item"><a href="inspections.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>فحص الوحدات</p></a></li>
            <li class="nav-item"><a href="inspections.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تسجيل فحص جديد</p></a></li>
            <li class="nav-item"><a href="damages.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الأضرار</p></a></li>
            <li class="nav-item"><a href="handover-items.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الأغراض والمحتويات</p></a></li>
            <li class="nav-item"><a href="handover.contract-items" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الأغراض المسلمة للمستأجرين</p></a></li>
          </ul>
        </li>

        <!-- ============================================ -->
       💰<!-- المستوى الثالث: النظام المالي (Financial System) -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #ffc107; font-size: 14px;">النظام المالي</li>

        <!-- الفواتير والمدفوعات -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>الفواتير والمدفوعات <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="invoices.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع الفواتير</p></a></li>
            <li class="nav-item"><a href="invoices.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إنشاء فاتورة جديدة</p></a></li>
            <li class="nav-item"><a href="invoices.rent" class="nav-link"><i class="far fa-circle nav-icon"></i><p>فواتير الإيجار</p></a></li>
            <li class="nav-item"><a href="invoices.utilities" class="nav-link"><i class="far fa-circle nav-icon"></i><p>فواتير المرافق</p></a></li>
            <li class="nav-item"><a href="invoices.late-fees" class="nav-link"><i class="far fa-circle nav-icon"></i><p>غرامات التأخير</p></a></li>
            <li class="nav-item"><a href="invoices.unpaid" class="nav-link"><i class="far fa-circle nav-icon"></i><p>الفواتير غير المدفوعة</p><span class="badge badge-danger right">متأخرة</span></a></li>
            <li class="nav-item"><a href="payments.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>سجل المدفوعات</p></a></li>
            <li class="nav-item"><a href="payments.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تسجيل دفعة جديدة</p></a></li>
            <li class="nav-item"><a href="payments.reports" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقارير الإيرادات</p></a></li>
          </ul>
        </li>

        <!-- التأمينات والإيداعات -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shield-alt"></i>
            <p>التأمينات والإيداعات <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="deposits.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع التأمينات</p></a></li>
            <li class="nav-item"><a href="deposits.rules" class="nav-link"><i class="far fa-circle nav-icon"></i><p>سياسات التأمين</p></a></li>
            <li class="nav-item"><a href="deposits.unpaid" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تأمينات غير مدفوعة</p></a></li>
            <li class="nav-item"><a href="deposits.refunded" class="nav-link"><i class="far fa-circle nav-icon"></i><p>التأمينات المستردة</p></a></li>
            <li class="nav-item"><a href="deposits.deducted" class="nav-link"><i class="far fa-circle nav-icon"></i><p>استقطاعات من التأمين</p></a></li>
          </ul>
        </li>

        <!-- الاشتراكات الشهرية -->
        <li class="nav-item">
          <a href="subscriptions.agency" class="nav-link">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>اشتراكات المؤسسات الشهرية</p>
          </a>
        </li>

        <!-- ============================================ -->
        <!-- 🔔 المستوى الرابع: النظام الداعم (Support System) -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #6c757d; font-size: 14px;">النظام الداعم</li>

        <!-- الفروع -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-store"></i>
            <p>الفروع <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="branches.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع الفروع</p></a></li>
            <li class="nav-item"><a href="branches.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إضافة فرع جديد</p></a></li>
            <li class="nav-item"><a href="branches.managers" class="nav-link"><i class="far fa-circle nav-icon"></i><p>مديري الفروع</p></a></li>
            <li class="nav-item"><a href="branches.reports" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقارير الفروع</p></a></li>
          </ul>
        </li>

        <!-- الإشعارات -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-bell"></i>
            <p>الإشعارات <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="notifications.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع الإشعارات</p></a></li>
            <li class="nav-item"><a href="notifications.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إرسال إشعار جديد</p></a></li>
            <li class="nav-item"><a href="notifications.templates" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قوالب الإشعارات</p></a></li>
            <li class="nav-item"><a href="notifications.reminders" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تذكيرات التحصيل</p></a></li>
            <li class="nav-item"><a href="notifications.gateways" class="nav-link"><i class="far fa-circle nav-icon"></i><p>بوابات الإرسال</p></a></li>
          </ul>
        </li>

        <!-- التقارير -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>التقارير <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="reports.contracts" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقرير العقود</p></a></li>
            <li class="nav-item"><a href="reports.payments" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقرير المدفوعات</p></a></li>
            <li class="nav-item"><a href="reports.occupancy" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقرير الإشغال</p></a></li>
            <li class="nav-item"><a href="reports.maintenance" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقرير الصيانة</p></a></li>
            <li class="nav-item"><a href="reports.financial" class="nav-link"><i class="far fa-circle nav-icon"></i><p>التقارير المالية</p></a></li>
            <li class="nav-item"><a href="reports.saved" class="nav-link"><i class="far fa-circle nav-icon"></i><p>التقارير المحفوظة</p></a></li>
            <li class="nav-item"><a href="reports.schedule" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جدولة التقارير</p></a></li>
            <li class="nav-item"><a href="reports.export-logs" class="nav-link"><i class="far fa-circle nav-icon"></i><p>سجل التصدير</p></a></li>
          </ul>
        </li>

        <!-- المهام المجدولة -->
        <li class="nav-item">
          <a href="scheduled-jobs.index" class="nav-link">
            <i class="nav-icon fas fa-clock"></i>
            <p>المهام المجدولة</p>
          </a>
        </li>

        <!-- الدعم الفني -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-headset"></i>
            <p>الدعم الفني <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="tickets.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع التذاكر</p></a></li>
            <li class="nav-item"><a href="tickets.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تذكرة جديدة</p></a></li>
            <li class="nav-item"><a href="tickets.open" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تذاكر مفتوحة</p><span class="badge badge-warning right">مفتوحة</span></a></li>
            <li class="nav-item"><a href="tickets.resolved" class="nav-link"><i class="far fa-circle nav-icon"></i><p>التذاكر المحلولة</p></a></li>
          </ul>
        </li>

        <!-- ============================================ -->
        <!-- ⚡ المستوى الخامس: النظام المتقدم (Advanced System) -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #6610f2; font-size: 14px;">النظام المتقدم</li>

        <!-- المرافق (كهرباء/مياه/غاز) -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tint"></i>
            <p>المرافق <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="utilities.meter-readings" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قراءات العدادات</p></a></li>
            <li class="nav-item"><a href="utilities.create-reading" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تسجيل قراءة جديدة</p></a></li>
            <li class="nav-item"><a href="utilities.water" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قراءات المياه</p></a></li>
            <li class="nav-item"><a href="utilities.electricity" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قراءات الكهرباء</p></a></li>
            <li class="nav-item"><a href="utilities.gas" class="nav-link"><i class="far fa-circle nav-icon"></i><p>قراءات الغاز</p></a></li>
            <li class="nav-item"><a href="utilities.consumption-reports" class="nav-link"><i class="far fa-circle nav-icon"></i><p>تقارير الاستهلاك</p></a></li>
          </ul>
        </li>

        <!-- النسخ الاحتياطي -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-database"></i>
            <p>النسخ الاحتياطي <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="backup.index" class="nav-link"><i class="far fa-circle nav-icon"></i><p>جميع النسخ الاحتياطية</p></a></li>
            <li class="nav-item"><a href="backup.create" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إنشاء نسخة احتياطية</p></a></li>
            <li class="nav-item"><a href="backup.restore" class="nav-link"><i class="far fa-circle nav-icon"></i><p>استعادة البيانات</p></a></li>
            <li class="nav-item"><a href="backup.settings" class="nav-link"><i class="far fa-circle nav-icon"></i><p>إعدادات النسخ الاحتياطي</p></a></li>
          </ul>
        </li>

        <!-- التكاملات API -->
        <li class="nav-item">
          <a href="api-settings.index" class="nav-link">
            <i class="nav-icon fas fa-plug"></i>
            <p>التكاملات و API</p>
          </a>
        </li>

        <!-- ============================================ -->
        <!-- ⚙️ الإعدادات العامة -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #343a40; font-size: 14px;">الإعدادات</li>

        <li class="nav-item">
          <a href="settings.general" class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>الإعدادات العامة</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="settings.billing" class="nav-link">
            <i class="nav-icon fas fa-credit-card"></i>
            <p>إعدادات الفواتير والضرائب</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="settings.security" class="nav-link">
            <i class="nav-icon fas fa-lock"></i>
            <p>إعدادات الأمان</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="settings.email" class="nav-link">
            <i class="nav-icon fas fa-envelope"></i>
            <p>إعدادات البريد الإلكتروني</p>
          </a>
        </li>

        <!-- ============================================ -->
        <!-- 👤 حساب المستخدم -->
        <!-- ============================================ -->
        <li class="nav-header" style="color: #343a40; font-size: 14px;">حسابي</li>

        <li class="nav-item">
          <a href="profile.index" class="nav-link">
            <i class="nav-icon fas fa-user-circle"></i>
            <p>الملف الشخصي</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="profile.settings" class="nav-link">
            <i class="nav-icon fas fa-user-edit"></i>
            <p>تعديل الملف الشخصي</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="" class="nav-link">
            <i class="nav-icon fas fa-key"></i>
            <p>تغيير كلمة المرور</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="profile.two-factor" class="nav-link">
            <i class="nav-icon fas fa-shield-alt"></i>
            <p>التحقق بخطوتين (2FA)</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="profile.sessions" class="nav-link">
            <i class="nav-icon fas fa-laptop"></i>
            <p>الجلسات النشطة</p>
          </a>
        </li>

        <!-- ============================================ -->
        <!-- 🚪 تسجيل الخروج -->
        <!-- ============================================ -->
        <li class="nav-item">
          <a href="logout'" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>تسجيل الخروج</p>
          </a>
          <form id="logout-form" action="logout" method="POST" style="display: none;">
            @csrf
          </form>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>