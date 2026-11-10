



<div>
    <div>

        <title>الخريطة العقارية | نظام إدارة العقارات</title>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @assets
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        @endassets
<style>
    :root {
        --royal: #0F2B46;
        --midnight: #1B4D7A;
        --gold: #C9A227;
        --gold-light: #E8CB5C;
        --emerald: #059669;
        --terracotta: #D97706;
        --cherry: #DC2626;
        --sky: #0284C7;
        --purple: #7C3AED;

        --bg-page: #F7F9FC;
        --bg-card: #FFFFFF;
        --bg-field: #EEF2F7;
        --text-primary: #1E293B;
        --text-secondary: #64748B;
        --text-muted: #94A3B8;
        --border-soft: #E2E8F0;

        --shadow-sm: 0 1px 3px rgba(15, 43, 70, 0.06);
        --shadow-md: 0 4px 16px rgba(15, 43, 70, 0.08);
        --shadow-lg: 0 12px 40px rgba(15, 43, 70, 0.12);
        --shadow-gold: 0 6px 20px rgba(201, 162, 39, 0.3);

        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Tajawal', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--bg-page);
        color: var(--text-primary);
        min-height: 100vh;
        line-height: 1.5;
        overflow: hidden;
    }

    .content-area {
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ============================================================
             شريط الأدوات العلوي
                ============================================================ 
            */
    .page-toolbar {
        background: var(--bg-card);
        box-shadow: var(--shadow-sm);
        padding: 0.7rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--border-soft);
        flex-shrink: 0;
        z-index: 10;
    }

    .toolbar-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        min-width: 0;
    }

    .toolbar-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--royal), var(--midnight));
        color: var(--gold-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(15, 43, 70, 0.25);
    }

    .toolbar-text h1 {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--royal);
        margin-bottom: 0.05rem;
    }

    .toolbar-text p {
        font-size: 0.7rem;
        color: var(--text-secondary);
    }

    .toolbar-actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-inline-start: auto;
        flex-wrap: wrap;
    }

    .btn-add-main {
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: var(--royal);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: var(--transition);
        box-shadow: 0 3px 10px rgba(201, 162, 39, 0.35);
        height: 34px;
        white-space: nowrap;
    }

    .btn-add-main:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(201, 162, 39, 0.5);
    }

    .btn-add-main.blue {
        background: linear-gradient(135deg, var(--midnight), #2563EB);
        color: #fff;
        box-shadow: 0 3px 10px rgba(27, 77, 122, 0.35);
    }

    .btn-add-main.blue:hover {
        box-shadow: 0 6px 16px rgba(27, 77, 122, 0.5);
    }

    .btn-add-main i {
        font-size: 0.72rem;
    }

    /* ============================================================
                    شريط الفلاتر
                 ============================================================
            */
    .filters-bar {
        background: var(--bg-card);
        border-bottom: 1px solid var(--border-soft);
        padding: 0.55rem 1rem;
        flex-shrink: 0;
        z-index: 9;
    }

    .filters-row {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .filter-search {
        position: relative;
        display: flex;
        align-items: center;
        flex: 2;
        min-width: 200px;
    }

    .filter-search i {
        position: absolute;
        right: 0.65rem;
        color: var(--text-muted);
        font-size: 0.72rem;
        pointer-events: none;
    }

    .filter-search input {
        width: 100%;
        padding: 0.42rem 1.9rem 0.42rem 0.6rem;
        font-family: inherit;
        font-size: 0.78rem;
        background: var(--bg-field);
        border: 1.5px solid transparent;
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        outline: none;
        transition: var(--transition);
        height: 34px;
    }

    .filter-search input:focus {
        border-color: var(--midnight);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(27, 77, 122, 0.1);
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        flex: 0 0 auto;
        min-width: 130px;
    }

    .filter-control {
        width: 100%;
        padding: 0.42rem 0.6rem;
        font-family: inherit;
        font-size: 0.76rem;
        color: var(--text-primary);
        background: var(--bg-field);
        border: 1.5px solid transparent;
        border-radius: var(--radius-sm);
        outline: none;
        transition: var(--transition);
        height: 34px;
        cursor: pointer;
    }

    .filter-control:focus {
        border-color: var(--midnight);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(27, 77, 122, 0.1);
    }

    .filter-control:hover {
        background: #E8EEF5;
    }

    /* أزرار نوع العقار */
    .property-type-chips {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: wrap;
        background: var(--bg-field);
        border-radius: var(--radius-sm);
        padding: 0.2rem;
    }

    .chip {
        background: transparent;
        border: none;
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: var(--transition);
        white-space: nowrap;
    }

    .chip:hover {
        color: var(--midnight);
        background: rgba(255, 255, 255, 0.6);
    }

    .chip.active {
        background: #fff;
        color: var(--royal);
        box-shadow: 0 2px 6px rgba(15, 43, 70, 0.1);
    }

    .chip.active i {
        color: var(--gold);
    }

    .chip i {
        font-size: 0.65rem;
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-inline-start: auto;
    }

    .btn-filter-action {
        padding: 0.4rem 0.75rem;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border-soft);
        background: #fff;
        color: var(--text-secondary);
        font-family: inherit;
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: var(--transition);
        height: 34px;
        white-space: nowrap;
    }

    .btn-filter-action:hover {
        background: var(--bg-field);
        color: var(--midnight);
        border-color: #CBD5E1;
    }

    .btn-filter-action.primary {
        background: linear-gradient(135deg, var(--royal), var(--midnight));
        color: #fff;
        border-color: transparent;
    }

    .btn-filter-action.primary:hover {
        box-shadow: 0 4px 12px rgba(15, 43, 70, 0.3);
    }

    .btn-filter-action.clear:hover {
        background: #FEF2F2;
        color: var(--cherry);
        border-color: rgba(220, 38, 38, 0.3);
    }

    .btn-filter-action i {
        font-size: 0.68rem;
    }

    /* ============================================================
                   منطقة العمل — خريطة + قائمة
               ============================================================ 
            */
    .map-workspace {
        flex: 1;
        display: grid;
        grid-template-columns: 380px 1fr;
        min-height: 0;
        background: var(--bg-page);
        position: relative;
    }

    /* ---------- لوحة القائمة الجانبية ---------- */
    .sidebar {
        background: var(--bg-card);
        border-left: 1px solid var(--border-soft);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 5;
    }

    .sidebar-header {
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid var(--border-soft);
        background: linear-gradient(135deg, rgba(15, 43, 70, 0.02) 0%, rgba(201, 162, 39, 0.04) 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .sidebar-title {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--royal);
    }

    .sidebar-title i {
        color: var(--gold);
        font-size: 0.8rem;
    }

    .sidebar-title .count {
        background: var(--bg-field);
        color: var(--midnight);
        padding: 0.08rem 0.45rem;
        border-radius: 999px;
        font-size: 0.62rem;
        font-weight: 700;
    }

    .view-toggle {
        display: flex;
        gap: 0.15rem;
        background: var(--bg-field);
        padding: 0.12rem;
        border-radius: 6px;
    }

    .view-toggle button {
        background: transparent;
        border: none;
        width: 26px;
        height: 24px;
        border-radius: 4px;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 0.7rem;
        transition: var(--transition);
    }

    .view-toggle button.active {
        background: #fff;
        color: var(--royal);
        box-shadow: 0 1px 4px rgba(15, 43, 70, 0.1);
    }

    .properties-list {
        flex: 1;
        overflow-y: auto;
        padding: 0.45rem;
    }

    .properties-list::-webkit-scrollbar {
        width: 5px;
    }

    .properties-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .properties-list::-webkit-scrollbar-thumb {
        background: var(--border-soft);
        border-radius: 999px;
    }

    /* بطاقة عقار */
    .property-card {
        background: #fff;
        border: 1.5px solid var(--border-soft);
        border-radius: var(--radius-md);
        overflow: hidden;
        cursor: pointer;
        transition: var(--transition);
        margin-bottom: 0.45rem;
        position: relative;
    }

    .property-card:hover {
        border-color: var(--gold);
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }

    .property-card.active {
        border-color: var(--gold);
        background: linear-gradient(135deg, rgba(201, 162, 39, 0.06) 0%, rgba(15, 43, 70, 0.02) 100%);
        box-shadow: 0 4px 14px rgba(201, 162, 39, 0.2);
    }

    .property-card.active::before {
        content: '';
        position: absolute;
        right: 0;
        top: 15%;
        bottom: 15%;
        width: 3px;
        background: linear-gradient(180deg, var(--gold), var(--gold-light));
        border-radius: 999px;
    }

    .property-card-inner {
        display: flex;
        gap: 0.55rem;
        padding: 0.55rem;
    }

    .property-thumb {
        width: 76px;
        height: 76px;
        border-radius: var(--radius-sm);
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--midnight), var(--royal));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-light);
        font-size: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .property-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .property-badge {
        position: absolute;
        top: 3px;
        right: 3px;
        padding: 0.08rem 0.35rem;
        border-radius: 4px;
        font-size: 0.55rem;
        font-weight: 700;
        color: #fff;
        backdrop-filter: blur(4px);
    }

    .property-badge.for-sale {
        background: rgba(5, 150, 105, 0.92);
    }

    .property-badge.for-rent {
        background: rgba(2, 132, 199, 0.92);
    }

    .property-badge.sold {
        background: rgba(220, 38, 38, 0.92);
    }

    .property-info {
        flex: 1;
        min-width: 0;
    }

    .property-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0.15rem;
    }

    .property-location {
        font-size: 0.64rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.22rem;
        margin-bottom: 0.35rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .property-location i {
        color: var(--gold);
        font-size: 0.55rem;
    }

    .property-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.62rem;
        color: var(--text-secondary);
        flex-wrap: wrap;
    }

    .property-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
    }

    .property-meta i {
        font-size: 0.55rem;
        color: var(--midnight);
    }

    .property-price {
        font-size: 0.82rem;
        font-weight: 800;
        color: var(--emerald);
        margin-top: 0.25rem;
    }

    .property-price small {
        font-size: 0.58rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    /* حالة فارغة */
    .list-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        text-align: center;
        color: var(--text-muted);
    }

    .list-empty i {
        font-size: 2.4rem;
        color: var(--border-soft);
        margin-bottom: 0.6rem;
    }

    .list-empty h4 {
        font-size: 0.82rem;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .list-empty p {
        font-size: 0.7rem;
    }

    /* ---------- لوحة الخريطة ---------- */
    .map-panel {
        position: relative;
        overflow: hidden;
        background: #e5e7eb;
    }

    #map {
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    =.map-popup-image {
        width: 100%;
        height: 110px;
        background: linear-gradient(135deg, var(--midnight), var(--royal));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-light);
        font-size: 2rem;
        position: relative;
        overflow: hidden;
    }

    .map-popup-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .map-popup-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        padding: 0.15rem 0.5rem;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        color: #fff;
        backdrop-filter: blur(4px);
    }

    .map-popup-body {
        padding: 0.65rem 0.75rem;
    }

    .map-popup-title {
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--royal);
        margin-bottom: 0.2rem;
    }

    .map-popup-location {
        font-size: 0.68rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-bottom: 0.45rem;
    }

    .map-popup-location i {
        color: var(--gold);
        font-size: 0.6rem;
    }

    .map-popup-features {
        display: flex;
        gap: 0.55rem;
        font-size: 0.65rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
    }

    .map-popup-features span {
        display: flex;
        align-items: center;
        gap: 0.2rem;
    }

    .map-popup-features i {
        color: var(--midnight);
        font-size: 0.6rem;
    }

    .map-popup-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.5rem;
        border-top: 1px solid var(--border-soft);
    }

    .map-popup-price {
        font-size: 0.88rem;
        font-weight: 800;
        color: var(--emerald);
    }

    .map-popup-price small {
        font-size: 0.58rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .map-popup-btn {
        padding: 0.32rem 0.6rem;
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: var(--royal);
        border: none;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.65rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: var(--transition);
    }

    .map-popup-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(201, 162, 39, 0.4);
    }

    /* علامة مخصصة على الخريطة */
    .custom-marker {
        background: transparent !important;
        border: none !important;
    }

    .marker-pin {
        width: 36px;
        height: 36px;
        border-radius: 50% 50% 50% 0;
        background: linear-gradient(135deg, var(--royal), var(--midnight));
        border: 3px solid #fff;
        box-shadow: 0 3px 12px rgba(15, 43, 70, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-light);
        font-size: 0.85rem;
        transform: rotate(-45deg);
        transition: var(--transition);
        position: relative;
    }

    .marker-pin i {
        transform: rotate(45deg);
    }

    .marker-pin.for-sale {
        background: linear-gradient(135deg, var(--emerald), #10B981);
        color: #fff;
    }

    .marker-pin.for-rent {
        background: linear-gradient(135deg, var(--sky), #38BDF8);
        color: #fff;
    }

    .marker-pin.sold {
        background: linear-gradient(135deg, #64748B, #94A3B8);
        color: #fff;
    }

    .marker-pin.active {
        transform: rotate(-45deg) scale(1.25);
        box-shadow: 0 6px 20px rgba(201, 162, 39, 0.6);
        border-color: var(--gold);
        z-index: 999;
    }

    /* أدوات التحكم على الخريطة */
    .map-controls {
        position: absolute;
        top: 0.85rem;
        left: 0.85rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        z-index: 500;
    }

    .map-ctrl-btn {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        background: #fff;
        border: 1.5px solid var(--border-soft);
        color: var(--text-secondary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
    }

    .map-ctrl-btn:hover {
        background: var(--bg-field);
        color: var(--midnight);
        border-color: #CBD5E1;
        transform: translateY(-1px);
    }

    .map-ctrl-btn.active {
        background: linear-gradient(135deg, var(--royal), var(--midnight));
        color: var(--gold-light);
        border-color: transparent;
    }

    /* مفتاح الخريطة (legend) */
    .map-legend {
        position: absolute;
        bottom: 1rem;
        left: 0.85rem;
        background: #fff;
        border-radius: var(--radius-md);
        padding: 0.55rem 0.7rem;
        box-shadow: var(--shadow-lg);
        border: 1.5px solid var(--border-soft);
        z-index: 500;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        min-width: 130px;
    }

    .map-legend-title {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--royal);
        margin-bottom: 0.1rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .map-legend-title i {
        color: var(--gold);
        font-size: 0.6rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.65rem;
        color: var(--text-secondary);
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .legend-dot.sale {
        background: var(--emerald);
    }

    .legend-dot.rent {
        background: var(--sky);
    }

    .legend-dot.sold {
        background: #94A3B8;
    }

    /* عداد النتائج فوق الخريطة */
    .map-result-badge {
        position: absolute;
        top: 0.85rem;
        right: 0.85rem;
        background: rgba(15, 43, 70, 0.92);
        color: #fff;
        padding: 0.4rem 0.8rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        backdrop-filter: blur(6px);
        box-shadow: var(--shadow-md);
        z-index: 500;
    }

    .map-result-badge i {
        color: var(--gold-light);
        font-size: 0.65rem;
    }

    .map-result-badge strong {
        color: var(--gold-light);
    }

    /* ============================================================
                   نافذة تفاصيل العقار
                ============================================================ 
            */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 43, 70, 0.5);
        backdrop-filter: blur(6px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        animation: fadeIn 0.2s ease;
    }

    .modal-overlay.show {
        display: flex;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .modal {
        background: #fff;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        width: 100%;
        max-width: 720px;
        max-height: 92vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: modalUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.96);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        padding: 0.8rem 1rem;
        background: linear-gradient(135deg, var(--royal) 0%, var(--midnight) 100%);
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        position: relative;
        flex-shrink: 0;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
    }

    .modal-header-icon {
        width: 34px;
        height: 34px;
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--royal);
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .modal-header-text h2 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0.05rem;
    }

    .modal-header-text p {
        font-size: 0.68rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .modal-close {
        margin-inline-start: auto;
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        font-size: 0.8rem;
    }

    .modal-close:hover {
        background: rgba(220, 38, 38, 0.3);
    }

    .modal-body {
        padding: 1rem;
        overflow-y: auto;
        flex: 1;
    }

    .modal-body::-webkit-scrollbar {
        width: 4px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: var(--border-soft);
        border-radius: 999px;
    }

    .modal-footer {
        padding: 0.7rem 1rem;
        border-top: 1px solid var(--border-soft);
        display: flex;
        gap: 0.4rem;
        justify-content: flex-end;
        background: var(--bg-field);
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    .btn {
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.45rem 1.05rem;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.32rem;
        transition: var(--transition);
        height: 34px;
    }

    .btn-secondary {
        background: #fff;
        color: var(--text-primary);
        border: 1.5px solid var(--border-soft);
    }

    .btn-secondary:hover {
        background: var(--bg-page);
        border-color: #CBD5E1;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--royal), var(--midnight));
        color: #fff;
        box-shadow: 0 3px 10px rgba(15, 43, 70, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 43, 70, 0.4);
    }

    /* تفاصيل العقار في النافذة */
    .property-detail-hero {
        height: 180px;
        background: linear-gradient(135deg, var(--midnight), var(--royal));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-light);
        font-size: 3rem;
        border-radius: var(--radius-md);
        margin-bottom: 0.75rem;
        position: relative;
        overflow: hidden;
    }

    .property-detail-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .property-detail-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--royal);
        margin-bottom: 0.25rem;
    }

    .property-detail-location {
        font-size: 0.76rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-bottom: 0.75rem;
    }

    .property-detail-location i {
        color: var(--gold);
        font-size: 0.7rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .detail-item {
        background: var(--bg-field);
        border-radius: var(--radius-sm);
        padding: 0.55rem 0.7rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        background: rgba(27, 77, 122, 0.12);
        color: var(--midnight);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .detail-item-content {
        flex: 1;
        min-width: 0;
    }

    .detail-item-label {
        font-size: 0.6rem;
        color: var(--text-secondary);
        margin-bottom: 0.05rem;
    }

    .detail-item-value {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .detail-price-box {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.08), rgba(5, 150, 105, 0.03));
        border: 1.5px solid rgba(5, 150, 105, 0.2);
        border-radius: var(--radius-md);
        padding: 0.7rem 0.85rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .detail-price-label {
        font-size: 0.7rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .detail-price-value {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--emerald);
    }

    .detail-price-value small {
        font-size: 0.65rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .detail-section-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--royal);
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .detail-section-title i {
        color: var(--gold);
        font-size: 0.7rem;
    }

    .detail-notes {
        font-size: 0.76rem;
        color: var(--text-secondary);
        line-height: 1.6;
        background: var(--bg-field);
        padding: 0.6rem 0.75rem;
        border-radius: var(--radius-sm);
    }

    /* ============================================================
                  Toast
                 ============================================================ 
            */
    .toast {
        position: fixed;
        bottom: 1.25rem;
        left: 50%;
        transform: translateX(-50%) translateY(150%);
        background: var(--royal);
        color: #fff;
        padding: 0.6rem 1.15rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.76rem;
        font-weight: 500;
        z-index: 2000;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-width: 90vw;
    }

    .toast.show {
        transform: translateX(-50%) translateY(0);
    }

    .toast.success {
        border-right: 4px solid var(--emerald);
    }

    .toast.error {
        border-right: 4px solid var(--cherry);
    }

    .toast.success i {
        color: var(--emerald);
    }

    .toast.error i {
        color: var(--cherry);
    }

    /* ============================================================
              Responsive
                 ============================================================ 
            */
    @media (max-width: 1100px) {
        .map-workspace {
            grid-template-columns: 320px 1fr;
        }
    }

    @media (max-width: 900px) {
        body {
            overflow: auto;
        }

        .content-area {
            height: auto;
            min-height: 100vh;
        }

        .map-workspace {
            grid-template-columns: 1fr;
            grid-template-rows: 380px 500px;
        }

        .sidebar {
            border-left: none;
            border-bottom: 1px solid var(--border-soft);
        }

        .map-panel {
            min-height: 500px;
        }
    }

    @media (max-width: 768px) {
        .page-toolbar {
            padding: 0.6rem 0.75rem;
        }

        .toolbar-text h1 {
            font-size: 0.9rem;
        }

        .toolbar-text p {
            font-size: 0.65rem;
        }

        .toolbar-icon {
            width: 34px;
            height: 34px;
            font-size: 0.9rem;
        }

        .toolbar-actions {
            width: 100%;
        }

        .btn-add-main {
            flex: 1;
            justify-content: center;
        }

        .filters-row {
            gap: 0.35rem;
        }

        .filter-item {
            min-width: 120px;
        }

        .filter-search {
            min-width: 100%;
        }

        .map-controls {
            top: 0.6rem;
            left: 0.6rem;
        }

        .map-legend {
            display: none;
        }

        .map-result-badge {
            top: 0.6rem;
            right: 0.6rem;
            font-size: 0.65rem;
            padding: 0.35rem 0.65rem;
        }

        .modal {
            border-radius: var(--radius-md);
        }

        .modal-footer {
            flex-direction: column-reverse;
        }

        .modal-footer .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .map-workspace {
            grid-template-rows: 340px 460px;
        }

        .filter-item {
            min-width: 100%;
        }

        .filter-actions {
            width: 100%;
            margin-inline-start: 0;
        }

        .btn-filter-action {
            flex: 1;
            justify-content: center;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

        @php
        $mapFilters = $filterDefs ?? [];
        $mapStatusLabels = $statusLabels ?? [];
        $mapMarkers = $markers ?? [];

        $typeFilter = $mapFilters['property_type'] ?? null;
        $statusFilter = $mapFilters['is_active'] ?? ($mapFilters['status'] ?? null);
        $cityFilter = $mapFilters['city'] ?? null;
        $priceFilter = $mapFilters['price'] ?? null;

        $typeOptions = $typeFilter['options'] ?? [];
        $statusOptions = $statusFilter['options'] ?? [];
        $cityOptions = $cityFilter['options'] ?? [];
        $priceOptions = $priceFilter['options'] ?? [];
        @endphp

        <div class="content-area">

            <!-- ============ شريط الأدوات ============ -->
            <div class="page-toolbar">
                <div class="toolbar-title">
                    <div class="toolbar-icon"><i class="fas fa-map-location-dot"></i></div>
                    <div class="toolbar-text">
                        <h1>الخريطة العقارية</h1>
                        <p>استعرض العقارات على الخريطة وابحث عن موقعك المثالي</p>
                    </div>
                </div>

                <div class="toolbar-actions">
                    <button type="button" class="btn-add-main blue" onclick="locateMe()">
                        <i class="fas fa-location-crosshairs"></i>
                        موقعي الحالي
                    </button>

                    <button type="button" class="btn-add-main" onclick="openAddProperty()">
                        <i class="fas fa-plus"></i>
                        إضافة عقار
                    </button>
                </div>
            </div>

            <!-- ============ شريط الفلاتر ============ -->
            <div class="filters-bar">
                <div class="filters-row">

                    <div class="filter-search">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="ابحث باسم العقار، الحي، المدينة..."
                            wire:model.live.debounce.400ms="search">
                    </div>

                    <div class="property-type-chips" id="typeChips">

                        <button type="button" class="chip {{ empty($filters['property_type'] ?? '') ? 'active' : '' }}"
                            wire:click="$set('filters.property_type', '')">
                            <i class="fas fa-border-all"></i>
                            الكل
                        </button>

                        @foreach($typeOptions as $typeValue => $typeLabel)
                        @php
                        if (is_array($typeLabel)) {
                        $typeText = $typeLabel['label'] ?? $typeLabel['name'] ?? $typeValue;
                        $typeValue = $typeLabel['value'] ?? $typeValue;
                        } else {
                        $typeText = $typeLabel;
                        }

                        $typeIcon = match((string) $typeValue) {
                        'apartment' => 'fa-building',
                        'villa' => 'fa-house',
                        'commercial' => 'fa-store',
                        'land' => 'fa-mountain-sun',
                        'office' => 'fa-briefcase',
                        default => 'fa-building'
                        };
                        @endphp

                        <button type="button"
                            class="chip {{ (string) ($filters['property_type'] ?? '') === (string) $typeValue ? 'active' : '' }}"
                            wire:click="$set('filters.property_type', '{{ $typeValue }}')">
                            <i class="fas {{ $typeIcon }}"></i>
                            {{ $typeText }}
                        </button>
                        @endforeach

                    </div>

                    <div class="filter-item">
                        <select class="filter-control" id="filterStatus"
                            wire:model.live="filters.{{ isset($mapFilters['is_active']) ? 'is_active' : 'status' }}">

                            <option value="">كل الحالات</option>

                            @foreach($statusOptions as $statusValue => $statusLabel)
                            @php
                            if (is_array($statusLabel)) {
                            $statusText = $statusLabel['label'] ?? $statusLabel['name'] ?? $statusValue;
                            $statusValue = $statusLabel['value'] ?? $statusValue;
                            } else {
                            $statusText = $statusLabel;
                            }
                            @endphp

                            <option value="{{ $statusValue }}">{{ $statusText }}</option>
                            @endforeach

                            @if(empty($statusOptions))
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                            @endif
                        </select>
                    </div>

                    <div class="filter-item">
                        <select class="filter-control" id="filterCity" wire:model.live="filters.city">

                            <option value="">كل المدن</option>

                            @foreach($cityOptions as $cityValue => $cityLabel)
                            @php
                            if (is_array($cityLabel)) {
                            $cityText = $cityLabel['label'] ?? $cityLabel['name'] ?? $cityValue;
                            $cityValue = $cityLabel['value'] ?? $cityValue;
                            } else {
                            $cityText = $cityLabel;
                            }
                            @endphp

                            <option value="{{ $cityValue }}">{{ $cityText }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-item">
                        <select class="filter-control" id="filterPrice" wire:model.live="filters.price">

                            <option value="">كل الأسعار</option>

                            @foreach($priceOptions as $priceValue => $priceLabel)
                            @php
                            if (is_array($priceLabel)) {
                            $priceText = $priceLabel['label'] ?? $priceLabel['name'] ?? $priceValue;
                            $priceValue = $priceLabel['value'] ?? $priceValue;
                            } else {
                            $priceText = $priceLabel;
                            }
                            @endphp

                            <option value="{{ $priceValue }}">{{ $priceText }}</option>
                            @endforeach

                            @if(empty($priceOptions))
                            <option value="0-500000">أقل من 500 ألف</option>
                            <option value="500000-1000000">500 ألف - مليون</option>
                            <option value="1000000-3000000">مليون - 3 مليون</option>
                            <option value="3000000-999999999">أكثر من 3 مليون</option>
                            @endif
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="button" class="btn-filter-action clear" wire:click="clearFilters">
                            <i class="fas fa-rotate-left"></i>
                            مسح
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============ منطقة العمل ============ -->
            <div class="map-workspace">

                <!-- القائمة الجانبية -->
                <aside class="sidebar">
                    <div class="sidebar-header">
                        <div class="sidebar-title">
                            <i class="fas fa-list"></i>
                            العقارات
                            <span class="count" id="listCount">{{ count($mapMarkers) }}</span>
                        </div>
                    </div>

                    <div class="properties-list" id="propertiesList">
                        @forelse($mapMarkers as $property)
                        <div class="property-card {{ (string) ($selectedId ?? '') === (string) ($property['id'] ?? '') ? 'active' : '' }}"
                            data-id="{{ $property['id'] ?? '' }}" wire:click="selectRecord({{ $property['id'] ?? 0 }})">

                            <div class="property-card-inner">
                                <div class="property-thumb">
                                    @if(!empty($property['image']))
                                    <img src="{{ $property['image'] }}" alt="">
                                    @else
                                    <i class="fas fa-building"></i>
                                    @endif

                                    <span
                                        class="property-badge {{ !empty($property['status']) ? 'for-sale' : 'sold' }}">
                                        {{ $property['status_label'] ?? 'غير محدد' }}
                                    </span>
                                </div>

                                <div class="property-info">
                                    <div class="property-title">
                                        {{ $property['title'] ?? 'بدون اسم' }}
                                    </div>

                                    <div class="property-location">
                                        <i class="fas fa-location-dot"></i>
                                        {{ $property['district'] ?? '' }}
                                        {{ !empty($property['city']) ? '، ' . $property['city'] : '' }}
                                    </div>

                                    <div class="property-meta">
                                        @if(!empty($property['area']))
                                        <span>
                                            <i class="fas fa-vector-square"></i>
                                            {{ $property['area'] }} م²
                                        </span>
                                        @endif

                                        @if(!empty($property['bedrooms']))
                                        <span>
                                            <i class="fas fa-bed"></i>
                                            {{ $property['bedrooms'] }}
                                        </span>
                                        @endif

                                        @if(!empty($property['bathrooms']))
                                        <span>
                                            <i class="fas fa-bath"></i>
                                            {{ $property['bathrooms'] }}
                                        </span>
                                        @endif
                                    </div>

                                    @if(isset($property['price']) && $property['price'] !== null)
                                    <div class="property-price">
                                        {{ number_format((float) $property['price']) }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-empty">
                            <i class="fas fa-map-location-dot"></i>
                            <h4>لا توجد عقارات مطابقة</h4>
                            <p>جرّب تعديل الفلاتر أو مسحها</p>
                        </div>
                        @endforelse
                    </div>
                </aside>

                <!-- الخريطة -->
                <main class="map-panel">
                    <div id="map" wire:ignore></div>

                    <!-- عداد النتائج -->
                    <div class="map-result-badge">
                        <i class="fas fa-location-dot"></i>
                        <strong id="resultCount">{{ count($mapMarkers) }}</strong>
                        عقار على الخريطة
                    </div>

                    <!-- أدوات الخريطة -->
                    <div class="map-controls">
                        <button type="button" class="map-ctrl-btn" onclick="zoomIn()" title="تكبير">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="map-ctrl-btn" onclick="zoomOut()" title="تصغير">
                            <i class="fas fa-minus"></i>
                        </button>

                        <button type="button" class="map-ctrl-btn" onclick="resetView()" title="إعادة ضبط">
                            <i class="fas fa-compress"></i>
                        </button>

                        <button type="button" class="map-ctrl-btn" id="satelliteBtn" onclick="toggleSatellite()"
                            title="قمر صناعي">
                            <i class="fas fa-satellite"></i>
                        </button>
                    </div>

                    <!-- مفتاح الخريطة -->
                    <div class="map-legend">
                        <div class="map-legend-title">
                            <i class="fas fa-circle-info"></i>
                            الدليل
                        </div>

                        <div class="legend-item">
                            <span class="legend-dot sale"></span>
                            نشط
                        </div>

                        <div class="legend-item">
                            <span class="legend-dot sold"></span>
                            غير نشط
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- ============ نافذة تفاصيل العقار ============ -->
        <div class="modal-overlay" id="propertyModal">
            <div class="modal">
                <div class="modal-header">
                    <div class="modal-header-icon">
                        <i class="fas fa-building"></i>
                    </div>

                    <div class="modal-header-text">
                        <h2>تفاصيل العقار</h2>
                        <p>معلومات كاملة عن العقار المحدد</p>
                    </div>

                    <button type="button" class="modal-close" onclick="closeModal('propertyModal')">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>

                <div class="modal-body" id="propertyModalBody"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('propertyModal')">
                        إغلاق
                    </button>

                    <button type="button" class="btn btn-primary" onclick="focusCurrentOnMap()">
                        <i class="fas fa-location-dot"></i>
                        عرض على الخريطة
                    </button>
                </div>
            </div>
        </div>

        <!-- ============ Toast ============ -->
        <div class="toast" id="toast">
            <i class="fas fa-circle-check"></i>
            <span id="toastMsg"></span>
        </div>

    </div>
</div>

@script
<script>
    (() => {
    /* ============================================================
       بيانات الخريطة من Livewire
    ============================================================ */

    let properties = @js($markers ?? []);
    let filteredProperties = [...properties];

    let currentProperty = null;
    let map = null;
    let markersLayer = null;
    let markerRefs = {};
    let satelliteLayer = null;
    let streetLayer = null;
    let currentView = 'list';
    let userLocationMarker = null;
    let toastTimeout = null;

    const mapElement = document.getElementById('map');

    if (!mapElement) {
        return;
    }

    /* ============================================================
       تسوية بيانات العلامة
    ============================================================ */

    function normalizeProperty(property) {
        return {
            ...property,
            id: property.id ?? null,
            title: property.title ?? 'بدون اسم',
            lat: Number(property.lat ?? property.latitude),
            lng: Number(property.lng ?? property.longitude),
            status: property.status ?? null,
            status_label: property.status_label ?? 'غير محدد',
            type: property.type ?? property.property_type ?? '',
            city: property.city ?? '',
            district: property.district ?? '',
            address: property.address ?? '',
            area: property.area ?? property.building_area ?? null,
            price: property.price ?? null,
            bedrooms: property.bedrooms ?? null,
            bathrooms: property.bathrooms ?? null,
            image: property.image ?? null
        };
    }

    properties = properties.map(normalizeProperty);
    filteredProperties = [...properties];

    /* ============================================================
       Helpers
    ============================================================ */

    function esc(value) {
        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatPrice(value) {
        const number = Number(value);

        if (!Number.isFinite(number)) {
            return '';
        }

        if (number >= 1000000) {
            return (number / 1000000).toFixed(
                number % 1000000 === 0 ? 0 : 1
            ) + ' مليون';
        }

        if (number >= 1000) {
            return (number / 1000).toFixed(
                number % 1000 === 0 ? 0 : 1
            ) + ' ألف';
        }

        return number.toLocaleString('ar-SA');
    }

    function formatPriceFull(value) {
        const number = Number(value);

        if (!Number.isFinite(number)) {
            return 'غير محدد';
        }

        return number.toLocaleString('ar-SA');
    }

    function getStatusClass(property) {
        const status = String(property.status ?? '').toLowerCase();

        if (
            status === '1' ||
            status === 'true' ||
            status === 'active' ||
            status === 'نشط'
        ) {
            return 'for-sale';
        }

        if (
            status === '0' ||
            status === 'false' ||
            status === 'inactive' ||
            status === 'غير نشط'
        ) {
            return 'sold';
        }

        return 'for-rent';
    }

    function getTypeIcon(property) {
        const type = String(property.type ?? '').toLowerCase();

        const icons = {
            apartment: 'fa-building',
            villa: 'fa-house',
            land: 'fa-mountain-sun',
            commercial: 'fa-store',
            office: 'fa-briefcase'
        };

        return icons[type] || 'fa-building';
    }

    function getTypeLabel(property) {
        const type = String(property.type ?? '').toLowerCase();

        const labels = {
            apartment: 'شقة',
            villa: 'فيلا',
            land: 'أرض',
            commercial: 'تجاري',
            office: 'مكتب'
        };

        return labels[type] || property.type || 'غير محدد';
    }

    /* ============================================================
       تهيئة الخريطة
    ============================================================ */

    function initMap() {
        if (map || typeof L === 'undefined') {
            return;
        }

        map = L.map(mapElement, {
            center: [24.7136, 46.6753],
            zoom: 6,
            zoomControl: false,
            attributionControl: true
        });

        streetLayer = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }
        ).addTo(map);

        satelliteLayer = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                maxZoom: 19,
                attribution: '&copy; Esri'
            }
        );

        markersLayer = L.layerGroup().addTo(map);

        renderMarkers();

        setTimeout(() => {
            if (map) {
                map.invalidateSize();
            }
        }, 300);
    }

    /* ============================================================
       أيقونة العلامة المخصصة
    ============================================================ */

    function createMarkerIcon(property, active = false) {
        const statusClass = getStatusClass(property);
        const iconClass = getTypeIcon(property);
        const activeClass = active ? ' active' : '';

        return L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="marker-pin ${statusClass}${activeClass}">
                    <i class="fas ${iconClass}"></i>
                </div>
            `,
            iconSize: [36, 36],
            iconAnchor: [18, 36],
            popupAnchor: [0, -36]
        });
    }

    /* ============================================================
       بناء محتوى البوب أب
    ============================================================ */

    function buildPopupContent(property) {
        const statusClass = getStatusClass(property);
        const typeIcon = getTypeIcon(property);
        const typeLabel = getTypeLabel(property);

        return `
            <div class="map-popup">
                <div class="map-popup-title">
                    <i class="fas ${typeIcon}"></i>
                    ${esc(property.title)}
                </div>

                <div class="map-popup-location">
                    <i class="fas fa-location-dot"></i>
                    ${esc(property.city)}
                    ${property.district ? '، ' + esc(property.district) : ''}
                </div>

                <div class="map-popup-status ${statusClass}">
                    ${esc(property.status_label)}
                </div>

                <div class="map-popup-type">
                    ${esc(typeLabel)}
                </div>

                ${property.area ? `
                    <div class="map-popup-area">
                        <i class="fas fa-vector-square"></i>
                        ${esc(property.area)} م²
                    </div>
                ` : ''}
            </div>
        `;
    }

    /* ============================================================
       رسم العلامات على الخريطة
    ============================================================ */

    function renderMarkers() {
        if (!map || !markersLayer) {
            return;
        }

        markersLayer.clearLayers();
        markerRefs = {};

        const validProperties = filteredProperties.filter(property => {
            return property.id !== null &&
                Number.isFinite(property.lat) &&
                Number.isFinite(property.lng) &&
                property.lat >= -90 &&
                property.lat <= 90 &&
                property.lng >= -180 &&
                property.lng <= 180;
        });

        validProperties.forEach(property => {
            const marker = L.marker(
                [property.lat, property.lng],
                {
                    icon: createMarkerIcon(property)
                }
            )
            .bindPopup(
                buildPopupContent(property),
                {
                    maxWidth: 280,
                    minWidth: 220,
                    closeButton: true
                }
            )
            .on('click', () => {
                highlightCard(property.id);
            });

            marker.addTo(markersLayer);
            markerRefs[String(property.id)] = marker;
        });

        updateCounters();

        if (validProperties.length > 0 && !currentProperty) {
            const bounds = L.latLngBounds(
                validProperties.map(property => [
                    property.lat,
                    property.lng
                ])
            );

            map.fitBounds(bounds, {
                padding: [40, 40],
                maxZoom: 14
            });
        }
    }

    /* ============================================================
       تحديث العدادات
    ============================================================ */

    function updateCounters() {
        const listCount = document.getElementById('listCount');
        const resultCount = document.getElementById('resultCount');

        const validCount = filteredProperties.filter(property => {
            return Number.isFinite(property.lat) &&
                Number.isFinite(property.lng);
        }).length;

        if (listCount) {
            listCount.textContent = filteredProperties.length;
        }

        if (resultCount) {
            resultCount.textContent = validCount;
        }
    }

    /* ============================================================
       عرض قائمة العقارات
    ============================================================ */

    function renderPropertiesList() {
        const list = document.getElementById('propertiesList');

        if (!list) {
            return;
        }

        updateCounters();

        if (filteredProperties.length === 0) {
            list.innerHTML = `
                <div class="list-empty">
                    <i class="fas fa-map-location-dot"></i>
                    <h4>لا توجد عقارات مطابقة</h4>
                    <p>جرّب تعديل الفلاتر أو مسحها</p>
                </div>
            `;

            return;
        }

        list.innerHTML = filteredProperties.map(property => {
            const isActive = String(currentProperty?.id) === String(property.id);
            const statusClass = getStatusClass(property);
            const typeIcon = getTypeIcon(property);

            return `
                <div
                    class="property-card ${isActive ? 'active' : ''}"
                    data-id="${esc(property.id)}"
                    onclick="selectProperty('${esc(property.id)}')">

                    <div class="property-card-inner">

                        <div class="property-thumb">
                            ${
                                property.image
                                    ? `<img src="${esc(property.image)}" alt="">`
                                    : `<i class="fas ${typeIcon}"></i>`
                            }

                            <span class="property-badge ${statusClass}">
                                ${esc(property.status_label)}
                            </span>
                        </div>

                        <div class="property-info">

                            <div class="property-title">
                                ${esc(property.title)}
                            </div>

                            <div class="property-location">
                                <i class="fas fa-location-dot"></i>
                                ${esc(property.district)}
                                ${property.city ? '، ' + esc(property.city) : ''}
                            </div>

                            <div class="property-meta">
                                ${
                                    property.area
                                        ? `<span><i class="fas fa-vector-square"></i> ${esc(property.area)} م²</span>`
                                        : ''
                                }

                                ${
                                    Number(property.bedrooms) > 0
                                        ? `<span><i class="fas fa-bed"></i> ${esc(property.bedrooms)}</span>`
                                        : ''
                                }

                                ${
                                    Number(property.bathrooms) > 0
                                        ? `<span><i class="fas fa-bath"></i> ${esc(property.bathrooms)}</span>`
                                        : ''
                                }
                            </div>

                            ${
                                property.price !== null
                                    ? `<div class="property-price">${formatPrice(property.price)}</div>`
                                    : ''
                            }

                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    /* ============================================================
       اختيار عقار
    ============================================================ */

    function selectProperty(id) {
        const property = properties.find(item =>
            String(item.id) === String(id)
        );

        if (!property) {
            return;
        }

        currentProperty = property;

        renderPropertiesList();

        const marker = markerRefs[String(property.id)];

        if (marker && map) {
            map.flyTo(
                [property.lat, property.lng],
                Math.max(map.getZoom(), 13),
                { duration: 0.8 }
            );

            setTimeout(() => {
                marker.openPopup();
            }, 850);
        }

        updateActiveMarkers();
    }

    function updateActiveMarkers() {
        Object.keys(markerRefs).forEach(id => {
            const property = properties.find(item =>
                String(item.id) === String(id)
            );

            if (!property) {
                return;
            }

            const isActive =
                String(currentProperty?.id) === String(property.id);

            markerRefs[id].setIcon(
                createMarkerIcon(property, isActive)
            );
        });
    }

    function highlightCard(id) {
        currentProperty = properties.find(item =>
            String(item.id) === String(id)
        ) || null;

        renderPropertiesList();
        updateActiveMarkers();

        const card = document.querySelector(
            `.property-card[data-id="${CSS.escape(String(id))}"]`
        );

        if (card) {
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }
    }

    /* ============================================================
       نافذة تفاصيل العقار
    ============================================================ */

    function openPropertyModal(id) {
        const property = properties.find(item =>
            String(item.id) === String(id)
        );

        if (!property) {
            return;
        }

        currentProperty = property;

        const typeIcon = getTypeIcon(property);
        const typeLabel = getTypeLabel(property);
        const statusClass = getStatusClass(property);

        document.getElementById('propertyModalBody').innerHTML = `
            <div class="property-detail-hero">
                ${
                    property.image
                        ? `<img src="${esc(property.image)}" alt="">`
                        : `<i class="fas ${typeIcon}"></i>`
                }
            </div>

            <div class="property-detail-title">
                ${esc(property.title)}
            </div>

            <div class="property-detail-location">
                <i class="fas fa-location-dot"></i>
                ${esc(property.address || property.city || '')}
            </div>

            <div class="detail-price-box">
                <div>
                    <div class="detail-price-label">السعر</div>
                    <div class="detail-price-value">
                        ${
                            property.price !== null
                                ? formatPriceFull(property.price)
                                : 'غير محدد'
                        }
                    </div>
                </div>

                <div style="text-align:center;">
                    <div class="detail-price-label">الحالة</div>
                    <div class="detail-price-value ${statusClass}"
                         style="font-size:0.9rem;">
                        ${esc(property.status_label)}
                    </div>
                </div>
            </div>

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-item-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="detail-item-content">
                        <div class="detail-item-label">نوع العقار</div>
                        <div class="detail-item-value">${esc(typeLabel)}</div>
                    </div>
                </div>

                ${
                    property.area
                        ? `
                            <div class="detail-item">
                                <div class="detail-item-icon">
                                    <i class="fas fa-vector-square"></i>
                                </div>
                                <div class="detail-item-content">
                                    <div class="detail-item-label">المساحة</div>
                                    <div class="detail-item-value">${esc(property.area)} م²</div>
                                </div>
                            </div>
                        `
                        : ''
                }

                ${
                    Number(property.bedrooms) > 0
                        ? `
                            <div class="detail-item">
                                <div class="detail-item-icon">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div class="detail-item-content">
                                    <div class="detail-item-label">غرف النوم</div>
                                    <div class="detail-item-value">${esc(property.bedrooms)}</div>
                                </div>
                            </div>
                        `
                        : ''
                }

                ${
                    Number(property.bathrooms) > 0
                        ? `
                            <div class="detail-item">
                                <div class="detail-item-icon">
                                    <i class="fas fa-bath"></i>
                                </div>
                                <div class="detail-item-content">
                                    <div class="detail-item-label">الحمامات</div>
                                    <div class="detail-item-value">${esc(property.bathrooms)}</div>
                                </div>
                            </div>
                        `
                        : ''
                }

                <div class="detail-item">
                    <div class="detail-item-icon">
                        <i class="fas fa-city"></i>
                    </div>
                    <div class="detail-item-content">
                        <div class="detail-item-label">المدينة</div>
                        <div class="detail-item-value">${esc(property.city)}</div>
                    </div>
                </div>

                ${
                    property.owner
                        ? `
                            <div class="detail-item">
                                <div class="detail-item-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="detail-item-content">
                                    <div class="detail-item-label">المالك</div>
                                    <div class="detail-item-value">${esc(property.owner)}</div>
                                </div>
                            </div>
                        `
                        : ''
                }

            </div>

            <div class="detail-section-title">
                <i class="fas fa-align-right"></i>
                الوصف
            </div>

            <div class="detail-notes">
                ${esc(property.description || 'لا يوجد وصف.')}
            </div>
        `;

        openModal('propertyModal');
    }

    function focusCurrentOnMap() {
        if (!currentProperty) {
            return;
        }

        closeModal('propertyModal');

        setTimeout(() => {
            selectProperty(currentProperty.id);
        }, 250);
    }

    /* ============================================================
       أدوات الخريطة
    ============================================================ */

    function zoomIn() {
        if (map) {
            map.zoomIn();
        }
    }

    function zoomOut() {
        if (map) {
            map.zoomOut();
        }
    }

    function resetView() {
        if (!map) {
            return;
        }

        const validProperties = filteredProperties.filter(property =>
            Number.isFinite(property.lat) &&
            Number.isFinite(property.lng)
        );

        currentProperty = null;

        if (validProperties.length > 0) {
            const bounds = L.latLngBounds(
                validProperties.map(property => [
                    property.lat,
                    property.lng
                ])
            );

            map.flyToBounds(bounds, {
                padding: [40, 40],
                maxZoom: 14,
                duration: 0.8
            });
        } else {
            map.flyTo([24.7136, 46.6753], 6, {
                duration: 0.8
            });
        }

        renderPropertiesList();
        updateActiveMarkers();
    }

    function toggleSatellite() {
        const button = document.getElementById('satelliteBtn');

        if (!map) {
            return;
        }

        if (map.hasLayer(streetLayer)) {
            map.removeLayer(streetLayer);
            satelliteLayer.addTo(map);

            button?.classList.add('active');

            showToast(
                'تم تفعيل عرض القمر الصناعي',
                'success'
            );
        } else {
            map.removeLayer(satelliteLayer);
            streetLayer.addTo(map);

            button?.classList.remove('active');

            showToast(
                'تم تفعيل عرض الخريطة العادية',
                'success'
            );
        }
    }

    function locateMe() {
        if (!navigator.geolocation) {
            showToast(
                'المتصفح لا يدعم تحديد الموقع',
                'error'
            );

            return;
        }

        showToast(
            'جارٍ تحديد موقعك...',
            'success'
        );

        navigator.geolocation.getCurrentPosition(
            position => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                if (userLocationMarker && map) {
                    map.removeLayer(userLocationMarker);
                }

                userLocationMarker = L.circleMarker(
                    [latitude, longitude],
                    {
                        radius: 8,
                        fillColor: '#0284C7',
                        color: '#fff',
                        weight: 3,
                        opacity: 1,
                        fillOpacity: 1
                    }
                )
                .addTo(map)
                .bindPopup('موقعك الحالي')
                .openPopup();

                map.flyTo(
                    [latitude, longitude],
                    13,
                    { duration: 1 }
                );

                showToast(
                    'تم تحديد موقعك بنجاح',
                    'success'
                );
            },

            error => {
                console.error(error);

                showToast(
                    'تعذر تحديد الموقع، تحقق من الأذونات',
                    'error'
                );
            },

            {
                enableHighAccuracy: true,
                timeout: 8000
            }
        );
    }

    /* ============================================================
       إضافة عقار
    ============================================================ */

    function openAddProperty() {
        showToast(
            'يمكن ربط هذا الزر بنموذج إضافة العقار العام',
            'success'
        );
    }

    /* ============================================================
       عرض القائمة/الشبكة
    ============================================================ */

    function setView(mode) {
        currentView = mode;

        const viewList = document.getElementById('viewList');
        const viewGrid = document.getElementById('viewGrid');

        viewList?.classList.toggle('active', mode === 'list');
        viewGrid?.classList.toggle('active', mode === 'grid');

        const list = document.getElementById('propertiesList');

        if (!list) {
            return;
        }

        if (mode === 'grid') {
            list.style.display = 'grid';
            list.style.gridTemplateColumns = '1fr 1fr';
            list.style.gap = '0.4rem';
        } else {
            list.style.display = 'block';
            list.style.gridTemplateColumns = '';
            list.style.gap = '';
        }
    }

    /* ============================================================
       النوافذ
    ============================================================ */

    function openModal(id) {
        const element = document.getElementById(id);

        if (!element) {
            return;
        }

        element.classList.add('show');
    }

    function closeModal(id) {
        const element = document.getElementById(id);

        if (!element) {
            return;
        }

        element.classList.remove('show');
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', event => {
            if (event.target === overlay) {
                closeModal(overlay.id);
            }
        });
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.show').forEach(modal => {
                closeModal(modal.id);
            });
        }
    });

    /* ============================================================
       Toast
    ============================================================ */

    function showToast(message, type = 'success') {
        clearTimeout(toastTimeout);

        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toastMsg');

        if (!toast || !toastMsg) {
            return;
        }

        toast.className = 'toast ' + type;
        toastMsg.textContent = message;

        const icon = toast.querySelector('i');

        if (icon) {
            icon.className = type === 'success'
                ? 'fas fa-circle-check'
                : 'fas fa-circle-exclamation';
        }

        toast.classList.add('show');

        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    /* ============================================================
       تحديث بيانات الخريطة من Livewire
    ============================================================ */

    function updateMapData(newMarkers) {
        if (!Array.isArray(newMarkers)) {
            return;
        }

        properties = newMarkers.map(normalizeProperty);
        filteredProperties = [...properties];

        if (
            currentProperty &&
            !properties.some(property =>
                String(property.id) === String(currentProperty.id)
            )
        ) {
            currentProperty = null;
        }

        renderPropertiesList();
        renderMarkers();
    }

    /*
     * مراقبة خاصية markers في مكوّن Livewire.
     * لا يوجد طلب AJAX مستقل ولا جلب بيانات من JavaScript.
     */
    $wire.$watch('markers', value => {
        updateMapData(value);
    });

    /* ============================================================
       التهيئة
    ============================================================ */

    window.selectProperty = selectProperty;
    window.openPropertyModal = openPropertyModal;
    window.focusCurrentOnMap = focusCurrentOnMap;

    window.zoomIn = zoomIn;
    window.zoomOut = zoomOut;
    window.resetView = resetView;
    window.toggleSatellite = toggleSatellite;
    window.locateMe = locateMe;

    window.openAddProperty = openAddProperty;
    window.setView = setView;

    window.openModal = openModal;
    window.closeModal = closeModal;
    window.showToast = showToast;

    initMap();
    renderPropertiesList();
    renderMarkers();

    /*
     * معالجة تغيّر أبعاد الخريطة بعد تحديث Livewire.
     */
    requestAnimationFrame(() => {
        if (map) {
            map.invalidateSize();
        }
    });
})();
</script>
@endscript

</div>
</div>