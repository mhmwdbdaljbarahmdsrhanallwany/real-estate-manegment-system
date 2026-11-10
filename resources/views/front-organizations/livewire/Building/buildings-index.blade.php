<div wire:key="bm-root">

{{-- ═══════════════════════════════════════════════════════════════
     كل CSS + HTML داخل wire:ignore
═══════════════════════════════════════════════════════════════ --}}
<div wire:ignore>

<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
.bm { --royal:#0F2B46; --midnight:#1B4D7A; --gold:#C9A227; --gold-light:#E8CB5C;
  --emerald:#059669; --terracotta:#D97706; --cherry:#DC2626; --sky:#0284C7; --purple:#7C3AED;
  --bg-page:#F7F9FC; --bg-card:#FFF; --bg-field:#EEF2F7;
  --text-primary:#1E293B; --text-secondary:#64748B; --text-muted:#94A3B8;
  --border-soft:#E2E8F0;
  --shadow-sm:0 1px 3px rgba(15,43,70,.06); --shadow-md:0 4px 16px rgba(15,43,70,.08);
  --shadow-lg:0 12px 40px rgba(15,43,70,.12);
  --radius-sm:8px; --radius-md:12px; --radius-lg:16px;
  --transition:all .28s cubic-bezier(.4,0,.2,1);
  font-family:'Tajawal',sans-serif; background:var(--bg-page); color:var(--text-primary); line-height:1.5; }
.bm *, .bm *::before, .bm *::after { box-sizing:border-box; margin:0; padding:0; }

.bm .toolbar { background:var(--bg-card); box-shadow:var(--shadow-sm); padding:.75rem 1rem; display:flex; align-items:center; gap:.65rem; flex-wrap:wrap; border-bottom:1px solid var(--border-soft); }
.bm .toolbar-title { display:flex; align-items:center; gap:.55rem; }
.bm .toolbar-icon { width:42px; height:42px; border-radius:11px; background:linear-gradient(135deg,var(--royal),var(--midnight)); color:var(--gold-light); display:flex; align-items:center; justify-content:center; font-size:1.1rem; box-shadow:0 4px 12px rgba(15,43,70,.25); }
.bm .toolbar-text h1 { font-size:1.1rem; font-weight:800; color:var(--royal); }
.bm .toolbar-text p { font-size:.72rem; color:var(--text-secondary); }
.bm .toolbar-actions { margin-inline-start:auto; display:flex; gap:.35rem; flex-wrap:wrap; }
.bm .btn-add { background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:var(--royal); border:none; padding:.5rem 1rem; border-radius:var(--radius-sm); font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:.35rem; box-shadow:0 3px 10px rgba(201,162,39,.35); height:36px; }
.bm .btn-add:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(201,162,39,.5); }

.bm .filters { background:var(--bg-card); border-bottom:1px solid var(--border-soft); padding:.7rem 1rem; }
.bm .filters-row { display:flex; align-items:center; gap:.4rem; flex-wrap:wrap; margin-bottom:.5rem; }
.bm .filters-row:last-child { margin-bottom:0; }
.bm .filter-item { display:flex; flex-direction:column; gap:.2rem; flex:1; min-width:140px; }
.bm .filter-item.sm { flex:0 0 auto; min-width:130px; }
.bm .filter-label { font-size:.65rem; font-weight:600; color:var(--text-secondary); display:flex; align-items:center; gap:.25rem; }
.bm .filter-label i { color:var(--gold); font-size:.6rem; }
.bm .filter-control { width:100%; padding:.42rem .6rem; font-family:inherit; font-size:.76rem; color:var(--text-primary); background:var(--bg-field); border:1.5px solid transparent; border-radius:var(--radius-sm); outline:none; height:34px; transition:var(--transition); }
.bm .filter-control:focus { border-color:var(--midnight); background:#fff; box-shadow:0 0 0 3px rgba(27,77,122,.1); }
.bm .filter-search { position:relative; display:flex; align-items:center; flex:2; min-width:220px; }
.bm .filter-search i { position:absolute; right:.65rem; color:var(--text-muted); font-size:.72rem; pointer-events:none; }
.bm .filter-search input { width:100%; padding:.42rem 2rem .42rem .65rem; font-family:inherit; font-size:.78rem; background:var(--bg-field); border:1.5px solid transparent; border-radius:var(--radius-sm); height:34px; transition:var(--transition); }
.bm .filter-search input:focus { border-color:var(--midnight); background:#fff; box-shadow:0 0 0 3px rgba(27,77,122,.1); }
.bm .filter-actions { margin-inline-start:auto; display:flex; gap:.3rem; }
.bm .btn-filter { padding:.4rem .8rem; border-radius:var(--radius-sm); border:1.5px solid var(--border-soft); background:#fff; color:var(--text-secondary); font-family:inherit; font-size:.72rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:.3rem; height:34px; }
.bm .btn-filter.clear:hover { background:#FEF2F2; color:var(--cherry); border-color:rgba(220,38,38,.3); }

.bm .stats { display:grid; grid-template-columns:repeat(4,1fr); background:var(--bg-card); border-bottom:1px solid var(--border-soft); }
.bm .stat { padding:.75rem 1rem; display:flex; align-items:center; gap:.6rem; border-left:1px solid var(--border-soft); transition:var(--transition); }
.bm .stat:last-child { border-left:none; }
.bm .stat:hover { background:var(--bg-field); }
.bm .stat-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.9rem; }
.bm .stat-icon.royal { background:rgba(15,43,70,.1); color:var(--royal); }
.bm .stat-icon.emerald { background:rgba(5,150,105,.12); color:var(--emerald); }
.bm .stat-icon.sky { background:rgba(2,132,199,.12); color:var(--sky); }
.bm .stat-icon.gold { background:rgba(201,162,39,.15); color:var(--gold); }
.bm .stat-value { font-size:1.15rem; font-weight:800; color:var(--royal); line-height:1.1; }
.bm .stat-label { font-size:.65rem; color:var(--text-secondary); font-weight:500; }

.bm .workspace { display:grid; grid-template-columns:400px 1fr; min-height:calc(100vh - 280px); }
.bm .list-panel { background:var(--bg-card); border-left:1px solid var(--border-soft); display:flex; flex-direction:column; max-height:calc(100vh - 280px); position:sticky; top:0; overflow:hidden; }
.bm .panel-head { padding:.7rem 1rem; border-bottom:1px solid var(--border-soft); background:linear-gradient(135deg,rgba(15,43,70,.02),rgba(201,162,39,.04)); display:flex; align-items:center; justify-content:space-between; }
.bm .panel-head-title { display:flex; align-items:center; gap:.4rem; font-size:.85rem; font-weight:700; color:var(--royal); }
.bm .panel-head-title i { color:var(--gold); font-size:.85rem; }
.bm .panel-head-title .count { background:var(--bg-field); color:var(--midnight); padding:.1rem .5rem; border-radius:999px; font-size:.65rem; font-weight:700; }
.bm .list-body { flex:1; overflow-y:auto; padding:.5rem; }

.bm .b-card { background:#fff; border:1.5px solid var(--border-soft); border-radius:var(--radius-md); margin-bottom:.55rem; cursor:pointer; transition:var(--transition); overflow:hidden; position:relative; }
.bm .b-card:hover { border-color:var(--gold-light); box-shadow:var(--shadow-md); transform:translateY(-1px); }
.bm .b-card.active { border-color:var(--gold); box-shadow:0 4px 16px rgba(201,162,39,.2); }
.bm .b-card.active::before { content:''; position:absolute; right:0; top:0; bottom:0; width:4px; background:linear-gradient(180deg,var(--gold),var(--gold-light)); }
.bm .b-head { padding:.75rem .85rem; display:flex; align-items:center; gap:.6rem; }
.bm .b-icon { width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg,var(--royal),var(--midnight)); color:var(--gold-light); display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.bm .b-card.inactive .b-icon { background:linear-gradient(135deg,#64748B,#94A3B8); color:#fff; }
.bm .b-info { flex:1; min-width:0; }
.bm .b-name { font-size:.82rem; font-weight:700; color:var(--text-primary); margin-bottom:.15rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:flex; align-items:center; gap:.35rem; }
.bm .b-addr { font-size:.68rem; color:var(--text-secondary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:flex; align-items:center; gap:.25rem; }
.bm .b-addr i { font-size:.55rem; color:var(--text-muted); }
.bm .b-badges { display:flex; gap:.25rem; flex-wrap:wrap; padding:0 .85rem .65rem; }
.bm .badge { font-size:.62rem; font-weight:600; padding:.18rem .5rem; border-radius:999px; display:inline-flex; align-items:center; gap:.25rem; }
.bm .badge.on { background:rgba(5,150,105,.12); color:var(--emerald); }
.bm .badge.off { background:rgba(220,38,38,.1); color:var(--cherry); }
.bm .badge.units { background:rgba(2,132,199,.12); color:var(--sky); }
.bm .badge.floors { background:rgba(201,162,39,.15); color:var(--gold); }
.bm .badge.loc { background:rgba(5,150,105,.12); color:var(--emerald); }
.bm .badge.noloc { background:rgba(217,119,6,.12); color:var(--terracotta); }

.bm .details { background:var(--bg-card); min-height:calc(100vh - 280px); }
.bm .empty { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:3rem 1.5rem; text-align:center; min-height:calc(100vh - 280px); }
.bm .empty i { font-size:4rem; color:var(--border-soft); margin-bottom:1rem; }
.bm .empty h3 { font-size:1.05rem; font-weight:700; margin-bottom:.4rem; }
.bm .empty p { font-size:.8rem; color:var(--text-muted); max-width:300px; margin-bottom:1rem; }

.bm .d-head { padding:1.25rem 1.5rem; background:linear-gradient(135deg,rgba(15,43,70,.03),rgba(201,162,39,.05)); border-bottom:1px solid var(--border-soft); display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
.bm .d-avatar { width:68px; height:68px; border-radius:16px; background:linear-gradient(135deg,var(--royal),var(--midnight)); color:var(--gold-light); display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0; box-shadow:0 6px 20px rgba(15,43,70,.25); }
.bm .d-avatar.inactive { background:linear-gradient(135deg,#64748B,#94A3B8); color:#fff; }
.bm .d-info { flex:1; min-width:0; }
.bm .d-name { font-size:1.35rem; font-weight:800; color:var(--royal); margin-bottom:.35rem; display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.bm .d-meta { display:flex; gap:1rem; font-size:.75rem; color:var(--text-secondary); flex-wrap:wrap; }
.bm .d-meta span { display:flex; align-items:center; gap:.3rem; }
.bm .d-meta i { color:var(--gold); font-size:.72rem; }
.bm .d-actions { display:flex; gap:.4rem; flex-wrap:wrap; }

.bm .btn-act { padding:.55rem 1rem; border-radius:var(--radius-sm); border:1.5px solid var(--border-soft); background:#fff; color:var(--text-primary); font-family:inherit; font-size:.78rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:.35rem; height:38px; transition:var(--transition); }
.bm .btn-act:hover { background:var(--bg-field); transform:translateY(-1px); }
.bm .btn-act.loc { background:linear-gradient(135deg,var(--sky),#38BDF8); color:#fff; border-color:transparent; box-shadow:0 3px 10px rgba(2,132,199,.3); }
.bm .btn-act.loc:hover { box-shadow:0 6px 16px rgba(2,132,199,.45); }
.bm .btn-act.edit { background:linear-gradient(135deg,var(--midnight),#2563EB); color:#fff; border-color:transparent; }
.bm .btn-act.del { background:#fff; color:var(--cherry); border-color:rgba(220,38,38,.3); }
.bm .btn-act.del:hover { background:#FEF2F2; }

.bm .d-body { padding:1.25rem 1.5rem; }
.bm .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:.55rem; margin-bottom:1.25rem; }
.bm .info { background:var(--bg-field); border-radius:var(--radius-md); padding:.85rem 1rem; display:flex; align-items:center; gap:.75rem; border:1.5px solid transparent; transition:var(--transition); }
.bm .info:hover { background:#fff; border-color:var(--border-soft); box-shadow:var(--shadow-sm); }
.bm .info-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.9rem; flex-shrink:0; }
.bm .info-icon.blue { background:rgba(27,77,122,.12); color:var(--midnight); }
.bm .info-icon.gold { background:rgba(201,162,39,.12); color:var(--gold); }
.bm .info-icon.green { background:rgba(5,150,105,.12); color:var(--emerald); }
.bm .info-icon.orange { background:rgba(217,119,6,.12); color:var(--terracotta); }
.bm .info-icon.purple { background:rgba(124,58,237,.12); color:var(--purple); }
.bm .info-icon.sky { background:rgba(2,132,199,.12); color:var(--sky); }
.bm .info-label { font-size:.65rem; color:var(--text-secondary); font-weight:500; margin-bottom:.1rem; }
.bm .info-value { font-size:.85rem; color:var(--text-primary); font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.bm .info-value.empty { color:var(--text-muted); font-style:italic; font-weight:400; }

.bm .features { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:.5rem; margin-bottom:1rem; }
.bm .feat { background:var(--bg-field); border-radius:var(--radius-sm); padding:.6rem .75rem; display:flex; align-items:center; gap:.5rem; font-size:.78rem; font-weight:500; }
.bm .feat.on { background:rgba(5,150,105,.08); color:var(--emerald); border:1.5px solid rgba(5,150,105,.2); }
.bm .feat.off { color:var(--text-muted); }
.bm .feat i { font-size:.85rem; }

.bm .loc-card { background:#fff; border:1.5px solid var(--border-soft); border-radius:var(--radius-md); overflow:hidden; margin-bottom:1rem; }
.bm .loc-head { padding:.75rem 1rem; background:linear-gradient(135deg,rgba(2,132,199,.04),rgba(201,162,39,.04)); border-bottom:1px solid var(--border-soft); display:flex; align-items:center; gap:.5rem; }
.bm .loc-head i { color:var(--sky); font-size:.9rem; }
.bm .loc-head h3 { font-size:.85rem; font-weight:700; color:var(--royal); }
.bm .loc-head .pill { margin-inline-start:auto; font-size:.65rem; font-weight:600; padding:.2rem .6rem; border-radius:999px; }
.bm .pill.ok { background:rgba(5,150,105,.12); color:var(--emerald); }
.bm .pill.miss { background:rgba(217,119,6,.12); color:var(--terracotta); }
.bm .loc-body { padding:1rem; }
.bm .loc-empty { text-align:center; padding:1.5rem; }
.bm .loc-empty i { font-size:2.5rem; color:var(--border-soft); margin-bottom:.5rem; display:block; }
.bm .loc-empty p { font-size:.82rem; color:var(--text-secondary); margin-bottom:.85rem; }
.bm .btn-loc { background:linear-gradient(135deg,var(--sky),#38BDF8); color:#fff; border:none; padding:.6rem 1.25rem; border-radius:var(--radius-sm); font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:.4rem; box-shadow:0 3px 10px rgba(2,132,199,.3); transition:var(--transition); }
.bm .btn-loc:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(2,132,199,.45); }
.bm .loc-prev { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
.bm .coord-mini { background:var(--bg-field); padding:.6rem .75rem; border-radius:var(--radius-sm); display:flex; align-items:center; gap:.5rem; }
.bm .coord-mini i { color:var(--sky); font-size:.8rem; }
.bm .coord-mini-label { font-size:.62rem; color:var(--text-secondary); }
.bm .coord-mini-val { font-size:.8rem; font-weight:600; font-family:'Courier New',monospace; }
.bm .notes { font-size:.82rem; color:var(--text-secondary); line-height:1.6; padding:.65rem .85rem; background:var(--bg-field); border-radius:var(--radius-sm); font-style:italic; }
.bm .notes.filled { font-style:normal; color:var(--text-primary); }

/* Modals */
.bm .modal-bg { position:fixed; inset:0; background:rgba(15,43,70,.55); backdrop-filter:blur(6px); z-index:9999; display:none; align-items:center; justify-content:center; padding:1rem; }
.bm .modal-bg.show { display:flex; }
.bm .modal { background:#fff; border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); width:100%; max-width:780px; max-height:92vh; overflow:hidden; display:flex; flex-direction:column; }
.bm .modal.sm { max-width:440px; }
.bm .modal.map-modal { max-width:1100px; }
.bm .m-head { padding:.9rem 1.15rem; background:linear-gradient(135deg,var(--royal),var(--midnight)); color:#fff; display:flex; align-items:center; gap:.7rem; position:relative; }
.bm .m-head::before { content:''; position:absolute; top:0; right:0; width:100%; height:2px; background:linear-gradient(90deg,var(--gold),var(--gold-light),var(--gold)); }
.bm .m-head-icon { width:38px; height:38px; background:linear-gradient(135deg,var(--gold),var(--gold-light)); border-radius:9px; display:flex; align-items:center; justify-content:center; color:var(--royal); font-size:1rem; flex-shrink:0; }
.bm .m-head-text h2 { font-size:1.05rem; font-weight:700; margin-bottom:.05rem; }
.bm .m-head-text p { font-size:.72rem; color:rgba(255,255,255,.7); }
.bm .m-close { margin-inline-start:auto; width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,.1); border:none; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.9rem; }
.bm .m-close:hover { background:rgba(220,38,38,.3); }
.bm .m-body { padding:1.25rem; overflow-y:auto; flex:1; }
.bm .m-footer { padding:.85rem 1.15rem; border-top:1px solid var(--border-soft); display:flex; gap:.5rem; justify-content:flex-end; background:var(--bg-field); flex-wrap:wrap; }

.bm .f-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:.7rem; }
.bm .f-group { display:flex; flex-direction:column; gap:.25rem; }
.bm .f-group.full { grid-column:1 / -1; }
.bm .f-label { font-size:.75rem; font-weight:600; color:var(--text-primary); display:flex; align-items:center; gap:.3rem; }
.bm .f-label .req { color:var(--cherry); }
.bm .f-ctrl { width:100%; padding:.55rem .75rem; font-family:inherit; font-size:.82rem; color:var(--text-primary); background:var(--bg-field); border:1.5px solid transparent; border-radius:var(--radius-sm); outline:none; height:38px; transition:var(--transition); }
.bm .f-ctrl:focus { border-color:var(--midnight); background:#fff; box-shadow:0 0 0 3px rgba(27,77,122,.1); }
.bm textarea.f-ctrl { height:auto; min-height:70px; resize:vertical; padding:.55rem .75rem; line-height:1.5; }

.bm .f-check { display:flex; align-items:center; gap:.5rem; padding:.55rem .75rem; background:var(--bg-field); border-radius:var(--radius-sm); cursor:pointer; transition:var(--transition); }
.bm .f-check:hover { background:#E8EEF5; }
.bm .f-check input { width:18px; height:18px; accent-color:var(--emerald); cursor:pointer; }
.bm .f-check span { font-size:.82rem; font-weight:500; }

.bm .btn { font-family:inherit; font-size:.82rem; font-weight:600; padding:.55rem 1.15rem; border-radius:var(--radius-sm); border:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:.4rem; height:38px; transition:var(--transition); }
.bm .btn-sec { background:#fff; color:var(--text-primary); border:1.5px solid var(--border-soft); }
.bm .btn-pri { background:linear-gradient(135deg,var(--royal),var(--midnight)); color:#fff; box-shadow:0 3px 10px rgba(15,43,70,.3); }
.bm .btn-pri:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(15,43,70,.4); }
.bm .btn-dan { background:linear-gradient(135deg,var(--cherry),#EF4444); color:#fff; box-shadow:0 3px 10px rgba(220,38,38,.3); }
.bm .btn-suc { background:linear-gradient(135deg,var(--emerald),#10B981); color:#fff; box-shadow:0 3px 10px rgba(5,150,105,.3); }
.bm .btn-suc:disabled { opacity:.5; cursor:not-allowed; }

/* Map */
.bm .map-instr { background:linear-gradient(135deg,rgba(2,132,199,.08),rgba(201,162,39,.06)); border:1.5px solid rgba(2,132,199,.2); border-radius:var(--radius-sm); padding:.75rem 1rem; margin-bottom:.85rem; display:flex; align-items:center; gap:.65rem; font-size:.8rem; }
.bm .map-instr i { color:var(--sky); font-size:1.1rem; }
.bm .map-box { height:480px; border-radius:var(--radius-md); overflow:hidden; border:2px solid var(--border-soft); position:relative; }
.bm #bm-map { width:100%; height:100%; background:#e5e7eb; }
.bm .coords { display:grid; grid-template-columns:1fr 1fr; gap:.6rem; margin-top:.85rem; }
.bm .coord { background:var(--bg-field); border-radius:var(--radius-sm); padding:.75rem 1rem; display:flex; align-items:center; gap:.6rem; border:1.5px solid transparent; transition:var(--transition); }
.bm .coord.set { background:rgba(5,150,105,.06); border-color:rgba(5,150,105,.2); }
.bm .coord i { width:32px; height:32px; border-radius:8px; background:rgba(2,132,199,.12); color:var(--sky); display:flex; align-items:center; justify-content:center; font-size:.85rem; }
.bm .coord.set i { background:rgba(5,150,105,.15); color:var(--emerald); }
.bm .coord-label { font-size:.65rem; color:var(--text-secondary); font-weight:500; margin-bottom:.1rem; }
.bm .coord-val { font-size:.85rem; font-weight:700; font-family:'Courier New',monospace; letter-spacing:.3px; }

.bm .toast { position:fixed; bottom:1.5rem; left:50%; transform:translateX(-50%) translateY(150%); background:var(--royal); color:#fff; padding:.75rem 1.35rem; border-radius:var(--radius-md); box-shadow:var(--shadow-lg); display:flex; align-items:center; gap:.6rem; font-size:.82rem; font-weight:500; z-index:99999; transition:transform .4s cubic-bezier(.34,1.56,.64,1); max-width:90vw; }
.bm .toast.show { transform:translateX(-50%) translateY(0); }
.bm .toast.success { border-right:4px solid var(--emerald); }
.bm .toast.error { border-right:4px solid var(--cherry); }

@media (max-width:1024px) { .bm .workspace { grid-template-columns:340px 1fr; } }
@media (max-width:768px) {
  .bm .workspace { grid-template-columns:1fr; }
  .bm .list-panel { max-height:400px; position:relative; border-left:none; border-bottom:1px solid var(--border-soft); }
  .bm .f-grid { grid-template-columns:1fr; }
  .bm .stats { grid-template-columns:repeat(2,1fr); }
  .bm .stat:nth-child(2) { border-left:none; }
  .bm .map-box { height:380px; }
  .bm .coords { grid-template-columns:1fr; }
  .bm .loc-prev { grid-template-columns:1fr; }
}
</style>

<div class="bm">

  {{-- Toolbar --}}
  <div class="toolbar">
    <div class="toolbar-title">
      <div class="toolbar-icon"><i class="fas fa-building"></i></div>
      <div class="toolbar-text">
        <h1>إدارة المباني</h1>
        <p>عرض وإدارة المباني مع تحديد المواقع على الخريطة</p>
      </div>
    </div>
    <div class="toolbar-actions">
      <button type="button" class="btn-add" onclick="bmOpenEdit()">
        <i class="fas fa-plus"></i> إضافة مبنى
      </button>
    </div>
  </div>

  {{-- Filters --}}
  <div class="filters">
    <div class="filters-row">
      <div class="filter-search">
        <i class="fas fa-search"></i>
        <input type="text" id="bm_search" placeholder="بحث بالاسم، العنوان، المدينة...">
      </div>
      <div class="filter-item sm">
        <label class="filter-label"><i class="fas fa-toggle-on"></i> الحالة</label>
        <select class="filter-control" id="bm_status">
          <option value="all">الكل</option>
          <option value="active">نشط</option>
          <option value="inactive">غير نشط</option>
        </select>
      </div>
      <div class="filter-item sm">
        <label class="filter-label"><i class="fas fa-city"></i> المدينة</label>
        <select class="filter-control" id="bm_city">
          <option value="all">الكل</option>
        </select>
      </div>
      <div class="filter-item sm">
        <label class="filter-label"><i class="fas fa-house"></i> النوع</label>
        <select class="filter-control" id="bm_type">
          <option value="all">الكل</option>
          <option value="residential">سكني</option>
          <option value="commercial">تجاري</option>
          <option value="industrial">صناعي</option>
          <option value="agricultural">زراعي</option>
          <option value="mixed">مختلط</option>
        </select>
      </div>
      <div class="filter-item sm">
        <label class="filter-label"><i class="fas fa-map-marker-alt"></i> الموقع</label>
        <select class="filter-control" id="bm_location">
          <option value="all">الكل</option>
          <option value="has">تم تحديد الموقع</option>
          <option value="missing">بدون موقع</option>
        </select>
      </div>
    </div>
    <div class="filters-row">
      <div class="filter-item">
        <label class="filter-label"><i class="fas fa-calendar-plus"></i> من تاريخ</label>
        <input type="date" class="filter-control" id="bm_dateFrom">
      </div>
      <div class="filter-item">
        <label class="filter-label"><i class="fas fa-calendar-check"></i> إلى تاريخ</label>
        <input type="date" class="filter-control" id="bm_dateTo">
      </div>
      <div class="filter-item sm">
        <label class="filter-label"><i class="fas fa-sort"></i> الترتيب</label>
        <select class="filter-control" id="bm_sort">
          <option value="newest">الأحدث</option>
          <option value="oldest">الأقدم</option>
          <option value="name_asc">الاسم (أ-ي)</option>
          <option value="name_desc">الاسم (ي-أ)</option>
        </select>
      </div>
      <div class="filter-actions">
        <button type="button" class="btn-filter clear" onclick="bmClearFilters()">
          <i class="fas fa-rotate-left"></i> مسح
        </button>
      </div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="stats">
    <div class="stat"><div class="stat-icon royal"><i class="fas fa-building"></i></div><div><div class="stat-value" id="bm_statTotal">0</div><div class="stat-label">إجمالي المباني</div></div></div>
    <div class="stat"><div class="stat-icon emerald"><i class="fas fa-check-circle"></i></div><div><div class="stat-value" id="bm_statActive">0</div><div class="stat-label">نشط</div></div></div>
    <div class="stat"><div class="stat-icon sky"><i class="fas fa-map-marker-alt"></i></div><div><div class="stat-value" id="bm_statLocated">0</div><div class="stat-label">تم تحديد موقعه</div></div></div>
    <div class="stat"><div class="stat-icon gold"><i class="fas fa-door-open"></i></div><div><div class="stat-value" id="bm_statUnits">0</div><div class="stat-label">إجمالي الوحدات</div></div></div>
  </div>

  {{-- Workspace --}}
  <div class="workspace">
    <aside class="list-panel">
      <div class="panel-head">
        <div class="panel-head-title">
          <i class="fas fa-list"></i> قائمة المباني
          <span class="count" id="bm_listCount">0</span>
        </div>
      </div>
      <div class="list-body" id="bm_list"></div>
    </aside>
    <main class="details" id="bm_details">
      <div class="empty">
        <i class="fas fa-building"></i>
        <h3>اختر مبنى لعرض تفاصيله</h3>
        <p>انقر على أي مبنى من القائمة لعرض بياناته وإدارة موقعه على الخريطة</p>
      </div>
    </main>
  </div>

  {{-- Edit Modal --}}
  <div class="modal-bg" id="bm_editModal">
    <div class="modal">
      <div class="m-head">
        <div class="m-head-icon"><i class="fas fa-building"></i></div>
        <div class="m-head-text">
          <h2 id="bm_editTitle">إضافة مبنى</h2>
          <p id="bm_editSubtitle">أدخل البيانات</p>
        </div>
        <button type="button" class="m-close" onclick="bmClose('bm_editModal')"><i class="fas fa-xmark"></i></button>
      </div>
      <div class="m-body">
        <form id="bm_form" onsubmit="return false">
          <div class="f-grid">
            <div class="f-group full">
              <label class="f-label">اسم المبنى <span class="req">*</span></label>
              <input type="text" class="f-ctrl" id="bm_f_name">
            </div>
            <div class="f-group">
              <label class="f-label">المالك <span class="req">*</span></label>
              <select class="f-ctrl" id="bm_f_owner">
                <option value="">— اختر —</option>
                @foreach($ownersList as $o)
                  <option value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">المؤسسة</label>
              <select class="f-ctrl" id="bm_f_org">
                <option value="">— اختر —</option>
                @foreach($organizationsList as $o)
                  <option value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">الفرع</label>
              <select class="f-ctrl" id="bm_f_branch">
                <option value="">— اختر —</option>
                @foreach($branchesList as $o)
                  <option value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">رقم المبنى الوطني</label>
              <input type="text" class="f-ctrl" id="bm_f_number" placeholder="1234567890">
            </div>
            <div class="f-group">
              <label class="f-label">النوع</label>
              <select class="f-ctrl" id="bm_f_type">
                <option value="residential">سكني</option>
                <option value="commercial">تجاري</option>
                <option value="industrial">صناعي</option>
                <option value="agricultural">زراعي</option>
                <option value="mixed">مختلط</option>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">الحالة</label>
              <select class="f-ctrl" id="bm_f_status">
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">المدينة</label>
              <input type="text" class="f-ctrl" id="bm_f_city" placeholder="الرياض">
            </div>
            <div class="f-group">
              <label class="f-label">الحي</label>
              <input type="text" class="f-ctrl" id="bm_f_district" placeholder="حي النخيل">
            </div>
            <div class="f-group">
              <label class="f-label">الشارع</label>
              <input type="text" class="f-ctrl" id="bm_f_street" placeholder="شارع الملك فهد">
            </div>
            <div class="f-group">
              <label class="f-label">الرمز البريدي</label>
              <input type="text" class="f-ctrl" id="bm_f_postal" placeholder="12345">
            </div>
            <div class="f-group">
              <label class="f-label">العنوان 1</label>
              <input type="text" class="f-ctrl" id="bm_f_addr1">
            </div>
            <div class="f-group">
              <label class="f-label">العنوان 2</label>
              <input type="text" class="f-ctrl" id="bm_f_addr2">
            </div>
            <div class="f-group">
              <label class="f-label">عدد الطوابق</label>
              <input type="number" class="f-ctrl" id="bm_f_floors" min="0">
            </div>
            <div class="f-group">
              <label class="f-label">عدد الوحدات</label>
              <input type="number" class="f-ctrl" id="bm_f_units" min="0">
            </div>
            <div class="f-group">
              <label class="f-label">سنة البناء</label>
              <input type="number" class="f-ctrl" id="bm_f_year" min="1900" max="2100">
            </div>
            <div class="f-group">
              <label class="f-label">مساحة الأرض (م²)</label>
              <input type="number" class="f-ctrl" id="bm_f_land" step="0.01">
            </div>
            <div class="f-group">
              <label class="f-label">مساحة البناء (م²)</label>
              <input type="number" class="f-ctrl" id="bm_f_area" step="0.01">
            </div>
            <div class="f-group full">
              <label class="f-label"><i class="fas fa-list-check" style="color:var(--gold);"></i> الخدمات والمرافق</label>
              <div class="f-grid" style="grid-template-columns:repeat(4,1fr);">
                <label class="f-check"><input type="checkbox" id="bm_f_elevator"><span>مصعد</span></label>
                <label class="f-check"><input type="checkbox" id="bm_f_parking"><span>مواقف</span></label>
                <label class="f-check"><input type="checkbox" id="bm_f_security"><span>أمن</span></label>
                <label class="f-check"><input type="checkbox" id="bm_f_maint"><span>صيانة</span></label>
              </div>
            </div>
            <div class="f-group full">
              <label class="f-label">ملاحظات</label>
              <textarea class="f-ctrl" id="bm_f_notes" rows="2"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="m-footer">
        <button type="button" class="btn btn-sec" onclick="bmClose('bm_editModal')">إلغاء</button>
        <button type="button" class="btn btn-pri" onclick="bmSave()">
          <i class="fas fa-floppy-disk"></i> <span id="bm_saveText">حفظ</span>
        </button>
      </div>
    </div>
  </div>

  {{-- Map Modal --}}
  <div class="modal-bg" id="bm_mapModal">
    <div class="modal map-modal">
      <div class="m-head">
        <div class="m-head-icon"><i class="fas fa-map-location-dot"></i></div>
        <div class="m-head-text">
          <h2>تحديد موقع المبنى</h2>
          <p id="bm_mapSubtitle">انقر على الخريطة لاختيار الموقع</p>
        </div>
        <button type="button" class="m-close" onclick="bmClose('bm_mapModal')"><i class="fas fa-xmark"></i></button>
      </div>
      <div class="m-body">
        <div class="map-instr">
          <i class="fas fa-hand-pointer"></i>
          <span>انقر على الموقع على الخريطة، أو اسحب الدبوس لتعديله</span>
        </div>
        <div class="map-box"><div id="bm-map"></div></div>
        <div class="coords">
          <div class="coord" id="bm_coordLat">
            <i class="fas fa-arrows-up-down"></i>
            <div>
              <div class="coord-label">خط العرض</div>
              <div class="coord-val" id="bm_latVal">—</div>
            </div>
          </div>
          <div class="coord" id="bm_coordLng">
            <i class="fas fa-arrows-left-right"></i>
            <div>
              <div class="coord-label">خط الطول</div>
              <div class="coord-val" id="bm_lngVal">—</div>
            </div>
          </div>
        </div>
      </div>
      <div class="m-footer">
        <button type="button" class="btn btn-sec" onclick="bmClose('bm_mapModal')">إلغاء</button>
        <button type="button" class="btn btn-dan" id="bm_clearLocBtn" onclick="bmClearLoc()" style="display:none;"><i class="fas fa-trash"></i> حذف</button>
        <button type="button" class="btn btn-suc" id="bm_saveLocBtn" onclick="bmSaveLoc()" disabled><i class="fas fa-check"></i> حفظ الموقع</button>
      </div>
    </div>
  </div>

  {{-- Delete Modal --}}
  <div class="modal-bg" id="bm_delModal">
    <div class="modal sm">
      <div class="m-head">
        <div class="m-head-icon" style="background:linear-gradient(135deg,var(--cherry),#EF4444);color:#fff;"><i class="fas fa-trash"></i></div>
        <div class="m-head-text"><h2>تأكيد الحذف</h2><p>لا يمكن التراجع</p></div>
        <button type="button" class="m-close" onclick="bmClose('bm_delModal')"><i class="fas fa-xmark"></i></button>
      </div>
      <div class="m-body">
        <div style="text-align:center;padding:.5rem 0;">
          <div style="width:64px;height:64px;background:rgba(220,38,38,.1);color:var(--cherry);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto .85rem;">
            <i class="fas fa-triangle-exclamation"></i>
          </div>
          <h3 style="font-size:1rem;font-weight:700;margin-bottom:.4rem;">هل أنت متأكد؟</h3>
          <p style="font-size:.8rem;color:var(--text-secondary);">
            سيتم حذف <strong id="bm_delName" style="color:var(--cherry);">—</strong>
          </p>
        </div>
      </div>
      <div class="m-footer">
        <button type="button" class="btn btn-sec" onclick="bmClose('bm_delModal')">إلغاء</button>
        <button type="button" class="btn btn-dan" onclick="bmConfirmDelete()"><i class="fas fa-trash"></i> نعم، احذف</button>
      </div>
    </div>
  </div>

  <div class="toast" id="bm_toast"><i class="fas fa-circle-check"></i><span id="bm_toastMsg"></span></div>
</div>
</div>{{-- /wire:ignore --}}

{{-- ═══════════════════════════════════════════════════════════════
     Livewire 4 @script
═══════════════════════════════════════════════════════════════ --}}
@script
<script>
(function() {
    'use strict';

    if (window.__bmReady) return;
    window.__bmReady = true;

    console.log('[BM] init');

    // البيانات
    const DATA = {
        buildings: @json($buildings ?? []),
        stats:     @json($stats ?? []),
    };

    let buildings = DATA.buildings;
    let currentBuilding = null;
    let editingId = null;
    let deletingId = null;
    let map = null, marker = null;
    let selectedLat = null, selectedLng = null;

    let filters = {
        search: '', status: 'all', city: 'all', type: 'all',
        location: 'all', dateFrom: '', dateTo: '', sort: 'newest'
    };

    const wire = $wire;

    // ══════ Call Livewire ══════
    function call(method, ...args) {
        console.log('[BM] >>>', method, args);
        if (!wire) { toast('Livewire غير جاهز', 'error'); return; }
        try {
            if (typeof wire.call === 'function') { wire.call(method, ...args); return; }
            if (typeof wire.$call === 'function') { wire.$call(method, ...args); return; }
            if (typeof wire[method] === 'function') { wire[method](...args); return; }
        } catch (e) {
            console.error('[BM]', e);
            toast('خطأ: ' + e.message, 'error');
        }
    }

    // ══════ Listen ══════
    if (wire && typeof wire.on === 'function') {
        wire.on('bm-data-updated', (payload) => {
            const data = payload?.detail ?? (Array.isArray(payload) ? payload[0] : payload);
            if (!data) return;
            if (data.buildings) buildings = data.buildings;

            document.querySelectorAll('.bm .modal-bg.show').forEach(m => m.classList.remove('show'));
            document.body.style.overflow = '';

            updateStats();
            renderList();

            if (data.keepId) {
                const found = buildings.find(b => b.id === data.keepId);
                if (found) { currentBuilding = found; renderDetails(); }
            } else if (currentBuilding) {
                const found = buildings.find(b => b.id === currentBuilding.id);
                if (found) { currentBuilding = found; renderDetails(); }
                else { currentBuilding = null; renderDetails(); }
            }

            if (data.message) toast(data.message, 'success');
        });

        wire.on('bm-error', (payload) => {
            const data = payload?.detail ?? (Array.isArray(payload) ? payload[0] : payload);
            toast(data?.message || 'حدث خطأ', 'error');
        });
    }

    // ══════ Stats ══════
    function updateStats() {
        document.getElementById('bm_statTotal').textContent = buildings.length;
        document.getElementById('bm_statActive').textContent = buildings.filter(b => b.status === 'active').length;
        document.getElementById('bm_statLocated').textContent = buildings.filter(b => b.latitude && b.longitude).length;
        document.getElementById('bm_statUnits').textContent = buildings.reduce((s, b) => s + (parseInt(b.number_of_units) || 0), 0);
    }

    // ══════ Filters ══════
    function applyFilters() {
        let f = [...buildings];
        if (filters.search) {
            const t = filters.search.toLowerCase();
            f = f.filter(b =>
                (b.building_name || '').toLowerCase().includes(t) ||
                (b.address_line1 || '').toLowerCase().includes(t) ||
                (b.city || '').toLowerCase().includes(t) ||
                (b.district || '').toLowerCase().includes(t)
            );
        }
        if (filters.status !== 'all') f = f.filter(b => b.status === filters.status);
        if (filters.city !== 'all') f = f.filter(b => b.city === filters.city);
        if (filters.type !== 'all') f = f.filter(b => b.property_type === filters.type);
        if (filters.location === 'has') f = f.filter(b => b.latitude && b.longitude);
        else if (filters.location === 'missing') f = f.filter(b => !b.latitude || !b.longitude);
        if (filters.dateFrom) f = f.filter(b => (b.created_at || '') >= filters.dateFrom);
        if (filters.dateTo) f = f.filter(b => (b.created_at || '') <= filters.dateTo);
        switch (filters.sort) {
            case 'newest': f.sort((a,b) => b.id - a.id); break;
            case 'oldest': f.sort((a,b) => a.id - b.id); break;
            case 'name_asc': f.sort((a,b) => (a.building_name||'').localeCompare(b.building_name||'','ar')); break;
            case 'name_desc': f.sort((a,b) => (b.building_name||'').localeCompare(a.building_name||'','ar')); break;
        }
        return f;
    }

    // ══════ Render List ══════
    function renderList() {
        const list = document.getElementById('bm_list');
        const filtered = applyFilters();
        document.getElementById('bm_listCount').textContent = filtered.length;

        if (!filtered.length) {
            list.innerHTML = `<div style="text-align:center;padding:3rem 1rem;color:var(--text-muted);"><i class="fas fa-building" style="font-size:2.5rem;color:var(--border-soft);display:block;margin-bottom:.75rem;"></i><p style="font-size:.85rem;">لا توجد نتائج</p></div>`;
            return;
        }

        const typeLabels = {
            residential: 'سكني', commercial: 'تجاري', industrial: 'صناعي',
            agricultural: 'زراعي', mixed: 'مختلط'
        };

        list.innerHTML = filtered.map(b => {
            const hasLoc = b.latitude && b.longitude;
            const isActive = b.status === 'active';
            return `<div class="b-card ${currentBuilding?.id === b.id ? 'active' : ''} ${!isActive ? 'inactive' : ''}" onclick="bmSelect(${b.id})">
                <div class="b-head">
                    <div class="b-icon"><i class="fas fa-building"></i></div>
                    <div class="b-info">
                        <div class="b-name">${b.building_name || '—'}${b.building_number ? `<span style="font-size:.62rem;color:var(--text-muted);">(${b.building_number})</span>` : ''}</div>
                        <div class="b-addr"><i class="fas fa-location-dot"></i> ${b.city || ''}${b.district ? ' - ' + b.district : ''}</div>
                    </div>
                </div>
                <div class="b-badges">
                    <span class="badge ${isActive ? 'on' : 'off'}">${isActive ? 'نشط' : 'غير نشط'}</span>
                    ${b.number_of_units ? `<span class="badge units"><i class="fas fa-door-open"></i>${b.number_of_units}</span>` : ''}
                    ${b.number_of_floors ? `<span class="badge floors"><i class="fas fa-layer-group"></i>${b.number_of_floors}</span>` : ''}
                    <span class="badge ${hasLoc ? 'loc' : 'noloc'}">${hasLoc ? '📍 محدد' : '⚠ بدون'}</span>
                </div>
            </div>`;
        }).join('');
    }

    // ══════ Render Details ══════
    function renderDetails() {
        const panel = document.getElementById('bm_details');
        if (!currentBuilding) {
            panel.innerHTML = `<div class="empty"><i class="fas fa-building"></i><h3>اختر مبنى لعرض تفاصيله</h3><p>انقر على أي مبنى من القائمة لعرض بياناته وإدارة موقعه</p></div>`;
            return;
        }

        const b = currentBuilding;
        const hasLoc = b.latitude && b.longitude;
        const isActive = b.status === 'active';
        const typeLabels = { residential:'سكني', commercial:'تجاري', industrial:'صناعي', agricultural:'زراعي', mixed:'مختلط' };

        panel.innerHTML = `
            <div class="d-head">
                <div class="d-avatar ${!isActive ? 'inactive' : ''}"><i class="fas fa-building"></i></div>
                <div class="d-info">
                    <div class="d-name">
                        ${b.building_name || '—'}
                        <span class="badge ${isActive ? 'on' : 'off'}" style="font-size:.7rem;">${isActive ? 'نشط' : 'غير نشط'}</span>
                    </div>
                    <div class="d-meta">
                        ${b.building_number ? `<span><i class="fas fa-hashtag"></i> ${b.building_number}</span>` : ''}
                        <span><i class="fas fa-city"></i> ${b.city || '—'}</span>
                        <span><i class="fas fa-user"></i> ${b.owner_name || 'بدون مالك'}</span>
                        <span><i class="fas fa-calendar"></i> ${b.created_at || '—'}</span>
                    </div>
                </div>
                <div class="d-actions">
                    <button type="button" class="btn-act loc" onclick="bmOpenMap()">
                        <i class="fas fa-map-location-dot"></i> ${hasLoc ? 'تعديل الموقع' : 'إضافة موقع'}
                    </button>
                    <button type="button" class="btn-act edit" onclick="bmOpenEdit(${b.id})">
                        <i class="fas fa-pen"></i> تعديل
                    </button>
                    <button type="button" class="btn-act del" onclick="bmOpenDelete(${b.id})">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </div>
            </div>
            <div class="d-body">
                <div class="info-grid">
                    <div class="info"><div class="info-icon blue"><i class="fas fa-house"></i></div><div><div class="info-label">نوع العقار</div><div class="info-value">${typeLabels[b.property_type] || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon orange"><i class="fas fa-layer-group"></i></div><div><div class="info-label">الطوابق</div><div class="info-value ${!b.number_of_floors ? 'empty' : ''}">${b.number_of_floors || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon sky"><i class="fas fa-door-open"></i></div><div><div class="info-label">الوحدات</div><div class="info-value">${b.number_of_units || 0}</div></div></div>
                    <div class="info"><div class="info-icon gold"><i class="fas fa-calendar"></i></div><div><div class="info-label">سنة البناء</div><div class="info-value ${!b.year_built ? 'empty' : ''}">${b.year_built || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon purple"><i class="fas fa-ruler-combined"></i></div><div><div class="info-label">مساحة الأرض</div><div class="info-value ${!b.land_area ? 'empty' : ''}">${b.land_area ? b.land_area + ' م²' : 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon green"><i class="fas fa-vector-square"></i></div><div><div class="info-label">مساحة البناء</div><div class="info-value ${!b.building_area ? 'empty' : ''}">${b.building_area ? b.building_area + ' م²' : 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon sky"><i class="fas fa-city"></i></div><div><div class="info-label">المدينة</div><div class="info-value ${!b.city ? 'empty' : ''}">${b.city || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon purple"><i class="fas fa-map"></i></div><div><div class="info-label">الحي</div><div class="info-value ${!b.district ? 'empty' : ''}">${b.district || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon gold"><i class="fas fa-road"></i></div><div><div class="info-label">الشارع</div><div class="info-value ${!b.street ? 'empty' : ''}">${b.street || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon blue"><i class="fas fa-user"></i></div><div><div class="info-label">المالك</div><div class="info-value ${!b.owner_name ? 'empty' : ''}">${b.owner_name || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon green"><i class="fas fa-building"></i></div><div><div class="info-label">المؤسسة</div><div class="info-value ${!b.organization_name ? 'empty' : ''}">${b.organization_name || 'غير محدد'}</div></div></div>
                    <div class="info"><div class="info-icon orange"><i class="fas fa-code-branch"></i></div><div><div class="info-label">الفرع</div><div class="info-value ${!b.branch_name ? 'empty' : ''}">${b.branch_name || 'غير محدد'}</div></div></div>
                </div>

                <div style="font-size:.78rem;font-weight:600;margin-bottom:.5rem;">
                    <i class="fas fa-list-check" style="color:var(--gold);"></i> الخدمات والمرافق
                </div>
                <div class="features">
                    <div class="feat ${b.has_elevator ? 'on' : 'off'}"><i class="fas fa-elevator"></i> مصعد</div>
                    <div class="feat ${b.has_parking ? 'on' : 'off'}"><i class="fas fa-square-parking"></i> مواقف</div>
                    <div class="feat ${b.has_security ? 'on' : 'off'}"><i class="fas fa-shield-halved"></i> أمن</div>
                    <div class="feat ${b.has_maintenance ? 'on' : 'off'}"><i class="fas fa-screwdriver-wrench"></i> صيانة</div>
                </div>

                <div class="loc-card">
                    <div class="loc-head">
                        <i class="fas fa-map-location-dot"></i>
                        <h3>الموقع الجغرافي</h3>
                        <span class="pill ${hasLoc ? 'ok' : 'miss'}">${hasLoc ? '✓ تم التحديد' : '⚠ لم يحدد'}</span>
                    </div>
                    <div class="loc-body">
                        ${hasLoc ? `
                            <div class="loc-prev">
                                <div class="coord-mini"><i class="fas fa-arrows-up-down"></i><div><div class="coord-mini-label">خط العرض</div><div class="coord-mini-val">${parseFloat(b.latitude).toFixed(6)}</div></div></div>
                                <div class="coord-mini"><i class="fas fa-arrows-left-right"></i><div><div class="coord-mini-label">خط الطول</div><div class="coord-mini-val">${parseFloat(b.longitude).toFixed(6)}</div></div></div>
                            </div>
                        ` : `
                            <div class="loc-empty">
                                <i class="fas fa-map-marked-alt"></i>
                                <p>لم يتم تحديد موقع هذا المبنى</p>
                                <button type="button" class="btn-loc" onclick="bmOpenMap()"><i class="fas fa-plus"></i> إضافة موقع على الخريطة</button>
                            </div>
                        `}
                    </div>
                </div>

                <div style="margin-top:1rem;">
                    <div style="font-size:.78rem;font-weight:600;margin-bottom:.4rem;"><i class="fas fa-note-sticky" style="color:var(--gold);"></i> ملاحظات</div>
                    <div class="notes ${b.notes ? 'filled' : ''}">${b.notes || 'لا توجد ملاحظات.'}</div>
                </div>
            </div>
        `;
    }

    // ══════ Edit Modal ══════
    function openEdit(id = null) {
        editingId = id;
        document.getElementById('bm_form').reset();

        const title = document.getElementById('bm_editTitle');
        const sub = document.getElementById('bm_editSubtitle');
        const saveText = document.getElementById('bm_saveText');

        if (id) {
            const b = buildings.find(x => x.id === id);
            if (!b) return;
            title.textContent = 'تعديل بيانات المبنى';
            sub.textContent = 'حدّث البيانات';
            saveText.textContent = 'حفظ التعديلات';

            setVal('bm_f_name', b.building_name);
            setVal('bm_f_owner', b.owner_id);
            setVal('bm_f_org', b.organization_id);
            setVal('bm_f_branch', b.branch_id);
            setVal('bm_f_number', b.building_number);
            setVal('bm_f_type', b.property_type);
            setVal('bm_f_status', b.status);
            setVal('bm_f_city', b.city);
            setVal('bm_f_district', b.district);
            setVal('bm_f_street', b.street);
            setVal('bm_f_postal', b.postal_code);
            setVal('bm_f_addr1', b.address_line1);
            setVal('bm_f_addr2', b.address_line2);
            setVal('bm_f_floors', b.number_of_floors);
            setVal('bm_f_units', b.number_of_units);
            setVal('bm_f_year', b.year_built);
            setVal('bm_f_land', b.land_area);
            setVal('bm_f_area', b.building_area);
            document.getElementById('bm_f_elevator').checked = b.has_elevator;
            document.getElementById('bm_f_parking').checked = b.has_parking;
            document.getElementById('bm_f_security').checked = b.has_security;
            document.getElementById('bm_f_maint').checked = b.has_maintenance;
            setVal('bm_f_notes', b.notes);
        } else {
            title.textContent = 'إضافة مبنى جديد';
            sub.textContent = 'أدخل البيانات';
            saveText.textContent = 'حفظ';
            setVal('bm_f_status', 'active');
            setVal('bm_f_type', 'residential');
        }

        openModal('bm_editModal');
    }

    function setVal(id, v) { const el = document.getElementById(id); if (el) el.value = v ?? ''; }
    function getVal(id) { const el = document.getElementById(id); return el ? el.value.trim() : ''; }
    function getChk(id) { const el = document.getElementById(id); return el ? el.checked : false; }

    function save() {
        const name = getVal('bm_f_name');
        if (!name) { toast('يرجى إدخال اسم المبنى', 'error'); return; }
        if (!getVal('bm_f_owner')) { toast('يرجى اختيار المالك', 'error'); return; }

        const data = {
            building_name:    name,
            owner_id:         getVal('bm_f_owner'),
            organization_id:  getVal('bm_f_org'),
            branch_id:        getVal('bm_f_branch'),
            building_number:  getVal('bm_f_number'),
            property_type:    getVal('bm_f_type') || 'residential',
            status:           getVal('bm_f_status') || 'active',
            city:             getVal('bm_f_city'),
            district:         getVal('bm_f_district'),
            street:           getVal('bm_f_street'),
            postal_code:      getVal('bm_f_postal'),
            address_line1:    getVal('bm_f_addr1'),
            address_line2:    getVal('bm_f_addr2'),
            number_of_floors: getVal('bm_f_floors'),
            number_of_units:  getVal('bm_f_units'),
            year_built:       getVal('bm_f_year'),
            land_area:        getVal('bm_f_land'),
            building_area:    getVal('bm_f_area'),
            has_elevator:     getChk('bm_f_elevator'),
            has_parking:      getChk('bm_f_parking'),
            has_security:     getChk('bm_f_security'),
            has_maintenance:  getChk('bm_f_maint'),
            notes:            getVal('bm_f_notes'),
        };

        call('saveBuilding', data, editingId);
    }

    // ══════ Map ══════
    function openMap() {
        if (!currentBuilding) return;
        selectedLat = currentBuilding.latitude ? parseFloat(currentBuilding.latitude) : null;
        selectedLng = currentBuilding.longitude ? parseFloat(currentBuilding.longitude) : null;
        document.getElementById('bm_mapSubtitle').textContent = `تحديد موقع: ${currentBuilding.building_name}`;
        openModal('bm_mapModal');

        // انتظر حتى تصبح النافذة مرئية
        setTimeout(() => {
            initMap();
            setTimeout(() => { if (map) map.invalidateSize(true); }, 200);
            setTimeout(() => { if (map) map.invalidateSize(true); }, 500);
        }, 250);
    }

    function initMap() {
        if (map) { try { map.remove(); } catch(e){} map = null; marker = null; }

        const c = document.getElementById('bm-map');
        if (!c) return;
        const rect = c.getBoundingClientRect();
        console.log('[BM] Map container:', rect.width, 'x', rect.height);

        const lat = selectedLat || 24.7136;
        const lng = selectedLng || 46.6753;
        const zoom = selectedLat ? 15 : 6;

        map = L.map('bm-map', { center: [lat, lng], zoom, zoomControl: true });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19,
        }).addTo(map);

        map.invalidateSize(true);
        setTimeout(() => map.invalidateSize(true), 100);
        setTimeout(() => map.invalidateSize(true), 400);

        if (selectedLat && selectedLng) {
            marker = L.marker([selectedLat, selectedLng], {
                draggable: true,
                icon: customIcon(),
            }).addTo(map);
            marker.on('dragend', () => {
                const p = marker.getLatLng();
                selectedLat = p.lat;
                selectedLng = p.lng;
                updateCoords(p.lat, p.lng);
            });
            updateCoords(selectedLat, selectedLng);
            document.getElementById('bm_saveLocBtn').disabled = false;
            document.getElementById('bm_clearLocBtn').style.display = 'inline-flex';
        }

        map.on('click', (e) => {
            const { lat, lng } = e.latlng;
            if (marker) marker.setLatLng([lat, lng]);
            else {
                marker = L.marker([lat, lng], { draggable: true, icon: customIcon() }).addTo(map);
                marker.on('dragend', () => {
                    const p = marker.getLatLng();
                    selectedLat = p.lat;
                    selectedLng = p.lng;
                    updateCoords(p.lat, p.lng);
                });
            }
            selectedLat = lat;
            selectedLng = lng;
            updateCoords(lat, lng);
            document.getElementById('bm_saveLocBtn').disabled = false;
            document.getElementById('bm_clearLocBtn').style.display = 'inline-flex';
        });
    }

    function customIcon() {
        return L.divIcon({
            className: 'bm-pin',
            html: `<div style="width:36px;height:36px;background:linear-gradient(135deg,#0F2B46,#1B4D7A);border:3px solid #E8CB5C;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 4px 14px rgba(15,43,70,0.4);display:flex;align-items:center;justify-content:center;"><i class="fas fa-building" style="transform:rotate(45deg);color:#E8CB5C;font-size:14px;"></i></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 36],
        });
    }

    function updateCoords(lat, lng) {
        document.getElementById('bm_latVal').textContent = parseFloat(lat).toFixed(6);
        document.getElementById('bm_lngVal').textContent = parseFloat(lng).toFixed(6);
        document.getElementById('bm_coordLat').classList.add('set');
        document.getElementById('bm_coordLng').classList.add('set');
    }

    function clearLoc() {
        if (marker && map) { map.removeLayer(marker); marker = null; }
        selectedLat = null;
        selectedLng = null;
        document.getElementById('bm_latVal').textContent = '—';
        document.getElementById('bm_lngVal').textContent = '—';
        document.getElementById('bm_coordLat').classList.remove('set');
        document.getElementById('bm_coordLng').classList.remove('set');
        document.getElementById('bm_saveLocBtn').disabled = true;
        document.getElementById('bm_clearLocBtn').style.display = 'none';
    }

    function saveLoc() {
        if (!selectedLat || !selectedLng || !currentBuilding) return;
        call('saveLocation', currentBuilding.id, selectedLat, selectedLng);
        closeModal('bm_mapModal');
    }

    // ══════ Delete ══════
    function openDelete(id = null) {
        deletingId = id || currentBuilding?.id;
        if (!deletingId) return;
        const b = buildings.find(x => x.id === deletingId);
        if (!b) return;
        document.getElementById('bm_delName').textContent = b.building_name;
        openModal('bm_delModal');
    }

    function confirmDelete() {
        if (!deletingId) return;
        call('deleteBuilding', deletingId);
        closeModal('bm_delModal');
    }

    // ══════ Modals ══════
    function openModal(id) {
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
        if (id === 'bm_mapModal' && map) {
            const m = map; map = null; marker = null;
            setTimeout(() => { try { m.remove(); } catch(e){} }, 250);
        }
    }

    document.querySelectorAll('.bm .modal-bg').forEach(o => {
        o.addEventListener('click', e => { if (e.target === o) closeModal(o.id); });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.bm .modal-bg.show').forEach(m => closeModal(m.id));
        }
    });

    // ══════ Toast ══════
    let toastTimer;
    function toast(msg, type = 'success') {
        clearTimeout(toastTimer);
        const t = document.getElementById('bm_toast');
        const m = document.getElementById('bm_toastMsg');
        t.className = 'toast ' + type;
        m.textContent = msg;
        t.querySelector('i').className = type === 'success' ? 'fas fa-circle-check' : 'fas fa-circle-exclamation';
        t.classList.add('show');
        toastTimer = setTimeout(() => t.classList.remove('show'), 3500);
    }

    // ══════ Filters Bind ══════
    function bind(id, key, immediate) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener(immediate ? 'input' : 'change', () => {
            filters[key] = el.value;
            renderList();
        });
    }

    bind('bm_search', 'search', true);
    bind('bm_status', 'status');
    bind('bm_city', 'city');
    bind('bm_type', 'type');
    bind('bm_location', 'location');
    bind('bm_dateFrom', 'dateFrom');
    bind('bm_dateTo', 'dateTo');
    bind('bm_sort', 'sort');

    function clearFilters() {
        filters = { search:'', status:'all', city:'all', type:'all', location:'all', dateFrom:'', dateTo:'', sort:'newest' };
        ['bm_search','bm_status','bm_city','bm_type','bm_location','bm_dateFrom','bm_dateTo','bm_sort'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (id === 'bm_search' || id === 'bm_dateFrom' || id === 'bm_dateTo') el.value = '';
            else if (id === 'bm_sort') el.value = 'newest';
            else el.value = 'all';
        });
        renderList();
        toast('تم مسح الفلاتر', 'success');
    }

    // ══════ Populate City Filter ══════
    function populateCityFilter() {
        const sel = document.getElementById('bm_city');
        if (!sel) return;
        const cities = [...new Set(buildings.map(b => b.city).filter(Boolean))];
        sel.innerHTML = '<option value="all">الكل</option>' + cities.map(c => `<option value="${c}">${c}</option>`).join('');
    }

    // ══════ Expose Globals ══════
    window.bmSelect = (id) => { currentBuilding = buildings.find(b => b.id === id); renderList(); renderDetails(); };
    window.bmOpenEdit = openEdit;
    window.bmSave = save;
    window.bmOpenMap = openMap;
    window.bmSaveLoc = saveLoc;
    window.bmClearLoc = clearLoc;
    window.bmOpenDelete = openDelete;
    window.bmConfirmDelete = confirmDelete;
    window.bmClose = closeModal;
    window.bmClearFilters = clearFilters;

    // ══════ Init ══════
    updateStats();
    populateCityFilter();
    renderList();
    if (buildings.length > 0) {
        currentBuilding = buildings[0];
        renderDetails();
        renderList();
    }

    console.log('[BM] ✅ Ready —', buildings.length, 'buildings');
})();
</script>
@endscript

</div>{{-- /bm-root --}}