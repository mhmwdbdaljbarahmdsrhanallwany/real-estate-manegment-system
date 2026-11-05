<div wire:key="grid-{{ $entityKey }}">
    @php
    $primaryKey = $config['primary_key'] ?? 'id';
    $nameField = $config['name_field'] ?? $primaryKey;
    $entityName = $config['entity_name'] ?? 'سجلات';
    $entitySingle = $config['entity_singular'] ?? 'سجل';
    $subTitle = $config['sub_section_title'] ?? 'العناصر المرتبطة';
    $subIcon = $config['sub_section_icon'] ?? 'fas fa-list';
    $mainIcon = $config['icon'] ?? 'fas fa-table';
    $statusLabels = $config['status']['labels'] ?? ['active'=>'نشط','inactive'=>'غير نشط','blocked'=>'محظور'];
    $relationsCfg = $config['relations'] ?? [];
    $firstRelation = !empty($relationsCfg) ? array_key_first($relationsCfg) : null;
    @endphp
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .gg-wrap {
            font-family: 'Tajawal', system-ui, sans-serif;
            background: #F7F9FC;
            min-height: 100vh;
            color: #1E293B;
        }

        .gg-wrap * {
            box-sizing: border-box;
        }

        /* ====== Toolbar ====== */
        .gg-toolbar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: .85rem 1.2rem;
            display: flex;
            align-items: center;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .gg-toolbar-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0F2B46, #1B4D7A);
            color: #E8CB5C;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 6px 18px rgba(15, 43, 70, .25);
            flex-shrink: 0;
        }

        .gg-toolbar-text h1 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0F2B46;
            margin: 0 0 .15rem;
        }

        .gg-toolbar-text p {
            font-size: .74rem;
            color: #64748B;
            margin: 0;
        }

        .gg-toolbar-actions {
            margin-inline-start: auto;
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
        }

        .gg-btn {
            padding: .5rem 1rem;
            border-radius: 8px;
            border: none;
            font-family: inherit;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            transition: all .25s ease;
            height: 36px;
            white-space: nowrap;
        }

        .gg-btn-primary {
            background: linear-gradient(135deg, #C9A227, #E8CB5C);
            color: #0F2B46;
            box-shadow: 0 3px 10px rgba(201, 162, 39, .35);
        }

        .gg-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(201, 162, 39, .5);
        }

        .gg-btn-blue {
            background: linear-gradient(135deg, #1B4D7A, #2563EB);
            color: #fff;
            box-shadow: 0 3px 10px rgba(27, 77, 122, .35);
        }

        .gg-btn-blue:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(27, 77, 122, .5);
        }

        /* ====== Filters ====== */
        .gg-filters {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: .9rem 1.2rem;
        }

        .gg-filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: .55rem;
        }

        .gg-field {
            display: flex;
            flex-direction: column;
            gap: .25rem;
        }

        .gg-field-wide {
            grid-column: span 2;
        }

        .gg-label {
            font-size: .68rem;
            font-weight: 700;
            color: #64748B;
            display: flex;
            align-items: center;
            gap: .25rem;
        }

        .gg-label i {
            color: #C9A227;
            font-size: .68rem;
        }

        .gg-input,
        .gg-select {
            width: 100%;
            padding: .5rem .7rem;
            font-family: inherit;
            font-size: .8rem;
            background: #EEF2F7;
            border: 1.5px solid transparent;
            border-radius: 8px;
            color: #1E293B;
            outline: none;
            transition: all .25s ease;
            height: 36px;
        }

        .gg-input:focus,
        .gg-select:focus {
            background: #fff;
            border-color: #1B4D7A;
            box-shadow: 0 0 0 3px rgba(27, 77, 122, .1);
        }

        .gg-search-wrap {
            position: relative;
        }

        .gg-search-wrap i {
            position: absolute;
            right: .7rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: .75rem;
            pointer-events: none;
        }

        .gg-search-wrap input {
            padding-right: 2rem;
        }

        .gg-btn-clear {
            background: #fff;
            color: #64748B;
            border: 1.5px solid #E2E8F0;
            padding: .5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: .78rem;
            font-weight: 600;
            font-family: inherit;
            height: 36px;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            transition: all .25s ease;
        }

        .gg-btn-clear:hover {
            background: #FEF2F2;
            color: #DC2626;
            border-color: #DC2626;
        }

        /* ====== Table ====== */
        .gg-content {
            padding: 1rem 1.2rem;
        }

        .gg-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(15, 43, 70, .06);
            overflow: hidden;
        }

        .gg-table-wrap {
            overflow-x: auto;
        }

        .gg-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .gg-table thead {
            background: linear-gradient(135deg, rgba(15, 43, 70, .03), rgba(201, 162, 39, .05));
            border-bottom: 2px solid #E2E8F0;
        }

        .gg-table th {
            padding: .75rem .85rem;
            font-size: .7rem;
            font-weight: 800;
            color: #0F2B46;
            text-align: right;
            white-space: nowrap;
            letter-spacing: .02em;
        }

        .gg-table td {
            padding: .7rem .85rem;
            font-size: .8rem;
            color: #1E293B;
            border-bottom: 1px solid #EEF2F7;
            vertical-align: middle;
        }

        .gg-table tbody tr {
            transition: background .2s ease;
        }

        .gg-table tbody tr:hover {
            background: #F7F9FC;
        }

        .gg-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ====== Cells ====== */
        .gg-user {
            display: flex;
            align-items: center;
            gap: .55rem;
        }

        .gg-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0F2B46, #1B4D7A);
            color: #E8CB5C;
            font-weight: 800;
            font-size: .85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
        }

        .gg-avatar.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: -2px;
            width: 12px;
            height: 12px;
            background: #059669;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .gg-avatar.inactive::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: -2px;
            width: 12px;
            height: 12px;
            background: #DC2626;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .gg-avatar.blocked {
            background: linear-gradient(135deg, #64748B, #94A3B8);
            color: #fff;
        }

        .gg-user-name {
            font-weight: 700;
            color: #1E293B;
            font-size: .85rem;
            margin-bottom: .1rem;
        }

        .gg-user-sub {
            font-size: .7rem;
            color: #64748B;
        }

        .gg-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .gg-pill.active {
            background: rgba(5, 150, 105, .12);
            color: #059669;
        }

        .gg-pill.inactive {
            background: rgba(220, 38, 38, .1);
            color: #DC2626;
        }

        .gg-pill.blocked {
            background: rgba(220, 38, 38, .15);
            color: #DC2626;
        }

        .gg-pill.sky {
            background: rgba(2, 132, 199, .1);
            color: #0284C7;
        }

        .gg-pill.gold {
            background: rgba(201, 162, 39, .12);
            color: #92710A;
        }

        .gg-pill.gray {
            background: #EEF2F7;
            color: #64748B;
        }

        /* ====== Actions ====== */
        .gg-actions {
            display: flex;
            gap: .3rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .gg-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1.5px solid #E2E8F0;
            background: #fff;
            cursor: pointer;
            font-size: .75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s ease;
            color: #64748B;
        }

        .gg-action-btn:hover {
            transform: translateY(-1px);
        }

        .gg-action-btn.view:hover {
            background: rgba(2, 132, 199, .08);
            color: #0284C7;
            border-color: rgba(2, 132, 199, .3);
        }

        .gg-action-btn.edit:hover {
            background: rgba(27, 77, 122, .08);
            color: #1B4D7A;
            border-color: rgba(27, 77, 122, .3);
        }

        .gg-action-btn.block:hover {
            background: rgba(217, 119, 6, .08);
            color: #D97706;
            border-color: rgba(217, 119, 6, .3);
        }

        .gg-action-btn.unblock:hover {
            background: rgba(5, 150, 105, .08);
            color: #059669;
            border-color: rgba(5, 150, 105, .3);
        }

        .gg-action-btn.delete:hover {
            background: rgba(220, 38, 38, .08);
            color: #DC2626;
            border-color: rgba(220, 38, 38, .3);
        }

        /* ====== Empty ====== */
        .gg-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: #94A3B8;
        }

        .gg-empty i {
            font-size: 2.5rem;
            margin-bottom: .8rem;
            display: block;
            opacity: .5;
        }

        .gg-empty h3 {
            font-size: 1rem;
            color: #64748B;
            margin: 0 0 .3rem;
        }

        .gg-empty p {
            font-size: .82rem;
            margin: 0;
        }

        /* ====== Pagination ====== */
        .gg-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 1rem;
            background: #fff;
            border-top: 1px solid #E2E8F0;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .gg-pagination-info {
            font-size: .75rem;
            color: #64748B;
        }

        .gg-pagination-controls {
            display: flex;
            gap: .25rem;
        }

        .gg-page-btn {
            min-width: 32px;
            height: 32px;
            padding: 0 .5rem;
            border: 1.5px solid #E2E8F0;
            background: #fff;
            color: #64748B;
            border-radius: 6px;
            cursor: pointer;
            font-size: .78rem;
            font-weight: 600;
            font-family: inherit;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .gg-page-btn:hover:not(:disabled) {
            background: rgba(27, 77, 122, .06);
            color: #1B4D7A;
            border-color: rgba(27, 77, 122, .3);
        }

        .gg-page-btn.active {
            background: #1B4D7A;
            color: #fff;
            border-color: #1B4D7A;
        }

        .gg-page-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* ====== Modals ====== */
        .gg-overlay {
            position: fixed;
            inset: 0;
            z-index: 1000;
            background: rgba(15, 43, 70, .5);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            animation: ggFade .2s ease;
        }

        @keyframes ggFade {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        .gg-modal {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(15, 43, 70, .25);
            width: 100%;
            max-width: 640px;
            max-height: 92vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: ggModalUp .3s cubic-bezier(.34, 1.56, .64, 1);
        }

        .gg-modal.small {
            max-width: 460px;
        }

        .gg-modal.large {
            max-width: 820px;
        }

        @keyframes ggModalUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(.96)
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1)
            }
        }

        .gg-modal-header {
            padding: .9rem 1.15rem;
            background: linear-gradient(135deg, #0F2B46, #1B4D7A);
            color: #fff;
            display: flex;
            align-items: center;
            gap: .65rem;
            position: relative;
        }

        .gg-modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #C9A227, #E8CB5C, #C9A227);
        }

        .gg-modal-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #C9A227, #E8CB5C);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0F2B46;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .gg-modal-title h2 {
            font-size: .98rem;
            font-weight: 700;
            margin: 0 0 .05rem;
        }

        .gg-modal-title p {
            font-size: .7rem;
            color: rgba(255, 255, 255, .75);
            margin: 0;
        }

        .gg-modal-close {
            margin-inline-start: auto;
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background: rgba(255, 255, 255, .1);
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: .8rem;
            transition: background .2s ease;
        }

        .gg-modal-close:hover {
            background: rgba(220, 38, 38, .3);
        }

        .gg-modal-body {
            padding: 1.1rem;
            overflow-y: auto;
            flex: 1;
        }

        .gg-modal-footer {
            padding: .8rem 1.15rem;
            border-top: 1px solid #E2E8F0;
            background: #F7F9FC;
            display: flex;
            gap: .4rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .gg-btn-secondary {
            background: #fff;
            color: #1E293B;
            border: 1.5px solid #E2E8F0;
        }

        .gg-btn-secondary:hover {
            background: #F7F9FC;
        }

        .gg-btn-danger {
            background: linear-gradient(135deg, #DC2626, #EF4444);
            color: #fff;
            box-shadow: 0 3px 10px rgba(220, 38, 38, .3);
        }

        .gg-btn-danger:hover {
            transform: translateY(-1px);
        }

        .gg-btn-warning {
            background: linear-gradient(135deg, #D97706, #F59E0B);
            color: #fff;
            box-shadow: 0 3px 10px rgba(217, 119, 6, .3);
        }

        .gg-btn-success {
            background: linear-gradient(135deg, #059669, #10B981);
            color: #fff;
            box-shadow: 0 3px 10px rgba(5, 150, 105, .3);
        }

        /* ====== Details in View Modal ====== */
        .gg-details-header {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #E2E8F0;
            margin-bottom: 1rem;
        }

        .gg-details-avatar {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            background: linear-gradient(135deg, #0F2B46, #1B4D7A);
            color: #E8CB5C;
            font-weight: 800;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
        }

        .gg-details-avatar.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: -4px;
            width: 16px;
            height: 16px;
            background: #059669;
            border: 3px solid #fff;
            border-radius: 50%;
        }

        .gg-details-avatar.inactive::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: -4px;
            width: 16px;
            height: 16px;
            background: #DC2626;
            border: 3px solid #fff;
            border-radius: 50%;
        }

        .gg-details-avatar.blocked {
            background: linear-gradient(135deg, #64748B, #94A3B8);
            color: #fff;
        }

        .gg-details-name {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0F2B46;
            margin-bottom: .2rem;
        }

        .gg-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .gg-detail-item {
            background: #F7F9FC;
            border-radius: 10px;
            padding: .6rem .75rem;
            border: 1px solid #E2E8F0;
        }

        .gg-detail-item-label {
            font-size: .65rem;
            color: #64748B;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .25rem;
            margin-bottom: .2rem;
        }

        .gg-detail-item-label i {
            color: #C9A227;
        }

        .gg-detail-item-value {
            font-size: .82rem;
            color: #1E293B;
            font-weight: 600;
            word-break: break-word;
        }

        .gg-detail-item-value.empty {
            color: #94A3B8;
            font-style: italic;
            font-weight: 400;
        }

        /* ====== Relations ====== */
        .gg-relations {
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 1px dashed #E2E8F0;
        }

        .gg-relations-title {
            font-size: .85rem;
            font-weight: 800;
            color: #0F2B46;
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: .6rem;
        }

        .gg-relations-title i {
            color: #C9A227;
        }

        .gg-relations-title .gg-badge {
            background: #EEF2F7;
            color: #1B4D7A;
            padding: .1rem .55rem;
            border-radius: 999px;
            font-size: .68rem;
            font-weight: 700;
        }

        .gg-rel-list {
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }

        .gg-rel-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .7rem;
            background: #F7F9FC;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
        }

        .gg-rel-item.blocked {
            background: rgba(220, 38, 38, .05);
            border-color: rgba(220, 38, 38, .2);
        }

        .gg-rel-avatar {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, #0284C7, #38BDF8);
            color: #fff;
            font-weight: 700;
            font-size: .8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .gg-rel-avatar.blocked {
            background: linear-gradient(135deg, #64748B, #94A3B8);
        }

        .gg-rel-info {
            flex: 1;
            min-width: 0;
        }

        .gg-rel-name {
            font-size: .82rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: .1rem;
        }

        .gg-rel-meta {
            display: flex;
            align-items: center;
            gap: .4rem;
            flex-wrap: wrap;
            font-size: .7rem;
            color: #64748B;
        }

        .gg-rel-meta span {
            display: flex;
            align-items: center;
            gap: .2rem;
        }

        /* ====== Confirm ====== */
        .gg-confirm {
            text-align: center;
            padding: .5rem 0;
        }

        .gg-confirm-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto .75rem;
        }

        .gg-confirm-icon.warning {
            background: rgba(217, 119, 6, .1);
            color: #D97706;
        }

        .gg-confirm-icon.danger {
            background: rgba(220, 38, 38, .1);
            color: #DC2626;
        }

        .gg-confirm-icon.success {
            background: rgba(5, 150, 105, .1);
            color: #059669;
        }

        .gg-confirm h3 {
            font-size: .95rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0 0 .3rem;
        }

        .gg-confirm p {
            font-size: .78rem;
            color: #64748B;
            margin: 0;
            line-height: 1.5;
        }

        .gg-confirm strong {
            color: #0F2B46;
        }

        /* ====== Toast ====== */
        .gg-toast {
            position: fixed;
            bottom: 1.25rem;
            left: 50%;
            transform: translateX(-50%) translateY(150%);
            background: #0F2B46;
            color: #fff;
            padding: .65rem 1.2rem;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(15, 43, 70, .3);
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .8rem;
            font-weight: 600;
            z-index: 2000;
            transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
            max-width: 90vw;
        }

        .gg-toast.show {
            transform: translateX(-50%) translateY(0);
        }

        .gg-toast.success {
            border-right: 4px solid #059669;
        }

        .gg-toast.error {
            border-right: 4px solid #DC2626;
        }

        .gg-toast.success i {
            color: #059669;
        }

        .gg-toast.error i {
            color: #DC2626;
        }

        /* ====== Responsive ====== */
        @media (max-width:768px) {
            .gg-toolbar {
                padding: .7rem .9rem;
            }

            .gg-toolbar-text h1 {
                font-size: .95rem;
            }

            .gg-toolbar-actions {
                width: 100%;
            }

            .gg-btn {
                flex: 1;
                justify-content: center;
            }

            .gg-content {
                padding: .75rem;
            }

            .gg-filters {
                padding: .75rem .9rem;
            }

            .gg-table th,
            .gg-table td {
                padding: .55rem .6rem;
                font-size: .72rem;
            }

            .gg-modal-footer {
                flex-direction: column-reverse;
            }

            .gg-modal-footer .gg-btn {
                width: 100%;
            }
        }
    </style>

    <div class="gg-wrap">

        {{-- ===== Toolbar ===== --}}
        <div class="gg-toolbar">
            <div class="gg-toolbar-icon"><i class="{{ $mainIcon }}"></i></div>
            <div class="gg-toolbar-text">
                <h1>{{ $config['title'] ?? 'إدارة '.$entityName }}</h1>
                <p>{{ $config['subtitle'] ?? 'عرض وإدارة '.$entityName }}</p>
            </div>
            <div class="gg-toolbar-actions">
                @if (!empty($config['actions']['create']))
                <button type="button" class="gg-btn gg-btn-primary"
                    wire:click="$dispatch('open-universal-form', { entityKey: '{{ $entityKey }}', mode: 'create' })">
                    <i class="fas fa-plus"></i> إضافة {{ $entitySingle }}
                </button>
                @endif
            </div>
        </div>

        {{-- ===== Filters ===== --}}
        <div class="gg-filters">
            <div class="gg-filters-grid">

                {{-- البحث --}}
                <div class="gg-field gg-field-wide">
                    <label class="gg-label"><i class="fas fa-search"></i> بحث</label>
                    <div class="gg-search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" class="gg-input" wire:model.live.debounce.400ms="search"
                            placeholder="بحث في {{ $entityName }}...">
                    </div>
                </div>

                {{-- الفلاتر من config --}}
                @foreach (($config['filters'] ?? []) as $filter)
                @php
                $fk = $filter['key'] ?? '';
                $ftype = $filter['type'] ?? 'select';
                if ($ftype === 'sort') continue;
                @endphp
                @if ($fk)
                @if ($ftype === 'date')
                <div class="gg-field">
                    <label class="gg-label">
                        <i class="{{ $filter['icon'] ?? 'fas fa-calendar' }}"></i>
                        {{ $filter['label'] ?? $fk }}
                    </label>
                    <input type="date" class="gg-input" wire:model.live="filters.{{ $fk }}">
                </div>
                @else
                <div class="gg-field">
                    <label class="gg-label">
                        <i class="{{ $filter['icon'] ?? 'fas fa-filter' }}"></i>
                        {{ $filter['label'] ?? $fk }}
                    </label>
                    <select class="gg-select" wire:model.live="filters.{{ $fk }}">
                        @if (!empty($filter['options_source']) && $filter['options_source'] === 'organization_id')
                        <option value="all">الكل</option>
                        @foreach ($organizations as $org)
                        <option value="{{ $org['id'] }}">{{ $org['name'] }}</option>
                        @endforeach
                        @else
                        @foreach (($filter['options'] ?? [['value'=>'all','label'=>'الكل']]) as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                @endif
                @endif
                @endforeach

                {{-- ترتيب --}}
                @foreach (($config['filters'] ?? []) as $filter)
                @if (($filter['type'] ?? '') === 'sort')
                <div class="gg-field">
                    <label class="gg-label"><i class="fas fa-sort"></i> الترتيب</label>
                    <select class="gg-select" wire:model.live="filters.sort">
                        @foreach (($filter['options'] ?? []) as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @endforeach

                {{-- مسح --}}
                <div class="gg-field" style="justify-content:flex-end;">
                    <label class="gg-label">&nbsp;</label>
                    <button type="button" class="gg-btn-clear" wire:click="clearFilters">
                        <i class="fas fa-rotate-left"></i> مسح
                    </button>
                </div>

            </div>
        </div>

        {{-- ===== Table ===== --}}
        <div class="gg-content">
            <div class="gg-card">

                @if (count($records['items']) === 0)
                <div class="gg-empty">
                    <i class="fas fa-inbox"></i>
                    <h3>لا توجد {{ $entityName }}</h3>
                    <p>لم يتم العثور على أي نتائج مطابقة.</p>
                </div>
                @else
                <div class="gg-table-wrap">
                    <table class="gg-table">
                        <thead>
                            <tr>
                                <th style="width:220px;">{{ $entitySingle }}</th>
                                @foreach (($config['main_fields'] ?? []) as $field)
                                @php
                                $key = $field['key'] ?? '';
                                $isList = $field['show_in_list'] ?? false;
                                $isName = in_array($key, [$nameField, 'phone', 'id_number', 'status'], true);
                                @endphp
                                @if ($isList || ($isName && !in_array($key, ['full_name','name'])))
                                @if (!in_array($key, ['full_name','name','notes','address']))
                                <th>{{ $field['label'] ?? $key }}</th>
                                @endif
                                @endif
                                @endforeach
                                <th>الحالة</th>
                                <th style="width:160px; text-align:left;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records['items'] as $row)
                            @php
                            $rid = $row[$primaryKey] ?? null;
                            $name = $row[$nameField] ?? ($row['full_name'] ?? ($row['name'] ?? '—'));
                            $status = $row['status'] ?? 'active';
                            @endphp
                            <tr wire:key="row-{{ $rid }}">
                                <td>
                                    <div class="gg-user">
                                        <div class="gg-avatar {{ $status }}">
                                            {{ mb_substr($name, 0, 1) ?: '?' }}
                                        </div>
                                        <div>
                                            <div class="gg-user-name">{{ $name }}</div>
                                            <div class="gg-user-sub">
                                                @if (!empty($row['phone']))
                                                <i class="fas fa-mobile-screen"></i> {{ $row['phone'] }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                @foreach (($config['main_fields'] ?? []) as $field)
                                @php
                                $key = $field['key'] ?? '';
                                $isList = $field['show_in_list'] ?? false;
                                $isName = in_array($key, [$nameField, 'phone', 'id_number', 'status'], true);
                                $col = $field['column'] ?? $key;
                                @endphp
                                @if (($isList || ($isName && !in_array($key, ['full_name','name']))) && !in_array($key,
                                ['full_name','name','notes','address','status']))
                                @php
                                $val = $row[$col] ?? null;
                                if ($field['type'] === 'select' && $val !== null && isset($labelMaps[$key][$val])) {
                                $val = $labelMaps[$key][$val];
                                }
                                if (isset($row['_labels'][$col])) {
                                $val = $row['_labels'][$col];
                                }
                                @endphp
                                <td>{{ $val ?: '—' }}</td>
                                @endif
                                @endforeach

                                <td>
                                    <span class="gg-pill {{ $status }}">
                                        <i class="fas fa-circle" style="font-size:.4rem;"></i>
                                        {{ $statusLabels[$status] ?? $status }}
                                    </span>
                                </td>

                                <td>
                                    <div class="gg-actions">
                                        {{-- عرض --}}
                                        <button type="button" class="gg-action-btn view"
                                            wire:click="openView({{ $rid }})" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- تعديل السجل --}}
                                        {{-- تعديل السجل من خلال دالة GenericGrid --}}
                                        @if (!empty($config['actions']['edit']) && $rid !== null)
                                        <button type="button" class="gg-action-btn edit"
                                            wire:click="openEdit({{ (int) $rid }})" wire:loading.attr="disabled"
                                            wire:target="openEdit" title="تعديل">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        @endif

                                        {{-- حظر / تنشيط --}}
                                        @if (!empty($config['actions']['status']))
                                        @if ($status === 'blocked')
                                        <button type="button" class="gg-action-btn unblock"
                                            wire:click="toggleStatus({{ $rid }})" title="تفعيل">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                        @else
                                        <button type="button" class="gg-action-btn block"
                                            wire:click="openToggle({{ $rid }})" title="حظر">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        @endif
                                        @endif

                                        {{-- حذف --}}
                                        @if (!empty($config['actions']['delete']))
                                        <button type="button" class="gg-action-btn delete"
                                            wire:click="openDelete({{ $rid }})" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="gg-pagination">
                    <div class="gg-pagination-info">
                        عرض {{ $records['paginator']->firstItem() ?? 0 }}
                        - {{ $records['paginator']->lastItem() ?? 0 }}
                        من {{ $records['paginator']->total() }}
                    </div>
                    <div class="gg-pagination-controls">
                        <button type="button" class="gg-page-btn" wire:click="previousPage"
                            @disabled($records['paginator']->onFirstPage())>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        @foreach ($records['paginator']->getUrlRange(
                        max(1, $records['paginator']->currentPage() - 2),
                        min($records['paginator']->lastPage(), $records['paginator']->currentPage() + 2)
                        ) as $page => $url)
                        <button type="button"
                            class="gg-page-btn {{ $page === $records['paginator']->currentPage() ? 'active' : '' }}"
                            wire:click="gotoPage({{ $page }})">
                            {{ $page }}
                        </button>
                        @endforeach
                        <button type="button" class="gg-page-btn" wire:click="nextPage"
                            @disabled(!$records['paginator']->hasMorePages())>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- ===== View Modal ===== --}}
        @if ($viewingRecord && $modalType === 'view')
        @php
        $v = $viewingRecord;
        $vName = $v[$nameField] ?? ($v['full_name'] ?? ($v['name'] ?? '—'));
        $vStatus = $v['status'] ?? 'active';
        @endphp
        <div class="gg-overlay" wire:click.self="closeModal">
            <div class="gg-modal large">
                <div class="gg-modal-header">
                    <div class="gg-modal-icon"><i class="fas fa-id-badge"></i></div>
                    <div class="gg-modal-title">
                        <h2>تفاصيل {{ $entitySingle }}</h2>
                        <p>عرض شامل للبيانات والعلاقات</p>
                    </div>
                    <button type="button" class="gg-modal-close" wire:click="closeModal">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>

                <div class="gg-modal-body">
                    {{-- Header --}}
                    <div class="gg-details-header">
                        <div class="gg-details-avatar {{ $vStatus }}">
                            {{ mb_substr($vName, 0, 1) ?: '?' }}
                        </div>
                        <div>
                            <div class="gg-details-name">{{ $vName }}</div>
                            <span class="gg-pill {{ $vStatus }}">
                                <i class="fas fa-circle" style="font-size:.4rem;"></i>
                                {{ $statusLabels[$vStatus] ?? $vStatus }}
                            </span>
                        </div>
                    </div>

                    {{-- Details Grid --}}
                    <div class="gg-details-grid">
                        @foreach (($config['main_fields'] ?? []) as $field)
                        @php
                        $key = $field['key'] ?? '';
                        $col = $field['column'] ?? $key;
                        if (($field['is_status'] ?? false) && !isset($field['column'])) continue;
                        $val = $v[$col] ?? null;
                        if (isset($v['_labels'][$col])) $val = $v['_labels'][$col];
                        if ($field['type'] === 'select' && $val !== null && isset($labelMaps[$key][$val])) {
                        $val = $labelMaps[$key][$val];
                        }
                        @endphp
                        <div class="gg-detail-item">
                            <div class="gg-detail-item-label">
                                <i class="{{ $field['icon'] ?? 'fas fa-circle' }}"></i>
                                {{ $field['label'] ?? $key }}
                            </div>
                            <div class="gg-detail-item-value {{ $val ? '' : 'empty' }}">
                                {{ $val ?: 'غير محدد' }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Relations --}}
                    @foreach ($relationsCfg as $relName => $relDef)
                    @if (($relDef['enabled'] ?? true))
                    @php
                    $relItems = $v[$relName] ?? [];
                    $relTitle = $relDef['title'] ?? ($config['sub_section_title'] ?? 'العناصر المرتبطة');
                    @endphp
                    <div class="gg-relations">
                        <div class="gg-relations-title">
                            <i class="{{ $config['sub_section_icon'] ?? 'fas fa-list' }}"></i>
                            {{ $relTitle }}
                            <span class="gg-badge">{{ count($relItems) }}</span>
                        </div>
                        @if (count($relItems) === 0)
                        <div style="text-align:center; padding:1rem; color:#94A3B8; font-size:.8rem;">
                            <i class="fas fa-inbox"
                                style="display:block; font-size:1.5rem; margin-bottom:.4rem; opacity:.5;"></i>
                            لا توجد عناصر مرتبطة
                        </div>
                        @else
                        <div class="gg-rel-list">
                            @foreach ($relItems as $relRow)
                            @php
                            $relName_ = $relRow[$relDef['name_field'] ?? 'name']
                            ?? $relRow['full_name']
                            ?? $relRow['name']
                            ?? '—';
                            $relPhone = $relRow['phone'] ?? '';
                            $relStatus = $relRow['status'] ?? 'active';
                            @endphp
                            <div class="gg-rel-item {{ $relStatus === 'blocked' ? 'blocked' : '' }}">
                                <div class="gg-rel-avatar {{ $relStatus === 'blocked' ? 'blocked' : '' }}">
                                    {{ mb_substr($relName_, 0, 1) ?: '?' }}
                                </div>
                                <div class="gg-rel-info">
                                    <div class="gg-rel-name">{{ $relName_ }}</div>
                                    <div class="gg-rel-meta">
                                        @if ($relPhone)
                                        <span><i class="fas fa-mobile-screen"></i> {{ $relPhone }}</span>
                                        @endif
                                        @if (!empty($relRow['email']))
                                        <span><i class="fas fa-envelope"></i> {{ $relRow['email'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="gg-pill {{ $relStatus }}">
                                    {{ $statusLabels[$relStatus] ?? $relStatus }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="gg-modal-footer">
                    <button type="button" class="gg-btn gg-btn-secondary" wire:click="closeModal">إغلاق</button>
                    {{-- الانتقال من نافذة العرض إلى نافذة التعديل --}}
                    @if (!empty($config['actions']['edit']) && !empty($v[$primaryKey]))
                    <button type="button" class="gg-btn gg-btn-primary"
                        wire:click="openEdit({{ (int) $v[$primaryKey] }})" wire:loading.attr="disabled"
                        wire:target="openEdit">
                        <i class="fas fa-pen"></i>
                        تعديل
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
        {{-- ===== Edit Modal ===== --}}
        @if ($editingRecord && $modalType === 'edit')

        <div class="gg-overlay" wire:click.self="closeModal">

            <div class="gg-modal large">

                {{-- رأس النافذة --}}
                <div class="gg-modal-header">

                    <div class="gg-modal-icon">
                        <i class="fas fa-pen"></i>
                    </div>

                    <div class="gg-modal-title">
                        <h2>تعديل {{ $entitySingle }}</h2>
                        <p>
                            {{ $editingRecord[$nameField] ?? 'تعديل بيانات السجل' }}
                        </p>
                    </div>

                    <button type="button" class="gg-modal-close" wire:click="closeModal">
                        <i class="fas fa-xmark"></i>
                    </button>

                </div>

                {{-- نموذج التعديل --}}
                <form wire:submit.prevent="saveEdit">

                    <div class="gg-modal-body">

                        <div class="gg-details-grid">

                            @foreach (($config['main_fields'] ?? []) as $field)

                            @php
                            $key = $field['key'] ?? null;
                            $type = $field['type'] ?? 'text';

                            if (!$key || !array_key_exists($key, $editingData)) {
                            continue;
                            }

                            if (
                            ($field['virtual'] ?? false) ||
                            ($field['readonly'] ?? false)
                            ) {
                            continue;
                            }

                            $inputType = in_array($type, [
                            'text',
                            'email',
                            'number',
                            'date',
                            'tel',
                            'url',
                            ], true) ? $type : 'text';

                            $options = $field['options'] ?? [];

                            if ($key === 'status' && empty($options)) {
                            $options = [
                            ['value' => 'active', 'label' => 'نشط'],
                            ['value' => 'inactive', 'label' => 'غير نشط'],
                            ['value' => 'blocked', 'label' => 'محظور'],
                            ];
                            }
                            @endphp

                            <div class="gg-detail-item">

                                <label class="gg-detail-item-label">
                                    <i class="{{ $field['icon'] ?? 'fas fa-pen' }}"></i>
                                    {{ $field['label'] ?? $key }}
                                    @if (!empty($field['required']))
                                    <span style="color:#DC2626">*</span>
                                    @endif
                                </label>

                                @if ($type === 'textarea')

                                <textarea class="gg-input" style="height:auto; min-height:90px; resize:vertical;"
                                    wire:model="editingData.{{ $key }}"
                                    placeholder="أدخل {{ $field['label'] ?? $key }}"></textarea>

                                @elseif ($type === 'select' || $key === 'status')

                                <select class="gg-select" wire:model="editingData.{{ $key }}">
                                    <option value="">اختر...</option>

                                    @foreach ($options as $option)
                                    <option value="{{ $option['value'] }}">
                                        {{ $option['label'] }}
                                    </option>
                                    @endforeach
                                </select>

                                @elseif ($type === 'checkbox')

                                <label style="display:flex; align-items:center; gap:8px;">
                                    <input type="checkbox" wire:model="editingData.{{ $key }}">
                                    {{ $field['label'] ?? $key }}
                                </label>

                                @else

                                <input type="{{ $inputType }}" class="gg-input" wire:model="editingData.{{ $key }}"
                                    placeholder="أدخل {{ $field['label'] ?? $key }}">

                                @endif

                                @error('editingData.' . $key)
                                <small style="color:#DC2626; display:block; margin-top:5px;">
                                    {{ $message }}
                                </small>
                                @enderror

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- أزرار الحفظ --}}
                    <div class="gg-modal-footer">

                        <button type="button" class="gg-btn gg-btn-secondary" wire:click="closeModal">
                            إلغاء
                        </button>

                        <button type="submit" class="gg-btn gg-btn-primary" wire:loading.attr="disabled"
                            wire:target="saveEdit">
                            <span wire:loading.remove wire:target="saveEdit">
                                <i class="fas fa-save"></i>
                                حفظ التعديلات
                            </span>

                            <span wire:loading wire:target="saveEdit">
                                <i class="fas fa-spinner fa-spin"></i>
                                جارٍ الحفظ...
                            </span>
                        </button>

                    </div>

                </form>

            </div>

        </div>

        @endif
        {{-- ===== Delete Modal ===== --}}
        @if ($deletingRecord && $modalType === 'delete')
        <div class="gg-overlay" wire:click.self="closeModal">
            <div class="gg-modal small">
                <div class="gg-modal-header">
                    <div class="gg-modal-icon" style="background:linear-gradient(135deg,#DC2626,#EF4444); color:#fff;">
                        <i class="fas fa-trash"></i>
                    </div>
                    <div class="gg-modal-title">
                        <h2>تأكيد الحذف</h2>
                        <p>لا يمكن التراجع عن هذه العملية</p>
                    </div>
                    <button type="button" class="gg-modal-close" wire:click="closeModal">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
                <div class="gg-modal-body">
                    <div class="gg-confirm">
                        <div class="gg-confirm-icon danger">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>
                        <h3>هل أنت متأكد؟</h3>
                        <p>سيتم حذف <strong>{{ $deletingRecord[$nameField] ?? 'السجل' }}</strong> نهائياً.</p>
                    </div>
                </div>
                <div class="gg-modal-footer">
                    <button type="button" class="gg-btn gg-btn-secondary" wire:click="closeModal">إلغاء</button>
                    <button type="button" class="gg-btn gg-btn-danger"
                        wire:click="deleteRecord({{ $deletingRecord['id'] }})">
                        <i class="fas fa-trash"></i> نعم، احذف
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- ===== Toggle Modal ===== --}}
        @if ($togglingId && $modalType === 'toggle')
        <div class="gg-overlay" wire:click.self="closeModal">
            <div class="gg-modal small">
                <div class="gg-modal-header">
                    <div class="gg-modal-icon" style="background:linear-gradient(135deg,#D97706,#F59E0B); color:#fff;">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="gg-modal-title">
                        <h2>تغيير الحالة</h2>
                        <p>هل تريد تغيير حالة هذا السجل؟</p>
                    </div>
                    <button type="button" class="gg-modal-close" wire:click="closeModal">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
                <div class="gg-modal-body">
                    <div class="gg-confirm">
                        <div class="gg-confirm-icon warning">
                            <i class="fas fa-ban"></i>
                        </div>
                        <h3>سيتم تغيير الحالة</h3>
                        <p>يمكنك التراجع عن هذا الإجراء لاحقاً.</p>
                    </div>
                </div>
                <div class="gg-modal-footer">
                    <button type="button" class="gg-btn gg-btn-secondary" wire:click="closeModal">إلغاء</button>
                    <button type="button" class="gg-btn gg-btn-warning" wire:click="toggleStatus({{ $togglingId }})">
                        <i class="fas fa-ban"></i> تأكيد
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ===== Toast + Livewire events ===== --}}
    <div class="gg-toast" id="ggToast">
        <i class="fas fa-circle-check"></i>
        <span id="ggToastMsg"></span>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('grid-refreshed', (e) => {
                const data = e.detail ?? e[0] ?? e;
                showGridToast(data.message || 'تمت العملية', 'success');
            });
            Livewire.on('grid-error', (e) => {
                const data = e.detail ?? e[0] ?? e;
                showGridToast(data.message || 'حدث خطأ', 'error');
            });
        });

        function showGridToast(msg, type) {
            const t = document.getElementById('ggToast');
            const m = document.getElementById('ggToastMsg');
            if (!t || !m) return;
            t.className = 'gg-toast ' + type;
            m.textContent = msg;
            const icon = t.querySelector('i');
            icon.className = type === 'success' ? 'fas fa-circle-check' : 'fas fa-circle-exclamation';
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3200);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const overlay = document.querySelector('.gg-overlay');
                if (overlay) {
                    const closeBtn = overlay.querySelector('.gg-modal-close');
                    if (closeBtn) closeBtn.click();
                }
            }
        });
    </script>
</div>