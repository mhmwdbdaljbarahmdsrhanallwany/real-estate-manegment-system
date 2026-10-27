<nav class="main-header navbar navbar-expand"
    aria-label="الشريط العلوي">

    {{-- المجموعة الأولى: زر السايدبار والروابط والبحث --}}
    <ul class="navbar-nav header-primary-nav">

        {{-- زر فتح وإغلاق السايدبار --}}
        <li class="nav-item header-fixed-item">
            <a class="nav-link header-toggle-link"
                data-widget="pushmenu"
                href="#"
                aria-label="فتح أو إغلاق القائمة الجانبية">

                <i class="fas fa-bars" aria-hidden="true"></i>
            </a>
        </li>

        {{-- الصفحة الرئيسية --}}
        <li class="nav-item d-none d-sm-flex header-fixed-item">
            <a href="index3.html" class="nav-link header-page-link">
                @yield('homepageurl')
            </a>
        </li>

        {{-- الصفحة الحالية --}}
        <li class="nav-item d-none d-sm-flex header-fixed-item">
            <a href="#" class="nav-link header-current-page">
                @yield('urlpagebranch')
            </a>
        </li>

        {{-- البحث --}}
        <li class="nav-item header-search-item">
            <form class="form-inline header-search-form"
                id="header-search-form"
                action="#"
                method="GET">

                <div class="input-group input-group-sm">

                    <input
                        id="header-search-input"
                        name="search"
                        class="form-control form-control-navbar"
                        type="search"
                        placeholder="بحث في النظام..."
                        aria-label="بحث في النظام">

                    <div class="input-group-append">
                        <button
                            class="btn btn-navbar"
                            type="submit"
                            aria-label="تنفيذ البحث">

                            <i class="fas fa-search" aria-hidden="true"></i>
                        </button>
                    </div>

                </div>
            </form>
        </li>

    </ul>


    {{-- المجموعة الثانية: الرسائل والإشعارات والثيم --}}
    <ul class="navbar-nav header-secondary-nav">

        {{-- الرسائل --}}
        <li class="nav-item dropdown header-fixed-item">

            <a class="nav-link header-icon-link"
                data-toggle="dropdown"
                href="#"
                aria-haspopup="true"
                aria-expanded="false"
                aria-label="الرسائل">

                <i class="far fa-comments" aria-hidden="true"></i>

                <span class="badge navbar-badge header-badge header-badge-danger">
                    3
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left header-dropdown-menu">

                <a href="#" class="dropdown-item header-dropdown-item">
                    <div class="media">

                        <div class="header-avatar header-avatar-indigo">
                            BD
                        </div>

                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel

                                <span class="header-star header-star-danger">
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                </span>
                            </h3>

                            <p class="header-message-text">
                                Call me whenever you can...
                            </p>

                            <p class="header-message-time">
                                <i class="far fa-clock ml-1" aria-hidden="true"></i>
                                4 Hours Ago
                            </p>
                        </div>

                    </div>
                </a>

                <div class="header-dropdown-divider"></div>

                <a href="#" class="dropdown-item header-dropdown-item">
                    <div class="media">

                        <div class="header-avatar header-avatar-emerald">
                            JP
                        </div>

                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce

                                <span class="header-star">
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                </span>
                            </h3>

                            <p class="header-message-text">
                                I got your message bro
                            </p>

                            <p class="header-message-time">
                                <i class="far fa-clock ml-1" aria-hidden="true"></i>
                                4 Hours Ago
                            </p>
                        </div>

                    </div>
                </a>

                <div class="header-dropdown-divider"></div>

                <a href="#" class="dropdown-item header-dropdown-item">
                    <div class="media">

                        <div class="header-avatar header-avatar-amber">
                            NS
                        </div>

                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester

                                <span class="header-star header-star-amber">
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                </span>
                            </h3>

                            <p class="header-message-text">
                                The subject goes here
                            </p>

                            <p class="header-message-time">
                                <i class="far fa-clock ml-1" aria-hidden="true"></i>
                                4 Hours Ago
                            </p>
                        </div>

                    </div>
                </a>

                <div class="header-dropdown-divider"></div>

                <a href="#" class="dropdown-item dropdown-footer header-dropdown-footer">
                    مشاهدة جميع الرسائل
                </a>

            </div>
        </li>


        {{-- الإشعارات --}}
        <li class="nav-item dropdown header-fixed-item">

            <a class="nav-link header-icon-link"
                data-toggle="dropdown"
                href="#"
                aria-haspopup="true"
                aria-expanded="false"
                aria-label="الإشعارات">

                <i class="far fa-bell" aria-hidden="true"></i>

                <span class="badge navbar-badge header-badge header-badge-warning">
                    15
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left header-dropdown-menu">

                <span class="dropdown-item dropdown-header header-dropdown-header">
                    15 إشعار
                </span>

                <div class="header-dropdown-divider"></div>

                <a href="#" class="dropdown-item header-notification-item">
                    <i class="fas fa-envelope header-notification-icon header-icon-indigo"
                        aria-hidden="true"></i>

                    <span class="header-notification-text">
                        4 رسائل جديدة
                    </span>

                    <span class="header-notification-time">
                        3 دقائق
                    </span>
                </a>

                <div class="header-dropdown-divider"></div>

                <a href="#" class="dropdown-item header-notification-item">
                    <i class="fas fa-users header-notification-icon header-icon-emerald"
                        aria-hidden="true"></i>

                    <span class="header-notification-text">
                        8 طلبات صداقة
                    </span>

                    <span class="header-notification-time">
                        12 ساعة
                    </span>
                </a>

                <div class="header-dropdown-divider"></div>

                <a href="#" class="dropdown-item header-notification-item">
                    <i class="fas fa-file header-notification-icon header-icon-amber"
                        aria-hidden="true"></i>

                    <span class="header-notification-text">
                        3 تقارير جديدة
                    </span>

                    <span class="header-notification-time">
                        يومان
                    </span>
                </a>

                <div class="header-dropdown-divider"></div>

                <a href="#" class="dropdown-item dropdown-footer header-dropdown-footer">
                    مشاهدة جميع الإشعارات
                </a>

            </div>
        </li>


        {{-- قائمة الثيم --}}
        <li class="nav-item dropdown header-fixed-item">

            <a class="nav-link header-icon-link"
                data-toggle="dropdown"
                href="#"
                aria-haspopup="true"
                aria-expanded="false"
                aria-label="ألوان الواجهة">

                <i class="fas fa-th-large" aria-hidden="true"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left theme-dropdown-menu">

                <div class="theme-dropdown-title">
                    <i class="fas fa-palette" aria-hidden="true"></i>
                    ألوان الواجهة
                </div>

                <div class="theme-list">

                    {{-- Indigo --}}
                    <button
                        class="theme-btn"
                        data-theme="indigo"
                        type="button">

                        <span class="theme-colors">
                            <span class="theme-color theme-indigo-dark"></span>
                            <span class="theme-color theme-indigo-mid"></span>
                            <span class="theme-color theme-indigo-light"></span>
                        </span>

                        <span class="theme-name">
                            كلاسيك نيلي
                        </span>

                        <i class="fas fa-check theme-check"
                            id="check-indigo"
                            aria-hidden="true"></i>
                    </button>

                    {{-- Emerald --}}
                    <button
                        class="theme-btn"
                        data-theme="emerald"
                        type="button">

                        <span class="theme-colors">
                            <span class="theme-color theme-emerald-dark"></span>
                            <span class="theme-color theme-emerald-mid"></span>
                            <span class="theme-color theme-emerald-light"></span>
                        </span>

                        <span class="theme-name">
                            داكن زمردي
                        </span>

                        <i class="fas fa-check theme-check"
                            id="check-emerald"
                            aria-hidden="true"></i>
                    </button>

                    {{-- Navy --}}
                    <button
                        class="theme-btn"
                        data-theme="navy"
                        type="button">

                        <span class="theme-colors">
                            <span class="theme-color theme-navy-dark"></span>
                            <span class="theme-color theme-navy-mid"></span>
                            <span class="theme-color theme-navy-light"></span>
                        </span>

                        <span class="theme-name">
                            كحلي غامق
                        </span>

                        <i class="fas fa-check theme-check"
                            id="check-navy"
                            aria-hidden="true"></i>
                    </button>

                    {{-- Rose --}}
                    <button
                        class="theme-btn"
                        data-theme="rose"
                        type="button">

                        <span class="theme-colors">
                            <span class="theme-color theme-rose-dark"></span>
                            <span class="theme-color theme-rose-mid"></span>
                            <span class="theme-color theme-rose-light"></span>
                        </span>

                        <span class="theme-name">
                            دخاني وردي
                        </span>

                        <i class="fas fa-check theme-check"
                            id="check-rose"
                            aria-hidden="true"></i>
                    </button>

                    {{-- Light --}}
                    <button
                        class="theme-btn"
                        data-theme="light"
                        type="button">

                        <span class="theme-colors">
                            <span class="theme-color theme-light-dark"></span>
                            <span class="theme-color theme-light-mid"></span>
                            <span class="theme-color theme-light-accent"></span>
                        </span>

                        <span class="theme-name">
                            فاتح رمادي
                        </span>

                        <i class="fas fa-check theme-check"
                            id="check-light"
                            aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </li>

    </ul>

</nav>


<style>:root {
    --header-height: 60px;

    /* الثيم الافتراضي (الأزرق الداكن والأخضر الرقمي) */
    --header-bg: #0B132B;
    --header-text: #ffffff;
    --header-muted: #94a3b8;
    --header-border: rgba(217, 227, 238, .1);

    --header-input-bg: #1C2541;
    --header-input-border: #3A506B;
    --header-accent: #00F5D4;

    --header-menu-bg: #0B132B;
    --header-menu-text: #ffffff;
    --header-menu-muted: #94a3b8;
}

html[data-dashboard-theme="emerald"] {
    --header-bg: #064e3b;
    --header-text: #d1fae5;
    --header-muted: #a7f3d0;
    --header-border: rgba(255, 255, 255, .08);

    --header-input-bg: #022c22;
    --header-input-border: #065f46;
    --header-accent: #00F5D4;

    --header-menu-bg: #064e3b;
    --header-menu-text: #d1fae5;
    --header-menu-muted: #a7f3d0;
}

html[data-dashboard-theme="navy"] {
    --header-bg: #0c1833;
    --header-text: #bfdbfe;
    --header-muted: #93c5fd;
    --header-border: rgba(255, 255, 255, .06);

    --header-input-bg: #0a1628;
    --header-input-border: #1e3a5f;
    --header-accent: #00F5D4;

    --header-menu-bg: #0c1833;
    --header-menu-text: #bfdbfe;
    --header-menu-muted: #93c5fd;
}

html[data-dashboard-theme="rose"] {
    --header-bg: #1f1315;
    --header-text: #fecdd3;
    --header-muted: #fda4af;
    --header-border: rgba(255, 255, 255, .06);

    --header-input-bg: #1c1917;
    --header-input-border: #4c1d2b;
    --header-accent: #00F5D4;

    --header-menu-bg: #1f1315;
    --header-menu-text: #fecdd3;
    --header-menu-muted: #fda4af;
}

html[data-dashboard-theme="light"] {
    --header-bg: #ffffff;
    --header-text: #0B132B;
    --header-muted: #64748b;
    --header-border: #d9e3ee;

    --header-input-bg: #f8fafc;
    --header-input-border: #d9e3ee;
    --header-accent: #00F5D4;

    --header-menu-bg: #ffffff;
    --header-menu-text: #0B132B;
    --header-menu-muted: #64748b;
}


/* الهيدر الرئيسي */

.main-header {
    height: var(--header-height) !important;
    min-height: var(--header-height) !important;
    box-sizing: border-box !important;

    display: flex !important;
    align-items: center !important;

    padding: 0 1.5rem !important;
    direction: rtl !important;

    background: var(--header-bg) !important;
    border-bottom: 1px solid var(--header-border) !important;

    box-shadow: 0 1px 8px rgba(0, 0, 0, .2);

    transition: none !important;
    animation: none !important;
}


/* القوائم الرئيسية */

.main-header > .navbar-nav {
    height: var(--header-height) !important;
    display: flex !important;
    align-items: center !important;
}

.main-header .header-primary-nav {
    margin-left: auto !important;
}

.main-header .header-secondary-nav {
    display: flex !important;
    align-items: center !important;
    gap: .15rem !important;
    margin: 0 !important;
}

.main-header .header-fixed-item {
    height: var(--header-height) !important;
    display: flex !important;
    align-items: center !important;
    margin: 0 !important;
}


/* الروابط */

.main-header .nav-link {
    box-sizing: border-box !important;
    color: var(--header-text) !important;

    transition: none !important;
    animation: none !important;
}

.main-header .nav-link:hover,
.main-header .nav-link:focus {
    color: var(--header-text) !important;
}

.main-header .header-toggle-link {
    width: 46px !important;
    min-width: 46px !important;
    height: var(--header-height) !important;

    padding: 0 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.main-header .header-toggle-link i {
    width: 20px !important;
    min-width: 20px !important;
    text-align: center !important;
    font-size: 1.15rem !important;
}

.main-header .header-page-link {
    height: var(--header-height) !important;
    padding: 0 .75rem !important;

    display: flex !important;
    align-items: center !important;

    color: var(--header-text) !important;
    font-size: .88rem !important;
    font-weight: 600 !important;
}

.main-header .header-current-page {
    height: var(--header-height) !important;
    padding: 0 .75rem !important;

    display: flex !important;
    align-items: center !important;

    color: var(--header-muted) !important;
    font-size: .85rem !important;
}

.main-header .header-icon-link {
    width: 42px !important;
    min-width: 42px !important;
    height: var(--header-height) !important;

    padding: 0 !important;
    border-radius: 10px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    position: relative !important;
    color: var(--header-muted) !important;
}

.main-header .header-icon-link i {
    width: 20px !important;
    min-width: 20px !important;
    text-align: center !important;
    font-size: 1.1rem !important;
}


/* البحث */

.main-header .header-search-item {
    height: var(--header-height) !important;
    display: flex !important;
    align-items: center !important;
    margin: 0 0 0 auto !important;
}

.main-header .header-search-form {
    height: var(--header-height) !important;
    display: flex !important;
    align-items: center !important;
    margin: 0 !important;
}

.main-header .header-search-form .input-group {
    height: 34px !important;
    display: flex !important;
    align-items: stretch !important;
}

.main-header .form-control-navbar {
    width: 240px !important;
    height: 34px !important;
    min-height: 34px !important;

    box-sizing: border-box !important;

    background: var(--header-input-bg) !important;
    border: 1px solid var(--header-input-border) !important;
    color: var(--header-text) !important;

    outline: none !important;
    font-size: .85rem !important;
    padding: .45rem .85rem !important;

    transition: none !important;
    animation: none !important;
}

.main-header .form-control-navbar:focus {
    background: var(--header-input-bg) !important;
    border-color: var(--header-accent) !important;
    color: var(--header-text) !important;
    box-shadow: 0 0 0 3px rgba(0, 245, 212, .15) !important;
}

.main-header .form-control-navbar::placeholder {
    color: var(--header-muted) !important;
    opacity: 1 !important;
}

.main-header .btn-navbar {
    width: 42px !important;
    height: 34px !important;
    min-height: 34px !important;

    margin-left: .5rem !important;
    padding: 0 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    background: var(--header-input-bg) !important;
    border: 1px solid var(--header-input-border) !important;
    border-radius: 0 8px 8px 0 !important;

    color: var(--header-accent) !important;

    transition: none !important;
    animation: none !important;
}

.main-header .btn-navbar:hover,
.main-header .btn-navbar:focus {
    background: var(--header-input-bg) !important;
    color: var(--header-accent) !important;
    box-shadow: none !important;
}


/* الشارات */

.main-header .header-badge {
    position: absolute !important;
    top: 8px !important;
    left: 4px !important;

    min-width: 16px !important;
    height: 16px !important;
    line-height: 1.2 !important;

    padding: .15rem .4rem !important;
    border-radius: 40px !important;

    color: #fff !important;
    font-size: .6rem !important;
    font-weight: 600 !important;
    text-align: center !important;
}

.main-header .header-badge-danger {
    background: #ef4444 !important;
}

.main-header .header-badge-warning {
    background: #f59e0b !important;
    color: #0B132B !important;
}


/* القوائم المنسدلة */

.main-header .header-dropdown-menu,
.main-header .theme-dropdown-menu {
    background: var(--header-menu-bg) !important;
    border: 1px solid var(--header-border) !important;
    border-radius: 12px !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .5) !important;

    padding: .5rem !important;
    margin-top: .6rem !important;

    transition: none !important;
    animation: none !important;
}

.main-header .header-dropdown-item {
    border-radius: 8px !important;
    padding: .65rem .75rem !important;
    color: var(--header-menu-text) !important;
    transition: none !important;
}

.main-header .header-dropdown-item:hover,
.main-header .header-dropdown-item:focus {
    background: rgba(0, 245, 212, .08) !important;
    color: var(--header-menu-text) !important;
}

.main-header .header-dropdown-divider {
    border-top: 1px solid var(--header-border) !important;
    margin: .2rem 0 !important;
}

.main-header .header-dropdown-header {
    border-radius: 8px !important;
    padding: .5rem .75rem !important;

    color: var(--header-menu-muted) !important;
    font-size: .8rem !important;
    font-weight: 500 !important;
    text-align: center !important;
}

.main-header .header-dropdown-footer {
    border-radius: 8px !important;
    padding: .65rem .75rem !important;

    color: var(--header-accent) !important;
    font-size: .82rem !important;
    font-weight: 500 !important;
    text-align: center !important;
}


/* عناصر الرسائل والإشعارات */

.main-header .header-avatar {
    width: 36px !important;
    height: 36px !important;
    min-width: 36px !important;

    margin-left: .75rem !important;
    flex-shrink: 0 !important;

    border-radius: 10px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    color: #fff !important;
    font-size: .7rem !important;
    font-weight: 700 !important;
}

.main-header .header-avatar-indigo {
    background: linear-gradient(135deg, #0B132B, #1C2541) !important;
}

.main-header .header-avatar-emerald {
    background: linear-gradient(135deg, #064e3b, #059669) !important;
}

.main-header .header-avatar-amber {
    background: linear-gradient(135deg, #f59e0b, #fbbf24) !important;
}

.main-header .dropdown-item-title {
    margin: 0 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;

    color: var(--header-text) !important;
    font-size: .85rem !important;
    font-weight: 600 !important;
}

.main-header .header-message-text {
    margin: 2px 0 0 !important;
    color: var(--header-menu-muted) !important;
    font-size: .78rem !important;
}

.main-header .header-message-time {
    margin: 2px 0 0 !important;
    color: #94a3b8 !important;
    font-size: .72rem !important;
}

.main-header .header-star {
    color: #64748b !important;
    font-size: .7rem !important;
}

.main-header .header-star-danger {
    color: #ef4444 !important;
}

.main-header .header-star-amber {
    color: #f59e0b !important;
}


.main-header .header-notification-item {
    display: flex !important;
    align-items: center !important;
    gap: .5rem !important;

    border-radius: 8px !important;
    padding: .6rem .75rem !important;

    color: var(--header-menu-text) !important;
    font-size: .82rem !important;

    transition: none !important;
}

.main-header .header-notification-item:hover,
.main-header .header-notification-item:focus {
    background: rgba(0, 245, 212, .08) !important;
    color: var(--header-menu-text) !important;
}

.main-header .header-notification-icon {
    width: 16px !important;
    min-width: 16px !important;
    text-align: center !important;
}

.main-header .header-icon-indigo {
    color: var(--header-accent) !important;
}

.main-header .header-icon-emerald {
    color: #00F5D4 !important;
}

.main-header .header-icon-amber {
    color: #f59e0b !important;
}

.main-header .header-notification-text {
    flex: 1 !important;
}

.main-header .header-notification-time {
    color: #94a3b8 !important;
    font-size: .72rem !important;
}


/* قائمة الثيم */

.main-header .theme-dropdown-menu {
    min-width: 240px !important;
    padding: .85rem !important;
    direction: rtl !important;
}

.main-header .theme-dropdown-title {
    margin-bottom: .6rem !important;
    padding-right: .15rem !important;

    color: var(--header-menu-muted) !important;
    font-size: .78rem !important;
    font-weight: 600 !important;
}

.main-header .theme-dropdown-title i {
    margin-left: .35rem !important;
}

.main-header .theme-list {
    display: flex !important;
    flex-direction: column !important;
    gap: .3rem !important;
}

.main-header .theme-btn {
    width: 100% !important;
    min-height: 38px !important;

    display: flex !important;
    align-items: center !important;
    gap: .6rem !important;

    padding: .5rem .65rem !important;
    border: none !important;
    border-radius: 10px !important;

    background: transparent !important;
    color: var(--header-menu-text) !important;

    cursor: pointer !important;
    text-align: right !important;
    font-size: .82rem !important;

    transition: none !important;
    animation: none !important;
}

.main-header .theme-btn:hover,
.main-header .theme-btn:focus {
    background: rgba(0, 245, 212, .08) !important;
    color: var(--header-menu-text) !important;
    outline: none !important;
}

.main-header .theme-colors {
    display: flex !important;
    gap: 3px !important;
    flex-shrink: 0 !important;
}

.main-header .theme-color {
    width: 18px !important;
    height: 18px !important;
    border-radius: 5px !important;
    display: block !important;
}

.main-header .theme-name {
    flex: 1 !important;
}

.main-header .theme-check {
    width: 12px !important;
    min-width: 12px !important;
    color: var(--header-accent) !important;
    font-size: .65rem !important;
    opacity: 0 !important;
}

/* ألوان معاينات الثيمات (محدثة حسب الهوية البصرية الجديدة) */
.main-header .theme-indigo-dark {
    background: #0B132B !important;
}

.main-header .theme-indigo-mid {
    background: #1C2541 !important;
}

.main-header .theme-indigo-light {
    background: #00F5D4 !important;
}

.main-header .theme-emerald-dark {
    background: #064e3b !important;
}

.main-header .theme-emerald-mid {
    background: #059669 !important;
}

.main-header .theme-emerald-light {
    background: #00F5D4 !important;
}

.main-header .theme-navy-dark {
    background: #0c1833 !important;
}

.main-header .theme-navy-mid {
    background: #1e3a5f !important;
}

.main-header .theme-navy-light {
    background: #00F5D4 !important;
}

.main-header .theme-rose-dark {
    background: #1c1917 !important;
}

.main-header .theme-rose-mid {
    background: #be123c !important;
}

.main-header .theme-rose-light {
    background: #00F5D4 !important;
}

.main-header .theme-light-dark {
    background: #f1f5f9 !important;
}

.main-header .theme-light-mid {
    background: #cbd5e1 !important;
}

.main-header .theme-light-accent {
    background: #00F5D4 !important;
}


/* الشاشات المتوسطة والصغيرة */

@media (max-width: 768px) {
    .main-header {
        padding: 0 .75rem !important;
    }

    .main-header .form-control-navbar {
        width: 160px !important;
    }

    .main-header .header-page-link,
    .main-header .header-current-page {
        padding: 0 .4rem !important;
    }
}

@media (max-width: 576px) {
    .main-header .header-search-item {
        display: none !important;
    }

    .main-header .header-icon-link {
        width: 38px !important;
        min-width: 38px !important;
    }

    .main-header .theme-dropdown-menu {
        position: fixed !important;
        top: var(--header-height) !important;
        right: .5rem !important;
        left: .5rem !important;
        min-width: auto !important;
    }
}


/* منع أي حركة داخل الهيدر */

.main-header *,
.main-header *::before,
.main-header *::after {
    animation: none !important;
}

.main-header .nav-link,
.main-header .form-control,
.main-header .btn,
.main-header .dropdown-item,
.main-header .theme-btn {
    transition: none !important;
}
</style>
<script>
(function () {
    'use strict';

    function getCurrentTheme() {
        return localStorage.getItem('dashboard-theme') || 'indigo';
    }

    function updateThemeChecks(theme) {
        document
            .querySelectorAll('.theme-btn .theme-check')
            .forEach(function (icon) {
                icon.style.opacity = '0';
            });

        const selectedCheck = document.getElementById('check-' + theme);

        if (selectedCheck) {
            selectedCheck.style.opacity = '1';
        }
    }

    function setTheme(theme) {
        const allowedThemes = [
            'indigo',
            'emerald',
            'navy',
            'rose',
            'light'
        ];

        if (!allowedThemes.includes(theme)) {
            theme = 'indigo';
        }

        document.documentElement.setAttribute(
            'data-dashboard-theme',
            theme
        );

        localStorage.setItem('dashboard-theme', theme);

        updateThemeChecks(theme);
    }

    function initializeHeader() {
        setTheme(getCurrentTheme());

        document
            .querySelectorAll('.theme-btn')
            .forEach(function (button) {

                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    setTheme(
                        button.getAttribute('data-theme')
                    );
                });
            });

        const searchForm =
            document.getElementById('header-search-form');

        if (searchForm) {
            searchForm.addEventListener('submit', function (event) {
                event.preventDefault();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initializeHeader,
            { once: true }
        );
    } else {
        initializeHeader();
    }
})();
</script>