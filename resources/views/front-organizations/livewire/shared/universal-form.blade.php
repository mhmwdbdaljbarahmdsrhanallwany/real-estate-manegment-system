{{-- ============================================================
UniversalForm — Blade View
يجب أن يكون للمكوّن عنصر جذري واحد فقط (Livewire 4)
============================================================ --}}

@if(!empty($config['location_picker']['enabled']))
@assets
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endassets
@endif

<div class="uf-wrapper" wire:key="uf-wrapper-{{ $entityKey }}">

  @php
  $ufScriptPath = app_path(
  'Http/Livewire/FrontOrganizations/Shared/UniversalForm/scripts.js'
  );

  // تحديد علاقة hasMany القابلة للإدارة؛ علاقات belongsTo للعرض فقط.
  $firstRelKey = null;
  $firstRel = null;

  foreach (($config['relations'] ?? []) as $relationKey => $relationConfig) {
  if (
  !empty($relationConfig['enabled']) &&
  ($relationConfig['type'] ?? 'hasMany') === 'hasMany'
  ) {
  $firstRelKey = $relationKey;
  $firstRel = $relationConfig;
  break;
  }
  }

  $hasChildRelation = $firstRel !== null;

  $detailsUi = $config['ui']['details'] ?? [];
  $listUi = $config['ui']['list'] ?? [];
  $relationUi = $config['ui']['relation'] ?? [];
  $location = $config['location_picker'] ?? [];

  $latField = $location['latitude_field'] ?? 'latitude';
  $lngField = $location['longitude_field'] ?? 'longitude';

  $nameField = $config['name_field'] ?? $config['primary_key'];
  @endphp

  {{-- Livewire يتولى البيانات والعمليات؛ لا يتم تحميل scripts.js القديم. --}}

  <style>
    /* ============================================================
      الهوية البصرية — Royal Estate
     ============================================================
      */
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

    .uf-wrapper,
    .uf-wrapper * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .uf-wrapper {
      font-family: 'Tajawal', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg-page);
      color: var(--text-primary);
      min-height: 100vh;
      line-height: 1.5;
      direction: rtl;
    }

    .content-area {
      width: 100%;
      padding: 0;
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    .page-toolbar {
      background: var(--bg-card);
      border-radius: 0;
      box-shadow: var(--shadow-sm);
      padding: 0.7rem 1rem;
      display: flex;
      align-items: center;
      gap: 0.65rem;
      flex-wrap: wrap;
      position: relative;
      border-bottom: 1px solid var(--border-soft);
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

    .filters-bar {
      background: var(--bg-card);
      border-bottom: 1px solid var(--border-soft);
      padding: 0.65rem 1rem;
    }

    .filters-row {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      flex-wrap: wrap;
      margin-bottom: 0.5rem;
    }

    .filters-row:last-child {
      margin-bottom: 0;
    }

    .filter-item {
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
      flex: 1;
      min-width: 140px;
    }

    .filter-item.small {
      flex: 0 0 auto;
      min-width: 120px;
    }

    .filter-label {
      font-size: 0.65rem;
      font-weight: 600;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .filter-label i {
      color: var(--gold);
      font-size: 0.6rem;
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
      height: 32px;
    }

    .filter-control:focus {
      border-color: var(--midnight);
      background: #fff;
      box-shadow: 0 0 0 3px rgba(27, 77, 122, 0.1);
    }

    .filter-control:hover {
      background: #E8EEF5;
    }

    select.filter-control {
      cursor: pointer;
    }

    .filter-segmented {
      display: flex;
      align-items: center;
      background: var(--bg-field);
      border-radius: var(--radius-sm);
      padding: 0.15rem;
      gap: 0.1rem;
      height: 32px;
      overflow-x: auto;
      scrollbar-width: none;
    }

    .filter-segmented::-webkit-scrollbar {
      display: none;
    }

    .filter-btn {
      background: transparent;
      border: none;
      padding: 0.35rem 0.65rem;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--text-secondary);
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      transition: var(--transition);
      white-space: nowrap;
      height: 100%;
    }

    .filter-btn:hover {
      color: var(--midnight);
    }

    .filter-btn.active {
      background: #fff;
      color: var(--royal);
      box-shadow: 0 2px 6px rgba(15, 43, 70, 0.1);
    }

    .filter-btn i {
      font-size: 0.65rem;
    }

    .filter-btn.active i {
      color: var(--gold);
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
      height: 32px;
    }

    .filter-search input:focus {
      border-color: var(--midnight);
      background: #fff;
      box-shadow: 0 0 0 3px rgba(27, 77, 122, 0.1);
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
      height: 32px;
      white-space: nowrap;
    }

    .btn-filter-action:hover {
      background: var(--bg-field);
      color: var(--midnight);
      border-color: #CBD5E1;
    }

    .btn-filter-action.clear:hover {
      background: #FEF2F2;
      color: var(--cherry);
      border-color: rgba(220, 38, 38, 0.3);
    }

    .btn-filter-action i {
      font-size: 0.68rem;
    }

    .stats-row {
      display: grid;
      grid-template-columns: repeat(5, minmax(0, 1fr));
      gap: 0;
      background: var(--bg-card);
      border-bottom: 1px solid var(--border-soft);
    }

    .stat-card {
      padding: 0.6rem 0.85rem;
      display: flex;
      align-items: center;
      gap: 0.55rem;
      transition: var(--transition);
      border-left: 1px solid var(--border-soft);
    }

    .stat-card:last-child {
      border-left: none;
    }

    .stat-card:hover {
      background: var(--bg-field);
    }

    .stat-icon {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.82rem;
      flex-shrink: 0;
    }

    .stat-icon.royal {
      background: rgba(15, 43, 70, 0.1);
      color: var(--royal);
    }

    .stat-icon.emerald {
      background: rgba(5, 150, 105, 0.12);
      color: var(--emerald);
    }

    .stat-icon.gold {
      background: rgba(201, 162, 39, 0.15);
      color: var(--gold);
    }

    .stat-icon.sky {
      background: rgba(2, 132, 199, 0.12);
      color: var(--sky);
    }

    .stat-icon.cherry {
      background: rgba(220, 38, 38, 0.1);
      color: var(--cherry);
    }

    .stat-icon.purple {
      background: rgba(124, 58, 237, 0.12);
      color: var(--purple);
    }

    .stat-info {
      flex: 1;
      min-width: 0;
    }

    .stat-value {
      font-size: 1.05rem;
      font-weight: 800;
      color: var(--royal);
      line-height: 1.1;
    }

    .stat-label {
      font-size: 0.62rem;
      color: var(--text-secondary);
      font-weight: 500;
      margin-top: 0.05rem;
    }

    .workspace {
      display: grid;
      grid-template-columns: 340px 1fr;
      gap: 0;
      background: var(--bg-page);
      min-height: calc(100vh - 260px);
    }

    .owners-panel {
      background: var(--bg-card);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      border-left: 1px solid var(--border-soft);
      max-height: calc(100vh - 260px);
      position: sticky;
      top: 0;
    }

    .panel-header {
      padding: 0.6rem 0.85rem;
      border-bottom: 1px solid var(--border-soft);
      background: linear-gradient(135deg, rgba(15, 43, 70, 0.02) 0%, rgba(201, 162, 39, 0.04) 100%);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
    }

    .panel-header-title {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.8rem;
      font-weight: 700;
      color: var(--royal);
    }

    .panel-header-title i {
      color: var(--gold);
      font-size: 0.8rem;
    }

    .panel-header-title .count {
      background: var(--bg-field);
      color: var(--midnight);
      padding: 0.08rem 0.45rem;
      border-radius: 999px;
      font-size: 0.62rem;
      font-weight: 700;
    }

    .owners-list {
      flex: 1;
      overflow-y: auto;
      padding: 0.35rem;
    }

    .owners-list::-webkit-scrollbar {
      width: 4px;
    }

    .owners-list::-webkit-scrollbar-track {
      background: transparent;
    }

    .owners-list::-webkit-scrollbar-thumb {
      background: var(--border-soft);
      border-radius: 999px;
    }

    .owner-item {
      display: flex;
      align-items: center;
      gap: 0.55rem;
      padding: 0.5rem;
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: var(--transition);
      border: 1.5px solid transparent;
      margin-bottom: 0.25rem;
      position: relative;
    }

    .owner-item:hover {
      background: var(--bg-field);
      border-color: var(--border-soft);
    }

    .owner-item.active {
      background: linear-gradient(135deg, rgba(201, 162, 39, 0.08) 0%, rgba(15, 43, 70, 0.03) 100%);
      border-color: var(--gold);
    }

    .owner-item.active::before {
      content: '';
      position: absolute;
      right: 0;
      top: 20%;
      bottom: 20%;
      width: 3px;
      background: linear-gradient(180deg, var(--gold), var(--gold-light));
      border-radius: 999px;
    }

    .owner-item.blocked {
      opacity: 0.7;
    }

    .owner-avatar {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      background: linear-gradient(135deg, var(--royal), var(--midnight));
      color: var(--gold-light);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.8rem;
      flex-shrink: 0;
      position: relative;
    }

    .owner-avatar.blocked {
      background: linear-gradient(135deg, #64748B, #94A3B8);
      color: #fff;
    }

    .owner-avatar.status-active::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: -2px;
      width: 10px;
      height: 10px;
      background: var(--emerald);
      border: 2px solid #fff;
      border-radius: 50%;
    }

    .owner-avatar.status-inactive::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: -2px;
      width: 10px;
      height: 10px;
      background: var(--cherry);
      border: 2px solid #fff;
      border-radius: 50%;
    }

    .owner-info {
      flex: 1;
      min-width: 0;
    }

    .owner-name {
      font-size: 0.76rem;
      font-weight: 600;
      color: var(--text-primary);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 0.05rem;
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }

    .blocked-tag {
      background: rgba(220, 38, 38, 0.12);
      color: var(--cherry);
      padding: 0.02rem 0.35rem;
      border-radius: 999px;
      font-size: 0.58rem;
      font-weight: 700;
    }

    .owner-meta {
      display: flex;
      align-items: center;
      gap: 0.3rem;
      font-size: 0.62rem;
      color: var(--text-secondary);
    }

    .owner-meta i {
      font-size: 0.55rem;
    }

    .owner-actions-mini {
      display: flex;
      gap: 0.1rem;
      opacity: 0;
      transition: var(--transition);
    }

    .owner-item:hover .owner-actions-mini {
      opacity: 1;
    }

    .btn-mini {
      width: 22px;
      height: 22px;
      border-radius: 5px;
      border: none;
      background: transparent;
      color: var(--text-secondary);
      cursor: pointer;
      font-size: 0.6rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
    }

    .btn-mini:hover {
      background: var(--bg-field);
      color: var(--midnight);
    }

    .btn-mini.danger:hover {
      background: #FEF2F2;
      color: var(--cherry);
    }

    .btn-mini.warning:hover {
      background: #FFFBEB;
      color: var(--terracotta);
    }

    .btn-mini.success:hover {
      background: #ECFDF5;
      color: var(--emerald);
    }

    .details-panel {
      background: var(--bg-card);
      overflow: hidden;
      min-height: calc(100vh - 260px);
    }

    .empty-state {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 1.5rem;
      text-align: center;
      min-height: calc(100vh - 260px);
    }

    .empty-state i {
      font-size: 3rem;
      color: var(--border-soft);
      margin-bottom: 0.75rem;
    }

    .empty-state h3 {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }

    .empty-state p {
      font-size: 0.76rem;
      color: var(--text-muted);
      max-width: 260px;
      margin-bottom: 0.9rem;
    }

    .details-content {
      display: none;
    }

    .details-content.active {
      display: block;
    }

    .details-header {
      padding: 0.85rem 1rem;
      background: linear-gradient(135deg, rgba(15, 43, 70, 0.03) 0%, rgba(201, 162, 39, 0.05) 100%);
      border-bottom: 1px solid var(--border-soft);
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .details-avatar {
      width: 54px;
      height: 54px;
      border-radius: 13px;
      background: linear-gradient(135deg, var(--royal), var(--midnight));
      color: var(--gold-light);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 1.25rem;
      flex-shrink: 0;
      box-shadow: 0 6px 18px rgba(15, 43, 70, 0.25);
      position: relative;
    }

    .details-avatar.blocked {
      background: linear-gradient(135deg, #64748B, #94A3B8);
      color: #fff;
    }

    .details-avatar.status-active::after {
      content: '';
      position: absolute;
      bottom: -3px;
      left: -3px;
      width: 14px;
      height: 14px;
      background: var(--emerald);
      border: 3px solid #fff;
      border-radius: 50%;
    }

    .details-avatar.status-inactive::after {
      content: '';
      position: absolute;
      bottom: -3px;
      left: -3px;
      width: 14px;
      height: 14px;
      background: var(--cherry);
      border: 3px solid #fff;
      border-radius: 50%;
    }

    .details-info {
      flex: 1;
      min-width: 0;
    }

    .details-name {
      font-size: 1.05rem;
      font-weight: 800;
      color: var(--royal);
      margin-bottom: 0.2rem;
      display: flex;
      align-items: center;
      gap: 0.4rem;
      flex-wrap: wrap;
    }

    .status-tag {
      font-size: 0.62rem;
      font-weight: 600;
      padding: 0.12rem 0.5rem;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
    }

    .status-tag.active {
      background: rgba(5, 150, 105, 0.12);
      color: var(--emerald);
    }

    .status-tag.active::before {
      content: '';
      width: 4px;
      height: 4px;
      background: var(--emerald);
      border-radius: 50%;
    }

    .status-tag.inactive {
      background: rgba(220, 38, 38, 0.1);
      color: var(--cherry);
    }

    .status-tag.inactive::before {
      content: '';
      width: 4px;
      height: 4px;
      background: var(--cherry);
      border-radius: 50%;
    }

    .status-tag.blocked {
      background: rgba(220, 38, 38, 0.15);
      color: var(--cherry);
      border: 1px solid rgba(220, 38, 38, 0.3);
    }

    .status-tag.blocked::before {
      content: '';
      width: 4px;
      height: 4px;
      background: var(--cherry);
      border-radius: 50%;
    }

    .details-id {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.68rem;
      color: var(--text-secondary);
      flex-wrap: wrap;
    }

    .details-id span {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .details-id i {
      color: var(--gold);
      font-size: 0.62rem;
    }

    .details-actions {
      display: flex;
      gap: 0.3rem;
      flex-wrap: wrap;
    }

    .btn-action {
      padding: 0.42rem 0.7rem;
      border-radius: var(--radius-sm);
      border: 1.5px solid var(--border-soft);
      background: #fff;
      color: var(--text-primary);
      font-family: inherit;
      font-size: 0.7rem;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.28rem;
      transition: var(--transition);
      height: 32px;
      white-space: nowrap;
    }

    .btn-action:hover {
      background: var(--bg-field);
      border-color: #CBD5E1;
      transform: translateY(-1px);
    }

    .btn-action.edit {
      background: linear-gradient(135deg, var(--midnight), #2563EB);
      color: #fff;
      border-color: transparent;
    }

    .btn-action.edit:hover {
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-action.delete {
      background: #fff;
      color: var(--cherry);
      border-color: rgba(220, 38, 38, 0.3);
    }

    .btn-action.delete:hover {
      background: #FEF2F2;
      border-color: var(--cherry);
    }

    .btn-action.block {
      background: #fff;
      color: var(--terracotta);
      border-color: rgba(217, 119, 6, 0.3);
    }

    .btn-action.block:hover {
      background: #FFFBEB;
      border-color: var(--terracotta);
    }

    .btn-action.unblock {
      background: #fff;
      color: var(--emerald);
      border-color: rgba(5, 150, 105, 0.3);
    }

    .btn-action.unblock:hover {
      background: #ECFDF5;
      border-color: var(--emerald);
    }

    .btn-action.agent {
      background: linear-gradient(135deg, var(--gold), var(--gold-light));
      color: var(--royal);
      border-color: transparent;
      box-shadow: 0 3px 10px rgba(201, 162, 39, 0.3);
    }

    .btn-action.agent:hover {
      box-shadow: 0 6px 16px rgba(201, 162, 39, 0.45);
    }

    .details-body {
      padding: 0.85rem 1rem;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
      gap: 0.4rem;
      margin-bottom: 0.75rem;
    }

    .info-card {
      background: var(--bg-field);
      border-radius: var(--radius-md);
      padding: 0.6rem 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.55rem;
      transition: var(--transition);
      border: 1.5px solid transparent;
    }

    .info-card:hover {
      background: #fff;
      border-color: var(--border-soft);
      box-shadow: var(--shadow-sm);
    }

    .info-card-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.78rem;
      flex-shrink: 0;
    }

    .info-card-icon.blue {
      background: rgba(27, 77, 122, 0.12);
      color: var(--midnight);
    }

    .info-card-icon.gold {
      background: rgba(201, 162, 39, 0.12);
      color: var(--gold);
    }

    .info-card-icon.green {
      background: rgba(5, 150, 105, 0.12);
      color: var(--emerald);
    }

    .info-card-icon.orange {
      background: rgba(217, 119, 6, 0.12);
      color: var(--terracotta);
    }

    .info-card-icon.purple {
      background: rgba(124, 58, 237, 0.12);
      color: var(--purple);
    }

    .info-card-icon.sky {
      background: rgba(2, 132, 199, 0.12);
      color: var(--sky);
    }

    .info-card-content {
      flex: 1;
      min-width: 0;
    }

    .info-card-label {
      font-size: 0.6rem;
      color: var(--text-secondary);
      font-weight: 500;
      margin-bottom: 0.05rem;
    }

    .info-card-value {
      font-size: 0.76rem;
      color: var(--text-primary);
      font-weight: 600;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .info-card-value.empty {
      color: var(--text-muted);
      font-style: italic;
      font-weight: 400;
    }

    .section-card {
      background: #fff;
      border: 1.5px solid var(--border-soft);
      border-radius: var(--radius-md);
      overflow: hidden;
      margin-bottom: 0.5rem;
    }

    .section-card:last-child {
      margin-bottom: 0;
    }

    .section-card-header {
      display: flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.55rem 0.85rem;
      background: linear-gradient(135deg, rgba(15, 43, 70, 0.02) 0%, rgba(201, 162, 39, 0.04) 100%);
      border-bottom: 1px solid var(--border-soft);
      flex-wrap: wrap;
    }

    .section-card-header i.section-icon {
      color: var(--gold);
      font-size: 0.78rem;
    }

    .section-card-header h3 {
      font-size: 0.78rem;
      font-weight: 700;
      color: var(--royal);
    }

    .section-card-header .badge {
      background: var(--bg-field);
      color: var(--midnight);
      padding: 0.08rem 0.42rem;
      border-radius: 999px;
      font-size: 0.6rem;
      font-weight: 700;
    }

    .agents-tools {
      display: flex;
      align-items: center;
      gap: 0.3rem;
      margin-inline-start: auto;
      flex-wrap: wrap;
    }

    .agent-search-mini {
      position: relative;
      display: flex;
      align-items: center;
    }

    .agent-search-mini i {
      position: absolute;
      right: 0.45rem;
      color: var(--text-muted);
      font-size: 0.6rem;
      pointer-events: none;
    }

    .agent-search-mini input {
      width: 140px;
      padding: 0.28rem 1.4rem 0.28rem 0.45rem;
      font-family: inherit;
      font-size: 0.68rem;
      background: var(--bg-field);
      border: 1.5px solid transparent;
      border-radius: 6px;
      color: var(--text-primary);
      outline: none;
      transition: var(--transition);
      height: 26px;
    }

    .agent-search-mini input:focus {
      border-color: var(--midnight);
      background: #fff;
    }

    .btn-add-small {
      background: linear-gradient(135deg, var(--gold), var(--gold-light));
      color: var(--royal);
      border: none;
      padding: 0.28rem 0.6rem;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.65rem;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.22rem;
      transition: var(--transition);
      box-shadow: 0 2px 6px rgba(201, 162, 39, 0.3);
      height: 26px;
      white-space: nowrap;
    }

    .btn-add-small:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(201, 162, 39, 0.45);
    }

    .section-card-body {
      padding: 0.55rem 0.75rem;
    }

    .agent-card {
      display: flex;
      align-items: center;
      gap: 0.55rem;
      padding: 0.55rem 0.7rem;
      background: var(--bg-field);
      border-radius: var(--radius-sm);
      transition: var(--transition);
      border: 1.5px solid transparent;
      margin-bottom: 0.3rem;
      position: relative;
    }

    .agent-card:last-child {
      margin-bottom: 0;
    }

    .agent-card:hover {
      background: #fff;
      border-color: var(--border-soft);
      box-shadow: var(--shadow-sm);
    }

    .agent-card.blocked {
      background: rgba(220, 38, 38, 0.05);
      border-color: rgba(220, 38, 38, 0.15);
    }

    .agent-card.blocked:hover {
      background: rgba(220, 38, 38, 0.08);
    }

    .agent-avatar {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      background: linear-gradient(135deg, var(--sky), #38BDF8);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.78rem;
      flex-shrink: 0;
    }

    .agent-avatar.blocked {
      background: linear-gradient(135deg, #64748B, #94A3B8);
    }

    .agent-info {
      flex: 1;
      min-width: 0;
    }

    .agent-name {
      font-size: 0.76rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.05rem;
      display: flex;
      align-items: center;
      gap: 0.3rem;
      flex-wrap: wrap;
    }

    .agent-meta {
      font-size: 0.62rem;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 0.55rem;
      flex-wrap: wrap;
    }

    .agent-meta span {
      display: flex;
      align-items: center;
      gap: 0.2rem;
    }

    .agent-meta i {
      font-size: 0.55rem;
    }

    .agent-type-tag {
      background: rgba(2, 132, 199, 0.12);
      color: var(--sky);
      padding: 0.08rem 0.4rem;
      border-radius: 999px;
      font-size: 0.58rem;
      font-weight: 600;
    }

    .agent-actions {
      display: flex;
      gap: 0.12rem;
      flex-shrink: 0;
    }

    .btn-agent-action {
      width: 26px;
      height: 26px;
      border-radius: 6px;
      border: 1px solid transparent;
      background: #fff;
      color: var(--text-secondary);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.65rem;
      transition: var(--transition);
    }

    .btn-agent-action:hover {
      background: var(--bg-field);
      border-color: var(--border-soft);
      transform: translateY(-1px);
    }

    .btn-agent-action.view:hover {
      color: var(--sky);
      border-color: rgba(2, 132, 199, 0.3);
      background: rgba(2, 132, 199, 0.08);
    }

    .btn-agent-action.edit:hover {
      color: var(--midnight);
      border-color: rgba(27, 77, 122, 0.3);
      background: rgba(27, 77, 122, 0.08);
    }

    .btn-agent-action.delete:hover {
      color: var(--cherry);
      border-color: rgba(220, 38, 38, 0.3);
      background: #FEF2F2;
    }

    .btn-agent-action.block:hover {
      color: var(--terracotta);
      border-color: rgba(217, 119, 6, 0.3);
      background: #FFFBEB;
    }

    .btn-agent-action.unblock:hover {
      color: var(--emerald);
      border-color: rgba(5, 150, 105, 0.3);
      background: #ECFDF5;
    }

    .agent-empty {
      text-align: center;
      padding: 0.85rem;
      color: var(--text-muted);
      font-size: 0.72rem;
    }

    .agent-empty i {
      font-size: 1.4rem;
      color: var(--border-soft);
      display: block;
      margin-bottom: 0.3rem;
    }

    .agent-empty .btn-add-small {
      margin-top: 0.45rem;
    }

    .notes-box {
      font-size: 0.76rem;
      color: var(--text-secondary);
      line-height: 1.55;
      padding: 0.5rem 0.7rem;
      background: var(--bg-field);
      border-radius: var(--radius-sm);
      font-style: italic;
    }

    .notes-box.has-content {
      font-style: normal;
      color: var(--text-primary);
    }

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
      max-width: 660px;
      max-height: 92vh;
      overflow: hidden;
      top: 55px;
      left: 30%;
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

    .modal.small {
      max-width: 385px;
    }

    .modal.medium {
      max-width: 540px;
    }

    .modal-header {
      padding: 0.8rem 1rem;
      background: linear-gradient(135deg, var(--royal) 0%, var(--midnight) 100%);
      color: #fff;
      display: flex;
      align-items: center;
      gap: 0.6rem;
      position: relative;
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
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.55rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.18rem;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    .form-label {
      font-size: 0.7rem;
      font-weight: 500;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 0.22rem;
    }

    .form-label .required {
      color: var(--cherry);
    }

    .form-control {
      width: 100%;
      padding: 0.45rem 0.6rem;
      font-family: inherit;
      font-size: 0.78rem;
      color: var(--text-primary);
      background: var(--bg-field);
      border: 1.5px solid transparent;
      border-radius: var(--radius-sm);
      outline: none;
      transition: var(--transition);
      height: 34px;
    }

    .form-control:hover {
      background: #E8EEF5;
    }

    .form-control:focus {
      border-color: var(--midnight);
      background: #fff;
      box-shadow: 0 0 0 3px rgba(27, 77, 122, 0.1);
    }

    select.form-control {
      cursor: pointer;
    }

    textarea.form-control {
      height: auto;
      min-height: 65px;
      resize: vertical;
      padding: 0.45rem 0.6rem;
      line-height: 1.5;
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

    .btn-danger {
      background: linear-gradient(135deg, var(--cherry), #EF4444);
      color: #fff;
      box-shadow: 0 3px 10px rgba(220, 38, 38, 0.3);
    }

    .btn-danger:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
    }

    .btn-warning {
      background: linear-gradient(135deg, var(--terracotta), #F59E0B);
      color: #fff;
      box-shadow: 0 3px 10px rgba(217, 119, 6, 0.3);
    }

    .btn-warning:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
    }

    .btn-success {
      background: linear-gradient(135deg, var(--emerald), #10B981);
      color: #fff;
      box-shadow: 0 3px 10px rgba(5, 150, 105, 0.3);
    }

    .btn-success:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
    }

    .btn-gold {
      background: linear-gradient(135deg, var(--gold), var(--gold-light));
      color: var(--royal);
      box-shadow: 0 3px 10px rgba(201, 162, 39, 0.3);
    }

    .btn-gold:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(201, 162, 39, 0.45);
    }

    .confirm-content {
      text-align: center;
      padding: 0.3rem 0;
    }

    .confirm-icon {
      width: 56px;
      height: 56px;
      background: rgba(220, 38, 38, 0.1);
      color: var(--cherry);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin: 0 auto 0.65rem;
    }

    .confirm-icon.warning {
      background: rgba(217, 119, 6, 0.1);
      color: var(--terracotta);
    }

    .confirm-icon.success {
      background: rgba(5, 150, 105, 0.1);
      color: var(--emerald);
    }

    .confirm-content h3 {
      font-size: 0.9rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .confirm-content p {
      font-size: 0.74rem;
      color: var(--text-secondary);
      line-height: 1.5;
    }

    .confirm-content strong {
      color: var(--cherry);
    }

    .agent-detail-row {
      display: flex;
      align-items: center;
      gap: 0.55rem;
      padding: 0.5rem 0.65rem;
      background: var(--bg-field);
      border-radius: var(--radius-sm);
      margin-bottom: 0.35rem;
    }

    .agent-detail-row:last-child {
      margin-bottom: 0;
    }

    .agent-detail-icon {
      width: 30px;
      height: 30px;
      border-radius: 7px;
      background: rgba(2, 132, 199, 0.12);
      color: var(--sky);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      flex-shrink: 0;
    }

    .agent-detail-content {
      flex: 1;
      min-width: 0;
    }

    .agent-detail-label {
      font-size: 0.6rem;
      color: var(--text-secondary);
      font-weight: 500;
      margin-bottom: 0.05rem;
    }

    .agent-detail-value {
      font-size: 0.76rem;
      color: var(--text-primary);
      font-weight: 600;
      word-break: break-word;
    }

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

    @media (max-width: 1200px) {
      .workspace {
        grid-template-columns: 300px 1fr;
      }

      .stats-row {
        grid-template-columns: repeat(5, minmax(0, 1fr));
      }
    }

    @media (max-width: 992px) {
      .stats-row {
        grid-template-columns: repeat(3, minmax(0, 1fr));
      }

      .stat-card {
        border-left: none;
        border-bottom: 1px solid var(--border-soft);
      }

      .stat-card:nth-child(3n) {
        border-left: none;
      }

      .stat-card:nth-child(5) {
        border-bottom: none;
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

      .workspace {
        grid-template-columns: 1fr;
      }

      .owners-panel {
        border-left: none;
        border-bottom: 1px solid var(--border-soft);
        max-height: 320px;
        position: relative;
      }

      .details-panel {
        min-height: auto;
      }

      .empty-state {
        min-height: 260px;
        padding: 1.75rem 1rem;
      }

      .details-header {
        padding: 0.75rem 0.85rem;
        gap: 0.55rem;
      }

      .details-avatar {
        width: 46px;
        height: 46px;
        font-size: 1.05rem;
      }

      .details-name {
        font-size: 0.95rem;
      }

      .details-body {
        padding: 0.75rem 0.85rem;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }

      .details-actions {
        width: 100%;
      }

      .btn-action {
        flex: 1;
        justify-content: center;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .agents-tools {
        width: 100%;
      }

      .agent-search-mini {
        flex: 1;
      }

      .agent-search-mini input {
        width: 100%;
      }

      .filters-row {
        gap: 0.35rem;
      }

      .filter-item {
        min-width: 110px;
      }
    }

    @media (max-width: 480px) {
      .stats-row {
        grid-template-columns: 1fr 1fr;
      }

      .stat-card:nth-child(4) {
        border-bottom: none;
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

      .modal {
        border-radius: var(--radius-md);
      }

      .modal-footer {
        flex-direction: column-reverse;
      }

      .modal-footer .btn {
        width: 100%;
      }

      .agent-card {
        flex-wrap: wrap;
      }

      .agent-actions {
        width: 100%;
        justify-content: flex-end;
        margin-top: 0.25rem;
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
  <div class="content-area">

    {{-- ========== Toolbar ========== --}}
    <div class="page-toolbar">
      <div class="toolbar-title">
        <div class="toolbar-icon">
          <i class="{{ $config['icon'] ?? 'fas fa-table' }}"></i>
        </div>

        <div class="toolbar-text">
          <h1>{{ $config['title'] ?? '' }}</h1>
          <p>{{ $config['subtitle'] ?? '' }}</p>
        </div>
      </div>

      <div class="toolbar-actions">
        @if($hasChildRelation)
        <button type="button" class="btn-add-main blue" wire:click="openChildModal">
          <i class="{{ $config['sub_section_icon'] ?? 'fas fa-plus' }}"></i>
          {{ $config['btn_add_sub'] ?? 'إضافة' }}
        </button>
        @endif

        @if(!empty($config['actions']['create']))
        <button type="button" class="btn-add-main" wire:click="openRecordModal">
          <i class="fas fa-plus"></i>
          {{ $config['btn_add_main'] ?? 'إضافة' }}
        </button>
        @endif
      </div>
    </div>

    {{-- ========== Filters ========== --}}
    @if(!empty($config['display']['filters']) && !empty($config['filters']))
    <div class="filters-bar">
      @php
      $searchFilters = array_filter(
      $config['filters'],
      fn($f) => ($f['type'] ?? '') === 'search'
      );

      $dateFilters = array_filter(
      $config['filters'],
      fn($f) => ($f['type'] ?? '') === 'date'
      );

      $otherFilters = array_filter(
      $config['filters'],
      fn($f) => !in_array($f['type'] ?? '', ['search', 'date'])
      );
      @endphp

      <div class="filters-row">
        <div class="filter-search">
          <i class="fas fa-search"></i>

          <input type="text" id="searchInput" wire:model.live.debounce.350ms="search"
            placeholder="{{ $config['search']['placeholder'] ?? 'بحث...' }}">
        </div>

        @foreach($otherFilters as $f)
        @php
        $options = $f['options'] ?? [];

        if (!empty($f['options_source']) || !empty($f['lookup'])) {
        $options = $this->fieldOptions($f);
        }
        @endphp

        <div class="filter-item small">
          <label class="filter-label">
            <i class="{{ $f['icon'] ?? 'fas fa-filter' }}"></i>
            {{ $f['label'] ?? '' }}
          </label>

          @if(($f['type'] ?? 'select') === 'text')
          <input type="text" class="filter-control" id="filter{{ ucfirst($f['key']) }}"
            wire:model.live.debounce.350ms="filters.{{ $f['key'] }}">
          @else
          <select class="filter-control" id="filter{{ ucfirst($f['key']) }}" wire:model.live="filters.{{ $f['key'] }}">
            @if(
            !empty($f['options_source']) ||
            !empty($f['lookup']) ||
            !in_array(
            'all',
            array_map(
            fn($opt) => (string)($opt['value'] ?? ''),
            $options
            ),
            true
            )
            )
            <option value="all">الكل</option>
            @endif

            @foreach($options as $opt)
            <option value="{{ $opt['id'] ?? $opt['value'] ?? '' }}">
              {{ $opt['name'] ?? $opt['label'] ?? '' }}
            </option>
            @endforeach
          </select>
          @endif
        </div>
        @endforeach
      </div>

      @if(!empty($dateFilters))
      <div class="filters-row">
        @foreach($dateFilters as $f)
        <div class="filter-item">
          <label class="filter-label">
            <i class="{{ $f['icon'] ?? 'fas fa-calendar' }}"></i>
            {{ $f['label'] ?? '' }}
          </label>

          <input type="date" class="filter-control" id="filter{{ ucfirst($f['key']) }}"
            wire:model.live="filters.{{ $f['key'] }}">
        </div>
        @endforeach

        <div class="filter-actions">
          <button type="button" class="btn-filter-action clear" wire:click="clearFilters">
            <i class="fas fa-rotate-left"></i>
            مسح
          </button>
        </div>
      </div>
      @else
      <div class="filters-row">
        <div class="filter-actions">
          <button type="button" class="btn-filter-action clear" wire:click="clearFilters">
            <i class="fas fa-rotate-left"></i>
            مسح
          </button>
        </div>
      </div>
      @endif
    </div>
    @endif

    {{-- ========== Stats ========== --}}
    @if(!empty($config['display']['statistics']) && !empty($stats))
    <div class="stats-row" id="statsRow">
      @foreach($stats as $s)
      <div class="stat-card">
        <div class="stat-icon {{ $s['color'] ?? 'royal' }}">
          <i class="{{ $s['icon'] ?? 'fas fa-chart-simple' }}"></i>
        </div>

        <div class="stat-info">
          <div class="stat-value">{{ $s['value'] ?? 0 }}</div>
          <div class="stat-label">{{ $s['label'] ?? '' }}</div>
        </div>
      </div>
      @endforeach
    </div>
    @endif

    {{-- ========== Workspace ========== --}}
    <div class="workspace">

      {{-- List Panel --}}
      <aside class="owners-panel">
        <div class="panel-header">
          <div class="panel-header-title">
            <i class="fas fa-list"></i>
            قائمة {{ $config['entity_name'] ?? 'السجلات' }}
            <span class="count" id="listCount">{{ $records->total() }}</span>
          </div>
        </div>

        <div class="owners-list" id="ownersList">
          @forelse($records as $record)
          @php
          $recordId = $record->{$config['primary_key']};

          $avatarField = $listUi['avatar_field'] ?? (
          $listUi['title_field'] ?? $nameField
          );

          $titleField = $listUi['title_field'] ?? $nameField;
          $subtitleField = $listUi['subtitle_field'] ?? null;
          @endphp

          <div
            class="owner-item {{ $selectedId == $recordId ? 'active selected' : '' }} {{ ($record->status_key ?? '') === 'blocked' ? 'blocked' : '' }}"
            wire:click="selectRecord({{ $recordId }})" wire:key="uf-record-{{ $entityKey }}-{{ $recordId }}">
            <span class="owner-avatar">
              {{ mb_substr((string)($record->{$avatarField} ?? '؟'), 0, 1) }}
            </span>

            <span class="owner-item-info">
              <span class="owner-item-name">
                {{ $record->{$titleField} ?? 'بدون اسم' }}
              </span>

              @if($subtitleField)
              <span class="owner-item-sub">
                <i class="{{ $listUi['subtitle_icon'] ?? 'fas fa-circle-info' }}"></i>
                {{ $record->{$subtitleField} ?? '—' }}
              </span>
              @endif
            </span>

            <span class="status-tag {{ $record->status_key ?? 'active' }}">
              <span></span>
              {{ $record->status_label ?? '—' }}
            </span>
          </div>
          @empty
          <div class="empty-list-state">
            <i class="fas fa-folder-open"></i>
            <p>لا توجد سجلات مطابقة.</p>
          </div>
          @endforelse
        </div>

        @if(!empty($config['display']['pagination']) && $records->hasPages())
        <div class="p-2">
          {{ $records->onEachSide(1)->links() }}
        </div>
        @endif
      </aside>

      {{-- Details Panel --}}
      <main class="details-panel">

        @if(!$selectedRecord)
        <div class="empty-state" id="emptyState">
          <i class="{{ $config['icon'] ?? 'fas fa-table' }}"></i>

          <h3>
            اختر {{ $config['entity_singular'] ?? 'سجلاً' }} لعرض تفاصيله
          </h3>

          <p>
            انقر على أي {{ $config['entity_singular'] ?? 'سجل' }} من القائمة لعرض بياناته{{
            $hasChildRelation
            ? ' وإدارة ' . ($config['entity_sub'] ?? 'العناصر') . ''
            : ''
            }}
          </p>

          @if(!empty($config['actions']['create']))
          <button type="button" class="btn-add-main" wire:click="openRecordModal">
            <i class="fas fa-plus"></i>
            {{ $config['btn_add_main'] ?? 'إضافة' }}
          </button>
          @endif
        </div>
        @else

        <div class="details-content active" id="detailsContent">

          <div class="details-header">
            @php
            $detailsAvatarField = $detailsUi['avatar_field'] ?? $nameField;
            $detailsTitleField = $detailsUi['title_field'] ?? $nameField;
            @endphp

            <div class="details-avatar" id="detailsAvatar">
              {{ mb_substr((string)($selectedRecord[$detailsAvatarField] ?? '—'), 0, 1) }}
            </div>

            <div class="details-info">
              <div class="details-name">
                <span id="detailsName">
                  {{ $selectedRecord[$detailsTitleField] ?? '—' }}
                </span>

                <span class="status-tag {{ $selectedRecord['status_key'] ?? 'active' }}" id="detailsStatus">
                  <span></span>
                  {{ $selectedRecord['status_label'] ?? '—' }}
                </span>
              </div>

              <div class="details-id">
                @foreach(($detailsUi['header_fields'] ?? []) as $headerField)
                <span>
                  @if(!empty($headerField['icon']))
                  <i class="{{ $headerField['icon'] }}"></i>
                  @endif

                  {{ $headerField['label'] ?? $headerField['field'] }}:

                  {{ $this->displayValue($selectedRecord, $headerField['field']) }}
                </span>
                @endforeach

                @if(empty($detailsUi['header_fields']))
                <span>
                  رقم السجل:
                  {{ $selectedRecord[$config['primary_key']] ?? '—' }}
                </span>
                @endif
              </div>
            </div>

            <div class="details-actions">
              @if($hasChildRelation)
              <button type="button" class="btn-action agent" wire:click="openChildModal">
                <i class="fas fa-plus"></i>
                {{ $config['btn_add_sub'] ?? 'إضافة' }}
              </button>
              @endif

              @if(!empty($config['actions']['edit']))
              <button type="button" class="btn-action edit"
                wire:click="openRecordModal({{ $selectedRecord[$config['primary_key']] }})">
                <i class="fas fa-pen"></i>
                تعديل
              </button>
              @endif

              @if(!empty($config['actions']['status']))
              <button type="button" class="btn-action block" id="blockBtn" wire:click="$set('showBlockModal', true)">
                <i class="fas fa-ban"></i>

                <span>
                  {{ ($selectedRecord['status_key'] ?? '') === 'blocked' ? 'إلغاء الحظر' : 'حظر' }}
                </span>
              </button>
              @endif

              @if(!empty($config['actions']['delete']))
              <button type="button" class="btn-action delete"
                wire:click="openDeleteModal({{ $selectedRecord[$config['primary_key']] }})">
                <i class="fas fa-trash"></i>
                حذف
              </button>
              @endif
            </div>
          </div>

          <div class="details-body">

            <div class="info-grid">
              @foreach(($detailsUi['info_fields'] ?? []) as $infoField)
              @php
              $fieldName = $infoField['field'] ?? null;

              $fieldValue = $fieldName
              ? $this->displayValue($selectedRecord, $fieldName)
              : '—';
              @endphp

              @if($fieldName)
              <div class="info-item">
                <div class="info-icon {{ $infoField['color'] ?? '' }}">
                  <i class="{{ $infoField['icon'] ?? 'fas fa-circle-info' }}"></i>
                </div>

                <div class="info-text">
                  <span class="info-label">
                    {{ $infoField['label'] ?? $fieldName }}
                  </span>

                  <strong class="info-value">
                    {{ $fieldValue }}
                  </strong>
                </div>
              </div>
              @endif
              @endforeach
            </div>

            @if($hasChildRelation)
            <div class="section-card">
              <div class="section-card-header">
                <i class="{{ $config['sub_section_icon'] ?? 'fas fa-list' }} section-icon"></i>

                <h3>{{ $config['sub_section_title'] ?? '' }}</h3>

                <span class="badge" id="agentsCount">
                  {{ count($selectedChildren) }}
                </span>

                <div class="agents-tools">
                  <div class="agent-search-mini">
                    <i class="fas fa-search"></i>

                    <input type="text" id="agentSearchInput" wire:model.live.debounce.300ms="childSearch"
                      placeholder="بحث...">
                  </div>

                  <button type="button" class="btn-add-small" wire:click="openChildModal">
                    <i class="fas fa-plus"></i>
                    إضافة
                  </button>
                </div>
              </div>

              <div class="section-card-body">
                @php
                $childTitleField = $relationUi['title_field'] ?? 'full_name';
                $childSubtitleField = $relationUi['subtitle_field'] ?? 'phone';
                $childTypeField = $relationUi['type_field'] ?? 'role';

                $childSearchTerm = mb_strtolower(trim($childSearch ?? ''));

                $filteredChildren = collect($selectedChildren)->filter(
                function($child) use (
                $childTitleField,
                $childSubtitleField,
                $childTypeField,
                $childSearchTerm
                ) {
                if ($childSearchTerm === '') {
                return true;
                }

                $haystack = mb_strtolower(
                (string)($child[$childTitleField] ?? '') . ' ' .
                (string)($child[$childSubtitleField] ?? '') . ' ' .
                (string)($child[$childTypeField] ?? '')
                );

                return mb_strpos($haystack, $childSearchTerm) !== false;
                }
                );
                @endphp

                <div id="agentsList">
                  @forelse($filteredChildren as $child)
                  @php
                  $childId = $child[$firstRel['primary_key']];
                  $childName = $child[$childTitleField] ?? '—';
                  $childStatus = $child['status'] ?? 'active';

                  $childBlocked = in_array(
                  $childStatus,
                  ['blocked', 'inactive'],
                  true
                  );

                  $childRole = $child[$childTypeField] ?? '';
                  $roleLabel = $childRole;

                  foreach (($firstRel['fields'] ?? []) as $childFieldDefinition) {
                  if (($childFieldDefinition['key'] ?? null) === $childTypeField) {
                  foreach (($childFieldDefinition['options'] ?? []) as $roleOption) {
                  if ((string)($roleOption['value'] ?? '') === (string)$childRole) {
                  $roleLabel = $roleOption['label'] ?? $childRole;
                  }
                  }
                  }
                  }
                  @endphp

                  <div class="agent-card {{ $childBlocked ? 'blocked' : '' }}" wire:key="uf-child-{{ $childId }}">
                    <div class="agent-avatar {{ $childBlocked ? 'blocked' : '' }}">
                      {{ mb_substr((string)$childName, 0, 1) }}
                    </div>

                    <div class="agent-info">
                      <div class="agent-name">
                        {{ $childName }}

                        @if($childBlocked)
                        <span class="blocked-tag">
                          {{ $childStatus === 'blocked' ? 'محظور' : 'غير نشط' }}
                        </span>
                        @endif
                      </div>

                      <div class="agent-meta">
                        <span>
                          <i class="{{ $relationUi['status_icon'] ?? 'fas fa-mobile-screen' }}"></i>
                          {{ $child[$childSubtitleField] ?? '' }}
                        </span>

                        @if($roleLabel)
                        <span class="agent-type-tag">{{ $roleLabel }}</span>
                        @endif
                      </div>
                    </div>

                    <div class="agent-actions">
                      @if(!empty($config['actions']['view']))
                      <button type="button" class="btn-agent-action view" wire:click="viewChild({{ $childId }})"
                        title="عرض">
                        <i class="fas fa-eye"></i>
                      </button>
                      @endif

                      @if(!empty($config['actions']['edit']))
                      <button type="button" class="btn-agent-action edit" wire:click="openChildModal({{ $childId }})"
                        title="تعديل">
                        <i class="fas fa-pen"></i>
                      </button>
                      @endif

                      @if(!empty($config['actions']['status']))
                      <button type="button" class="btn-agent-action {{ $childBlocked ? 'unblock' : 'block' }}"
                        wire:click="$set('pendingBlockChildId', {{ $childId }}); $set('showBlockChildModal', true)"
                        title="{{ $childBlocked ? 'تغيير الحالة' : 'حظر' }}">
                        <i class="fas fa-{{ $childBlocked ? 'unlock' : 'user-slash' }}"></i>
                      </button>
                      @endif

                      @if(!empty($config['actions']['delete']))
                      <button type="button" class="btn-agent-action delete"
                        wire:click="openDeleteChildModal({{ $childId }})" title="حذف">
                        <i class="fas fa-trash"></i>
                      </button>
                      @endif
                    </div>
                  </div>

                  @empty
                  <div class="agent-empty">
                    <i class="fas fa-user-tie"></i>

                    لا يوجد {{ $config['entity_sub'] ?? 'عناصر' }}
                    لهذا {{ $config['entity_singular'] ?? 'السجل' }}

                    <br>

                    <button type="button" class="btn-add-small" wire:click="openChildModal">
                      <i class="fas fa-plus"></i>
                      إضافة أول عنصر
                    </button>
                  </div>
                  @endforelse
                </div>
              </div>
            </div>
            @endif

            @if(!empty($config['ui']['details']['notes_field']))
            <div class="section-card">
              <div class="section-card-header">
                <i class="fas fa-note-sticky section-icon"></i>
                <h3>ملاحظات</h3>
              </div>

              <div class="section-card-body">
                <div class="notes-box" id="infoNotes">
                  {{ $selectedRecord[$detailsUi['notes_field']] ?? 'لا توجد ملاحظات.' }}
                </div>
              </div>
            </div>
            @endif

            {{-- عرض الصور والملفات المحفوظة دون تغيير حاوية التفاصيل الأصلية --}}
            @php
            $storedFileFields = array_filter(
            $config['main_fields'] ?? [],
            fn($field) => ($field['type'] ?? '') === 'file'
            );
            @endphp

            @if(count($storedFileFields))
            <div class="section-card">
              <div class="section-card-header">
                <i class="fas fa-paperclip section-icon"></i>
                <h3>الصور والملفات</h3>
              </div>

              <div class="section-card-body">
                <div class="info-grid">
                  @foreach($storedFileFields as $fileField)
                  @php
                  $rawStored = $selectedRecord[$fileField['key']] ?? null;

                  $storedPaths = is_array($rawStored)
                  ? $rawStored
                  : (
                  json_decode((string)$rawStored, true)
                  ?: ($rawStored ? [$rawStored] : [])
                  );

                  $disk = $fileField['upload']['disk'] ?? 'public';
                  @endphp

                  @if(count($storedPaths))
                  <div class="form-group full">
                    <label class="form-label">
                      {{ $fileField['label'] ?? $fileField['key'] }}
                    </label>

                    <div class="d-flex flex-wrap gap-2">
                      @foreach($storedPaths as $storedPath)
                      @php
                      $fileUrl = \Illuminate\Support\Facades\Storage::disk($disk)->url($storedPath);
                      $extension = strtolower(pathinfo($storedPath, PATHINFO_EXTENSION));
                      @endphp

                      @if(in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                      <a href="{{ $fileUrl }}" target="_blank" rel="noopener" title="{{ basename($storedPath) }}">
                        <img src="{{ $fileUrl }}" alt="{{ $fileField['label'] ?? '' }}" loading="lazy"
                          style="width:110px;height:85px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                      </a>
                      @else
                      <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-light border">
                        <i class="fas fa-file-arrow-up"></i>
                        {{ basename($storedPath) }}
                      </a>
                      @endif
                      @endforeach
                    </div>
                  </div>
                  @endif
                  @endforeach
                </div>
              </div>
            </div>
            @endif

          </div>
        </div>
        @endif
        
      </main>
    </div>
  </div>

  {{-- ============================================================
  Modal: Save Record (Owner / Main Record)
  ============================================================ --}}
  <div class="modal-overlay {{ $showRecordModal ? 'show' : '' }}" id="ownerModal"
    aria-hidden="{{ $showRecordModal ? 'false' : 'true' }}">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-header-icon">
          <i class="fas fa-plus"></i>
        </div>

        <div class="modal-header-text">
          <h2 id="ownerModalTitle">
            {{ $editingId ? 'تعديل ' . ($config['entity_singular'] ?? 'السجل') : ($config['btn_add_main'] ?? 'إضافة') }}
          </h2>

          <p id="ownerModalSubtitle">أدخل البيانات</p>
        </div>

        <button type="button" class="modal-close" wire:click="closeRecordModal">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

      <div class="modal-body">
        <form id="ownerForm" wire:submit="saveRecord">

          
<div class="form-grid">
    @foreach(($config['main_fields'] ?? []) as $f)
        @php
            $isFull = !empty($f['full']);
            $fieldType = $f['type'] ?? 'text';
            $fieldKey = $f['key'];

            $fieldOptions = $this->fieldOptions($f);

            // مصدر الرسالة التوضيحية: الحقل ثم إعدادات الكيان.
            $fieldHelp = $f['help']
                ?? $f['hint']
                ?? ($config['field_help'][$fieldKey] ?? null)
                ?? ($config['field_hints'][$fieldKey] ?? null);

            $fieldInfo = $f['info']
                ?? ($config['field_info'][$fieldKey] ?? null);

            $fieldWarning = $f['warning']
                ?? ($config['field_warning'][$fieldKey] ?? null);

            // أولوية النص الذي يظهر داخل الحقل.
            $fieldPlaceholder = $fieldHelp
                ?: ($fieldInfo ?: ($fieldWarning ?: ($f['placeholder'] ?? '')));

            $inputId = 'f_' . $fieldKey;
        @endphp

        @if($this->isTenantManagedField($fieldKey))
            @continue
        @endif

        <div class="form-group {{ $isFull ? 'full' : '' }}">

            @if($fieldType !== 'checkbox')
                <label class="form-label" for="{{ $inputId }}">
                    {{ $f['label'] ?? $fieldKey }}

                    @if(!empty($f['required']))
                        <span class="required">*</span>
                    @endif
                </label>
            @endif

            @if($fieldType === 'select')

                <select
                    class="form-control text-center"
                    id="{{ $inputId }}"
                    wire:model.live="form.{{ $fieldKey }}"
                >
                    <option value="">
                        {{ $fieldPlaceholder ?: '— اختر —' }}
                    </option>

                    @foreach($fieldOptions as $opt)
                        <option value="{{ $opt['id'] ?? $opt['value'] ?? '' }}">
                            {{ $opt['name'] ?? $opt['label'] ?? '' }}
                        </option>
                    @endforeach
                </select>

            @elseif($fieldType === 'textarea')

                <textarea
                    class="form-control text-center"
                    id="{{ $inputId }}"
                    rows="3"
                    wire:model.live.debounce.500ms="form.{{ $fieldKey }}"
                    placeholder="{{ $fieldPlaceholder }}"
                ></textarea>

            @elseif($fieldType === 'checkbox')

                <div class="form-check d-flex align-items-center gap-2">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="{{ $inputId }}"
                        wire:model.live="form.{{ $fieldKey }}"
                    >

                    <label class="form-check-label mb-0" for="{{ $inputId }}">
                        {{ $f['label'] ?? $fieldKey }}

                        @if(!empty($f['required']))
                            <span class="required">*</span>
                        @endif
                    </label>
                </div>

                @if($fieldHelp || $fieldInfo || $fieldWarning)
                    <small class="d-block text-muted mt-1">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ $fieldHelp ?: ($fieldInfo ?: $fieldWarning) }}
                    </small>
                @endif

            @elseif($fieldType === 'file')

                <input
                    type="file"
                    class="form-control"
                    id="{{ $inputId }}"
                    wire:model="mainUploads.{{ $fieldKey }}"
                    @if(!empty($f['upload']['multiple'])) multiple @endif
                    accept="{{ !empty($f['upload']['mimes']) ? '.' . implode(',.', $f['upload']['mimes']) : '' }}"
                >

                <div
                    wire:loading
                    wire:target="mainUploads.{{ $fieldKey }}"
                    class="small text-muted mt-1"
                >
                    جارٍ رفع الملف...
                </div>

                @if($fieldHelp || $fieldInfo || $fieldWarning)
                    <small class="d-block text-muted mt-1">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ $fieldHelp ?: ($fieldInfo ?: $fieldWarning) }}
                    </small>
                @endif

                @if(!empty($f['upload']['multiple']))
                    <small class="text-muted d-block mt-1">
                        يمكن اختيار أكثر من ملف.
                    </small>
                @endif

                @if($editingId && !empty($selectedRecord[$fieldKey]))
                    @php
                        $currentStored = $selectedRecord[$fieldKey];

                        $currentPaths = is_array($currentStored)
                            ? $currentStored
                            : (
                                json_decode((string)$currentStored, true)
                                ?: [$currentStored]
                            );
                    @endphp

                    <small class="d-block text-muted mt-1">
                        الملفات الحالية: {{ count($currentPaths) }} —
                        ارفع ملفات جديدة لاستبدالها وفق إعداد الحقل.
                    </small>
                @endif

            @else

    <input
        type="{{ in_array($fieldType, ['text', 'email', 'tel', 'date', 'number', 'url', 'time']) ? $fieldType : 'text' }}"
        class="form-control text-center"
        id="{{ $inputId }}"
        wire:model="form.{{ $fieldKey }}"
        placeholder="{{ $fieldPlaceholder }}"
        @if($fieldType === 'number')
            step="{{ $f['step'] ?? 'any' }}"
            @if(isset($f['min'])) min="{{ $f['min'] }}" @endif
            @if(isset($f['max'])) max="{{ $f['max'] }}" @endif
        @endif
    >



            @endif

            {{-- أخطاء التحقق لا تظهر إلا إذا كان الحقل يحتوي على خطأ --}}
            @error($fieldKey)
                <small class="text-danger d-block mt-1">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ $message }}
                </small>
            @enderror

            @error('mainUploads.' . $fieldKey)
                <small class="text-danger d-block mt-1">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ $message }}
                </small>
            @enderror

        </div>
    @endforeach
</div>


          {{-- منتقي الموقع يظهر فقط للكيانات التي تفعله في configs.php --}}
          @if(!empty($location['enabled']))
          <div class="section-card mt-3">
            <div class="section-card-header">
              <i class="fas fa-map-location-dot section-icon"></i>

              <h3>
                تحديد موقع {{ $config['entity_singular'] ?? 'السجل' }} على الخريطة
              </h3>
            </div>

            <div class="section-card-body">
              <p class="text-muted small mb-2">
                انقر على الخريطة لتعبئة خط العرض وخط الطول تلقائياً،
                ويمكنك أيضاً تعديل الإحداثيات يدوياً.
              </p>

              <div id="uf-map-{{ $componentId }}" data-uf-map data-latitude-field="{{ $latField }}"
                data-longitude-field="{{ $lngField }}"
                data-initial-lat="{{ $form[$latField] ?? $location['default_latitude'] ?? 24.7136 }}"
                data-initial-lng="{{ $form[$lngField] ?? $location['default_longitude'] ?? 46.6753 }}"
                data-zoom="{{ $location['zoom'] ?? 13 }}"
                data-tile-url="{{ $location['tile_url'] ?? 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png' }}"
                data-attribution="{{ $location['tile_attribution'] ?? '&copy; OpenStreetMap contributors' }}"
                style="height:{{ $location['height'] ?? '320px' }};width:100%;border-radius:10px;z-index:1"></div>

              <div class="row g-2 mt-2">
                <div class="row g-2 mt-2">
                  <div class="col-md-6">
                    <label class="form-label">خط العرض</label>

                    <input type="number" step="any" min="-90" max="90" inputmode="decimal" class="form-control"
                      wire:model="form.{{ $latField }}">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">خط الطول</label>

                    <input type="number" step="any" min="-180" max="180" inputmode="decimal" class="form-control"
                      wire:model="form.{{ $lngField }}">
                  </div>
                </div>
              </div>

              @error('form.' . $latField)
              <small class="text-danger">{{ $message }}</small>
              @enderror

              @error('form.' . $lngField)
              <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>
          @endif

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="closeRecordModal">
          إلغاء
        </button>

        <button type="submit" form="ownerForm" class="btn btn-primary" wire:loading.attr="disabled"
          wire:target="saveRecord,mainUploads">
          <i class="fas fa-floppy-disk"></i>

          <span id="saveOwnerText">
            {{ $editingId ? 'حفظ التعديلات' : 'حفظ' }}
          </span>
        </button>
      </div>
    </div>
  </div>

  {{-- ============================================================
  Modal: Save Child (Agent / Related)
  ============================================================ --}}
  @if($hasChildRelation)
  <div class="modal-overlay {{ $showChildModal ? 'show' : '' }}" id="agentModal"
    aria-hidden="{{ $showChildModal ? 'false' : 'true' }}">
    <div class="modal medium">
      <div class="modal-header">
        <div class="modal-header-icon">
          <i class="{{ $config['sub_section_icon'] ?? 'fas fa-list' }}"></i>
        </div>

        <div class="modal-header-text">
          <h2 id="agentModalTitle">
            {{ $editingChildId ? 'تعديل ' . ($config['entity_sub'] ?? 'العنصر') : ($config['btn_add_sub'] ?? 'إضافة') }}
          </h2>

          <p id="agentModalSubtitle">أدخل البيانات</p>
        </div>

        <button type="button" class="modal-close" wire:click="closeChildModal">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

    

<form id="agentForm" wire:submit="saveChild">
    <div class="form-grid">
        @foreach(($firstRel['fields'] ?? []) as $f)
            @php
                $isFull = !empty($f['full']);
                $fieldType = $f['type'] ?? 'text';
                $fieldKey = $f['key'];

                $fieldOptions = $this->fieldOptions($f);

                $fieldHelp = $f['help']
                    ?? $f['hint']
                    ?? ($firstRel['field_help'][$fieldKey] ?? null)
                    ?? ($firstRel['field_hints'][$fieldKey] ?? null)
                    ?? ($config['field_help'][$fieldKey] ?? null)
                    ?? ($config['field_hints'][$fieldKey] ?? null);

                $fieldInfo = $f['info']
                    ?? ($firstRel['field_info'][$fieldKey] ?? null)
                    ?? ($config['field_info'][$fieldKey] ?? null);

                $fieldWarning = $f['warning']
                    ?? ($firstRel['field_warning'][$fieldKey] ?? null)
                    ?? ($config['field_warning'][$fieldKey] ?? null);

                $fieldPlaceholder = $fieldHelp
                    ?: ($fieldInfo ?: ($fieldWarning ?: ($f['placeholder'] ?? '')));

                $inputId = 'a_' . $fieldKey;
            @endphp

            <div class="form-group {{ $isFull ? 'full' : '' }}">

                @if($fieldType !== 'checkbox')
                    <label class="form-label" for="{{ $inputId }}">
                        {{ $f['label'] ?? $fieldKey }}

                        @if(!empty($f['required']))
                            <span class="required">*</span>
                        @endif
                    </label>
                @endif

                @if($fieldType === 'select')

                    <select
                        class="form-control text-center"
                        id="{{ $inputId }}"
                        wire:model.live="childForm.{{ $fieldKey }}"
                    >
                        <option value="">
                            {{ $fieldPlaceholder ?: '— اختر —' }}
                        </option>

                        @foreach($fieldOptions as $opt)
                            <option value="{{ $opt['id'] ?? $opt['value'] ?? '' }}">
                                {{ $opt['name'] ?? $opt['label'] ?? '' }}
                            </option>
                        @endforeach
                    </select>

                @elseif($fieldType === 'textarea')

                    <textarea
                        class="form-control text-center"
                        id="{{ $inputId }}"
                        rows="2"
                        wire:model.live.debounce.500ms="childForm.{{ $fieldKey }}"
                        placeholder="{{ $fieldPlaceholder }}"
                    ></textarea>

                @elseif($fieldType === 'checkbox')

                    <div class="form-check d-flex align-items-center gap-2">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="{{ $inputId }}"
                            wire:model.live="childForm.{{ $fieldKey }}"
                        >

                        <label class="form-check-label mb-0" for="{{ $inputId }}">
                            {{ $f['label'] ?? $fieldKey }}
                        </label>
                    </div>

                    @if($fieldHelp || $fieldInfo || $fieldWarning)
                        <small class="d-block text-muted mt-1">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ $fieldHelp ?: ($fieldInfo ?: $fieldWarning) }}
                        </small>
                    @endif

                @elseif($fieldType === 'file')

                    <input
                        type="file"
                        class="form-control"
                        id="{{ $inputId }}"
                        wire:model="childUploads.{{ $fieldKey }}"
                        @if(!empty($f['upload']['multiple'])) multiple @endif
                        accept="{{ !empty($f['upload']['mimes']) ? '.' . implode(',.', $f['upload']['mimes']) : '' }}"
                    >

                    <div
                        wire:loading
                        wire:target="childUploads.{{ $fieldKey }}"
                        class="small text-muted mt-1"
                    >
                        جارٍ رفع الملف...
                    </div>

                    @if($fieldHelp || $fieldInfo || $fieldWarning)
                        <small class="d-block text-muted mt-1">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ $fieldHelp ?: ($fieldInfo ?: $fieldWarning) }}
                        </small>
                    @endif

                    @if($editingChildId)
                        <small class="d-block text-muted mt-1">
                            يوجد ملف محفوظ؛ ارفع ملفاً جديداً لاستبداله.
                        </small>
                    @endif

                @else

                    <input
                        type="{{ in_array($fieldType, ['text', 'email', 'tel', 'date', 'number', 'url', 'time']) ? $fieldType : 'text' }}"
                        class="form-control text-center"
                        id="{{ $inputId }}"
                        wire:model.live.debounce.500ms="childForm.{{ $fieldKey }}"
                        placeholder="{{ $fieldPlaceholder }}"
                        @if($fieldType === 'number')
                            step="{{ $f['step'] ?? 'any' }}"
                            @if(isset($f['min'])) min="{{ $f['min'] }}" @endif
                            @if(isset($f['max'])) max="{{ $f['max'] }}" @endif
                        @endif
                    >

                @endif

                {{-- أخطاء التحقق --}}
                @error($fieldKey)
                    <small class="text-danger d-block mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </small>
                @enderror

                @error('childUploads.' . $fieldKey)
                    <small class="text-danger d-block mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </small>
                @enderror

            </div>
        @endforeach
    </div>
</form>



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="closeChildModal">
          إلغاء
        </button>

        <button type="submit" form="agentForm" class="btn btn-gold" wire:loading.attr="disabled"
          wire:target="saveChild,childUploads">
          <i class="fas fa-floppy-disk"></i>

          <span id="saveAgentText">
            {{ $config['btn_add_sub'] ?? 'إضافة' }}
          </span>
        </button>
      </div>
    </div>
  </div>

  {{-- Modal: View Child --}}
  <div class="modal-overlay {{ $showChildViewModal ? 'show' : '' }}" id="agentViewModal"
    aria-hidden="{{ $showChildViewModal ? 'false' : 'true' }}">
    <div class="modal small">
      <div class="modal-header">
        <div class="modal-header-icon">
          <i class="fas fa-id-badge"></i>
        </div>

        <div class="modal-header-text">
          <h2>تفاصيل {{ $config['entity_sub'] ?? '' }}</h2>
          <p>معلومات كاملة</p>
        </div>

        <button type="button" class="modal-close" wire:click="$set('showChildViewModal', false)">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

      <div class="modal-body" id="agentViewBody">
        @if($viewingChild)
        @foreach(($firstRel['fields'] ?? []) as $viewField)
        @php
        $viewKey = $viewField['key'];
        @endphp

        @if(($viewField['type'] ?? '') === 'file')
        @php
        $viewPathValue = $viewingChild[$viewKey] ?? null;

        $viewPaths = is_array($viewPathValue)
        ? $viewPathValue
        : (
        json_decode((string)$viewPathValue, true)
        ?: ($viewPathValue ? [$viewPathValue] : [])
        );
        @endphp

        @foreach($viewPaths as $viewPath)
        <p>
          <strong>{{ $viewField['label'] }}:</strong>

          <a target="_blank" rel="noopener"
            href="{{ \Illuminate\Support\Facades\Storage::disk($viewField['upload']['disk'] ?? 'public')->url($viewPath) }}">
            عرض الملف
          </a>
        </p>
        @endforeach

        @else
        <p class="mb-2">
          <strong>{{ $viewField['label'] }}:</strong>
          {{ $viewingChild[$viewKey] ?? '—' }}
        </p>
        @endif
        @endforeach
        @endif
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$set('showChildViewModal', false)">
          إغلاق
        </button>

        @if($viewingChild)
        <button type="button" class="btn btn-primary"
          wire:click="$set('showChildViewModal', false); openChildModal({{ $viewingChild[$firstRel['primary_key']] ?? 0 }})">
          <i class="fas fa-pen"></i>
          تعديل
        </button>
        @endif
      </div>
    </div>
  </div>

  {{-- Modal: Delete Child --}}
  <div class="modal-overlay {{ $showDeleteChildModal ? 'show' : '' }}" id="deleteAgentModal"
    aria-hidden="{{ $showDeleteChildModal ? 'false' : 'true' }}">
    <div class="modal small">
      <div class="modal-header">
        <div class="modal-header-icon"
          style="background: linear-gradient(135deg, var(--cherry), #EF4444); color: #fff;">
          <i class="fas fa-trash"></i>
        </div>

        <div class="modal-header-text">
          <h2>تأكيد حذف {{ $config['entity_sub'] ?? '' }}</h2>
          <p>هذا الإجراء لا يمكن التراجع عنه</p>
        </div>

        <button type="button" class="modal-close" wire:click="$set('showDeleteChildModal', false)">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

      <div class="modal-body">
        <div class="confirm-content">
          <div class="confirm-icon">
            <i class="fas fa-triangle-exclamation"></i>
          </div>

          <h3>حذف {{ $config['entity_sub'] ?? '' }}؟</h3>

          <p>
            سيتم حذف <strong id="deleteAgentName">العنصر المحدد</strong> نهائياً.
          </p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteChildModal', false)">
          إلغاء
        </button>

        <button type="button" class="btn btn-danger" wire:click="deleteChild">
          <i class="fas fa-trash"></i>
          نعم، احذف
        </button>
      </div>
    </div>
  </div>

  {{-- Modal: Block Child --}}
  <div class="modal-overlay {{ $showBlockChildModal ? 'show' : '' }}" id="blockAgentModal"
    aria-hidden="{{ $showBlockChildModal ? 'false' : 'true' }}">
    <div class="modal small">
      <div class="modal-header">
        <div class="modal-header-icon" id="blockAgentIcon"
          style="background: linear-gradient(135deg, var(--terracotta), #F59E0B); color: #fff;">
          <i class="fas fa-user-slash"></i>
        </div>

        <div class="modal-header-text">
          <h2 id="blockAgentTitle">
            حظر {{ $config['entity_sub'] ?? '' }}
          </h2>

          <p id="blockAgentSubtitle">سيتم منع الوصول</p>
        </div>

        <button type="button" class="modal-close" wire:click="$set('showBlockChildModal', false)">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

      <div class="modal-body">
        <div class="confirm-content">
          <div class="confirm-icon warning" id="blockAgentConfirmIcon">
            <i class="fas fa-user-slash"></i>
          </div>

          <h3 id="blockAgentConfirmTitle">
            حظر {{ $config['entity_sub'] ?? '' }}؟
          </h3>

          <p id="blockAgentConfirmText">سيتم الحظر.</p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$set('showBlockChildModal', false)">
          إلغاء
        </button>

        <button type="button" class="btn"
          wire:click="toggleChildStatus({{ $pendingBlockChildId ?? 0 }}); $set('showBlockChildModal', false)">
          <i class="fas fa-user-slash"></i>
          <span>حظر</span>
        </button>
      </div>
    </div>
  </div>
  @endif

  {{-- Modal: Delete Record --}}
  <div class="modal-overlay {{ $showDeleteModal ? 'show' : '' }}" id="deleteOwnerModal"
    aria-hidden="{{ $showDeleteModal ? 'false' : 'true' }}">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-header-icon"
          style="background: linear-gradient(135deg, var(--cherry), #EF4444); color: #fff;">
          <i class="fas fa-trash"></i>
        </div>

        <div class="modal-header-text">
          <h2>تأكيد الحذف</h2>
          <p>هذا الإجراء لا يمكن التراجع عنه</p>
        </div>

        <button type="button" class="modal-close" wire:click="$set('showDeleteModal', false)">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

      <div class="modal-body">
        <div class="confirm-content">
          <div class="confirm-icon">
            <i class="fas fa-triangle-exclamation"></i>
          </div>

          <h3>هل أنت متأكد من الحذف؟</h3>

          <p>
            سيتم حذف
            <strong id="deleteOwnerName">
              {{ $selectedRecord[$nameField] ?? 'السجل المحدد' }}
            </strong>
            نهائياً.
          </p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">
          إلغاء
        </button>

        <button type="button" class="btn btn-danger" wire:click="deleteRecord">
          <i class="fas fa-trash"></i>
          نعم، احذف
        </button>
      </div>
    </div>
  </div>

  {{-- Modal: Block Record --}}
  <div class="modal-overlay {{ $showBlockModal ? 'show' : '' }}" id="blockOwnerModal"
    aria-hidden="{{ $showBlockModal ? 'false' : 'true' }}">
    <div class="modal small">
      <div class="modal-header">
        <div class="modal-header-icon" id="blockModalIcon"
          style="background: linear-gradient(135deg, var(--terracotta), #F59E0B); color: #fff;">
          <i class="fas fa-ban"></i>
        </div>

        <div class="modal-header-text">
          <h2 id="blockModalTitle">حظر</h2>
          <p id="blockModalSubtitle">سيتم منع الوصول</p>
        </div>

        <button type="button" class="modal-close" wire:click="$set('showBlockModal', false)">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

      <div class="modal-body">
        <div class="confirm-content">
          <div class="confirm-icon warning" id="blockConfirmIcon">
            <i class="fas fa-ban"></i>
          </div>

          <h3 id="blockConfirmTitle">حظر؟</h3>
          <p id="blockConfirmText">سيتم الحظر.</p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$set('showBlockModal', false)">
          إلغاء
        </button>

        <button type="button" class="btn"
          wire:click="toggleRecordStatus({{ $selectedId ?? 0 }}); $set('showBlockModal', false)">
          <i class="fas fa-ban"></i>
          <span>حظر</span>
        </button>
      </div>
    </div>
  </div>

  {{-- Toast --}}
  @if($toastMessage)
  <div class="toast show {{ $toastType }}" id="toast" role="alert" wire:key="toast-{{ md5($toastMessage) }}" x-data="{}"
    x-init="setTimeout(() => $wire.dismissToast(), 3000)">
    <i class="fas {{ $toastType === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' }}"></i>

    <span id="toastMsg">{{ $toastMessage }}</span>

    <button type="button" class="btn-close ms-2" wire:click="dismissToast" aria-label="إغلاق"></button>
  </div>
  @else
  <div class="toast" id="toast" wire:key="toast-empty">
    <i class="fas fa-circle-check"></i>
    <span id="toastMsg"></span>
  </div>
  @endif

 

</div>

{{-- ============================================================
UniversalForm — Livewire Map Bridge
لا يوجد هنا ربط CRUD أو بحث أو فلاتر؛ هذا الجزء مخصص للخريطة فقط.
============================================================ --}}
@script
<script>
  (() => {
    const root = $wire.$el;

    let map = null;
    let marker = null;
    let mapElement = null;

    const getElement = () => root.querySelector('[data-uf-map]');

    const refreshMap = (payload = {}) => {
      mapElement = getElement();

      if (!mapElement || !window.L) {
        return;
      }

      const detail = payload.detail ?? payload;

      const rawLat = detail.lat ?? mapElement.dataset.initialLat;
      const rawLng = detail.lng ?? mapElement.dataset.initialLng;

      const lat = Number(rawLat);
      const lng = Number(rawLng);

      const startLat = Number.isFinite(lat) ? lat : 24.7136;
      const startLng = Number.isFinite(lng) ? lng : 46.6753;

      const zoom = Number(mapElement.dataset.zoom) || 13;

      if (!map) {
        map = L.map(mapElement).setView(
          [startLat, startLng],
          zoom
        );

        L.tileLayer(mapElement.dataset.tileUrl, {
          attribution: mapElement.dataset.attribution,
          maxZoom: 19
        }).addTo(map);

        marker = L.marker([
          startLat,
          startLng
        ]).addTo(map);

        map.on('click', (event) => {
          const selectedLat = Number(
            event.latlng.lat.toFixed(7)
          );

          const selectedLng = Number(
            event.latlng.lng.toFixed(7)
          );

          marker.setLatLng([
            selectedLat,
            selectedLng
          ]);

          $wire.set(
            'form.' + mapElement.dataset.latitudeField,
            selectedLat
          );

          $wire.set(
            'form.' + mapElement.dataset.longitudeField,
            selectedLng
          );
        });

      } else {
        marker.setLatLng([
          startLat,
          startLng
        ]);

        map.setView([
          startLat,
          startLng
        ], zoom);
      }

      window.setTimeout(() => {
        if (map) {
          map.invalidateSize();
        }
      }, 180);
    };

    $wire.on('uf-map-refresh', (payload) => {
      refreshMap(payload);
    });

    $wire.$watch('form', (form) => {
      if (!map || !marker || !mapElement) {
        return;
      }

      const lat = Number(
        form[mapElement.dataset.latitudeField]
      );

      const lng = Number(
        form[mapElement.dataset.longitudeField]
      );

      if (Number.isFinite(lat) && Number.isFinite(lng)) {
        marker.setLatLng([
          lat,
          lng
        ]);
      }
    });
  })();
</script>
@endscript