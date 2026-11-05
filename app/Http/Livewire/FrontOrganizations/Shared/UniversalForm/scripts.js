/* ============================================================
 * UniversalForm Scripts — متوافق مع تصميم إدارة المالكين
 * يتصل بـ Livewire وقاعدة البيانات عبر دوال UniversalForm
 * ============================================================ */
(function () {
    'use strict';

    // ============================================================
    // البيانات الأولية (من Blade عبر window.UF_DATA)
    // ============================================================
    const UF = window.UF_DATA || {};

    // بيانات تجريبية احتياطية للعمل بدون Livewire
    const DEMO_OWNERS = [
        {
            id: 1, full_name: 'أحمد محمد العتيبي', phone: '0551234567', phone2: '0559876543',
            email: 'ahmed@example.com', nationality: 'سعودي', id_type: 'national_id',
            id_number: '1234567890', address: 'الرياض، حي النخيل',
            agency: 'مؤسسة النخبة', organization_id: 1,
            bank_name: 'البنك الأهلي', bank_iban: 'SA4420000001234567891234',
            payment: 'bank_transfer', status: 'active',
            notes: 'مالك مميز.', created_at: '2024-01-15',
            agents: [
                { id: 1, name: 'خالد سعد', phone: '0551111111', email: 'khaled@example.com', type: 'general', notes: '', status: 'active' },
                { id: 2, name: 'سلطان فهد', phone: '0553333333', email: 'sultan@example.com', type: 'rental', notes: '', status: 'active' }
            ]
        },
        {
            id: 2, full_name: 'سارة عبدالله القحطاني', phone: '0552222222', phone2: '',
            email: 'sara@example.com', nationality: 'سعودي', id_type: 'national_id',
            id_number: '2345678901', address: 'جدة، حي الروضة',
            agency: 'مؤسسة الأمانة', organization_id: 2,
            bank_name: 'بنك الرياض', bank_iban: 'SA1120000002345678901234',
            payment: 'cheque', status: 'active', notes: '', created_at: '2024-02-20', agents: []
        },
        {
            id: 3, full_name: 'محمد فهد الدوسري', phone: '0553333333', phone2: '0554444444',
            email: '', nationality: 'سعودي', id_type: 'iqama',
            id_number: '3456789012', address: 'الدمام، حي الشاطئ',
            agency: 'مؤسسة الرائد', organization_id: 3,
            bank_name: 'البنك السعودي الفرنسي', bank_iban: 'SA3330000003456789012345',
            payment: 'cash', status: 'blocked', notes: 'حساب موقوف.',
            created_at: '2024-03-10',
            agents: [{ id: 3, name: 'عبدالرحمن ناصر', phone: '0555555555', email: '', type: 'rental', notes: '', status: 'active' }]
        }
    ];

    const DEMO_ORGS = [
        { id: 1, name: 'مؤسسة النخبة' },
        { id: 2, name: 'مؤسسة الأمانة' },
        { id: 3, name: 'مؤسسة الرائد' }
    ];

    const DEMO_ID_LABELS = {
        national_id: 'هوية وطنية', iqama: 'إقامة', passport: 'جواز سفر', commercial_reg: 'سجل تجاري'
    };
    const DEMO_PAY_LABELS = {
        bank_transfer: 'تحويل بنكي', cheque: 'شيك', cash: 'نقداً'
    };
    const DEMO_AGENT_LABELS = {
        general: 'وكالة عامة', rental: 'وكالة إيجار', sale: 'وكالة بيع', management: 'وكالة إدارة'
    };

    let owners         = Array.isArray(UF.owners) ? UF.owners : DEMO_OWNERS;
    const organizations = Array.isArray(UF.organizations) && UF.organizations.length
                        ? UF.organizations : DEMO_ORGS;
    const idTypeLabels  = UF.idTypeLabels    || DEMO_ID_LABELS;
    const paymentLabels = UF.paymentLabels   || DEMO_PAY_LABELS;
    const agentTypeLabels = UF.agentTypeLabels || DEMO_AGENT_LABELS;

    // ============================================================
    // الحالة
    // ============================================================
    let currentOwner     = null;
    let editingOwnerId   = null;
    let editingAgentId   = null;
    let deletingOwnerId  = null;
    let deletingAgentId  = null;
    let viewingAgentId   = null;
    let blockingOwnerId  = null;
    let blockingAgentId  = null;
    let blockAction      = 'block';
    let blockAgentAction = 'block';
    let agentSearchTerm  = '';

    let filters = {
        search: '', status: 'all', agency: 'all', id_type: 'all', nationality: 'all',
        dateFrom: '', dateTo: '', payment: 'all', agents: 'all', sort: 'newest'
    };

    // ============================================================
    // Helpers
    // ============================================================
    const $   = id => document.getElementById(id);
    const esc = s => (s == null ? '' : String(s).replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c])));
    const firstChar = s => esc(String(s || '?').trim().charAt(0) || '?');
    const agentName = a => a ? (a.name || a.full_name || '') : '';
    const agentRole = a => a ? (a.type || a.role || 'administrative') : 'administrative';

    const labelFor = (map, key) => (key && map[key]) ? map[key] : (key || '');

    // ============================================================
    // Livewire Bridge
    // ============================================================
    function livewireAvailable() {
        return !!(window.Livewire && typeof window.Livewire.find === 'function');
    }

    async function callLivewire(method, ...args) {
        try {
            for (let i = 0; i < 40; i++) {
                if (livewireAvailable()) break;
                await new Promise(r => setTimeout(r, 150));
            }
            if (!livewireAvailable()) throw new Error('Livewire غير محمّل');

            const root = document.querySelector('[wire\\:id]');
            if (!root) throw new Error('لم يتم العثور على مكوّن Livewire');

            const comp = window.Livewire.find(root.getAttribute('wire:id'));
            if (!comp || typeof comp.$call !== 'function') {
                throw new Error('واجهة Livewire غير متاحة');
            }
            return await comp.$call(method, ...args);
        } catch (e) {
            console.error('[UF] Livewire error:', { method, args, e });
            showToast(e.message || 'تعذر تنفيذ العملية', 'error');
            return false;
        }
    }

    function setupLivewireBridge() {
        if (!window.Livewire || typeof window.Livewire.on !== 'function') return;
        if (window.__ufBridgeReady) return;
        window.__ufBridgeReady = true;

        console.log('[UF] Registering Livewire events');

        // استقبال تحديثات البيانات من PHP (uf-data-updated)
        window.Livewire.on('uf-data-updated', (payload) => {
            const data = (payload && payload.detail) || payload || {};
            console.log('[UF] Data updated:', data);

            if (Array.isArray(data.owners)) owners = data.owners;

            // تحديد السجل المُراد الحفاظ عليه
            const keepId = (data.keepOwner !== undefined && data.keepOwner !== null)
                ? data.keepOwner
                : (currentOwner ? currentOwner.id : null);

            // إعادة التعيين
            currentOwner = null;
            editingOwnerId = null;
            editingAgentId = null;
            deletingOwnerId = null;
            deletingAgentId = null;
            viewingAgentId = null;
            blockingOwnerId = null;
            blockingAgentId = null;

            // تحديث الإحصائيات
            updateStats();
            renderOwnersList();

            // عرض التفاصيل
            if (keepId) {
                const found = owners.find(o => Number(o.id) === Number(keepId));
                if (found) {
                    // إعادة التحديد مع الحفاظ على بحث الوكلاء
                    const savedSearch = agentSearchTerm;
                    selectOwner(found.id);
                    agentSearchTerm = savedSearch;
                    const s = $('agentSearchInput');
                    if (s) s.value = savedSearch;
                    if (savedSearch) renderAgents();
                } else {
                    $('detailsContent')?.classList.remove('active');
                    const es = $('emptyState');
                    if (es) es.style.display = 'flex';
                }
            } else if (owners.length > 0) {
                selectOwner(owners[0].id);
            } else {
                $('detailsContent')?.classList.remove('active');
                const es = $('emptyState');
                if (es) es.style.display = 'flex';
            }

            // إغلاق النوافذ
            document.querySelectorAll('.modal-overlay.show').forEach(m => closeModal(m.id));
            document.body.style.overflow = '';

            if (data.message) showToast(data.message, 'success');
        });

        // استقبال الأخطاء
        window.Livewire.on('uf-error', (payload) => {
            const data = (payload && payload.detail) || payload || {};
            console.error('[UF] Backend error:', data);
            showToast(data.message || 'حدث خطأ أثناء تنفيذ العملية', 'error');
        });

        console.log('[UF] Livewire bridge ready');
    }

    // ============================================================
    // تعبئة القوائم المنسدلة
    // ============================================================
    function populateStaticOptions() {
        // f_id_type
        const idSel = $('f_id_type');
        if (idSel) {
            const cur = idSel.value;
            idSel.innerHTML = Object.entries(idTypeLabels)
                .map(([k, l]) => `<option value="${esc(k)}">${esc(l)}</option>`).join('');
            if (cur) idSel.value = cur;
        }

        // f_payment
        const paySel = $('f_payment');
        if (paySel) {
            const cur = paySel.value;
            paySel.innerHTML = Object.entries(paymentLabels)
                .map(([k, l]) => `<option value="${esc(k)}">${esc(l)}</option>`).join('');
            if (cur) paySel.value = cur;
        }

        // f_agency — القيمة = organization_id
        const agSel = $('f_agency');
        if (agSel) {
            const cur = agSel.value;
            agSel.innerHTML = '<option value="">— اختر —</option>' +
                organizations.map(o => `<option value="${esc(o.id)}">${esc(o.name)}</option>`).join('');
            if (cur && [...agSel.options].some(x => x.value === cur)) agSel.value = cur;
        }

        // filterAgency — القيمة = اسم المؤسسة (فلتر عميل)
        const agFilter = $('filterAgency');
        if (agFilter) {
            const cur = agFilter.value;
            agFilter.innerHTML = '<option value="all">الكل</option>' +
                organizations.map(o => `<option value="${esc(o.name)}">${esc(o.name)}</option>`).join('');
            agFilter.value = (cur && [...agFilter.options].some(x => x.value === cur)) ? cur : 'all';
        }

        // filterIdType
        const idFilter = $('filterIdType');
        if (idFilter) {
            const cur = idFilter.value;
            idFilter.innerHTML = '<option value="all">الكل</option>' +
                Object.entries(idTypeLabels).map(([k, l]) =>
                    `<option value="${esc(k)}">${esc(l)}</option>`).join('');
            idFilter.value = (cur && [...idFilter.options].some(x => x.value === cur)) ? cur : 'all';
        }

        // filterPayment
        const payFilter = $('filterPayment');
        if (payFilter) {
            const cur = payFilter.value;
            payFilter.innerHTML = '<option value="all">الكل</option>' +
                Object.entries(paymentLabels).map(([k, l]) =>
                    `<option value="${esc(k)}">${esc(l)}</option>`).join('');
            payFilter.value = (cur && [...payFilter.options].some(x => x.value === cur)) ? cur : 'all';
        }

        // a_type
        const agTypeSel = $('a_type');
        if (agTypeSel) {
            const cur = agTypeSel.value;
            agTypeSel.innerHTML = Object.entries(agentTypeLabels)
                .map(([k, l]) => `<option value="${esc(k)}">${esc(l)}</option>`).join('');
            if (cur) agTypeSel.value = cur;
        }
    }

    // ============================================================
    // الإحصائيات
    // ============================================================
    function updateStats() {
        const total   = owners.length;
        const active  = owners.filter(o => o.status === 'active').length;
        const blocked = owners.filter(o => o.status === 'blocked').length;
        const agents  = owners.reduce((s, o) => s + ((o.agents || []).length), 0);
        const agencies = new Set(owners.map(o => o.agency).filter(Boolean)).size;

        if ($('statTotal'))   $('statTotal').textContent   = total;
        if ($('statActive'))  $('statActive').textContent  = active;
        if ($('statBlocked')) $('statBlocked').textContent = blocked;
        if ($('statAgents'))  $('statAgents').textContent  = agents;
        if ($('statAgencies'))$('statAgencies').textContent= agencies;
    }

    // ============================================================
    // الفلترة (عميل)
    // ============================================================
    function applyFilters() {
        let f = [...owners];

        if (filters.search) {
            const t = filters.search.toLowerCase();
            f = f.filter(o =>
                (o.full_name || '').toLowerCase().includes(t) ||
                (o.phone || '').includes(t) ||
                (o.phone2 || '').includes(t) ||
                (o.id_number || '').includes(t) ||
                (o.email || '').toLowerCase().includes(t)
            );
        }

        if (filters.status !== 'all')      f = f.filter(o => o.status === filters.status);
        if (filters.agency !== 'all')      f = f.filter(o => o.agency === filters.agency);
        if (filters.id_type !== 'all')     f = f.filter(o => o.id_type === filters.id_type);
        if (filters.nationality !== 'all') f = f.filter(o => o.nationality === filters.nationality);
        if (filters.payment !== 'all')     f = f.filter(o => o.payment === filters.payment);

        if (filters.agents === 'has')       f = f.filter(o => (o.agents || []).length > 0);
        else if (filters.agents === 'none') f = f.filter(o => (o.agents || []).length === 0);

        if (filters.dateFrom) f = f.filter(o => (o.created_at || '') >= filters.dateFrom);
        if (filters.dateTo)   f = f.filter(o => (o.created_at || '') <= filters.dateTo);

        switch (filters.sort) {
            case 'newest':    f.sort((a, b) => (b.created_at || '').localeCompare(a.created_at || '')); break;
            case 'oldest':    f.sort((a, b) => (a.created_at || '').localeCompare(b.created_at || '')); break;
            case 'name_asc':  f.sort((a, b) => (a.full_name || '').localeCompare(b.full_name || '', 'ar')); break;
            case 'name_desc': f.sort((a, b) => (b.full_name || '').localeCompare(a.full_name || '', 'ar')); break;
        }

        return f;
    }

    // ============================================================
    // عرض قائمة المالكين
    // ============================================================
    function renderOwnersList() {
        const list = $('ownersList');
        if (!list) return;

        const filtered = applyFilters();
        if ($('listCount')) $('listCount').textContent = filtered.length;

        if (!filtered.length) {
            list.innerHTML = `<div class="agent-empty" style="padding:1.75rem 1rem;">
                <i class="fas fa-user-slash"></i>لا توجد نتائج</div>`;
            return;
        }

        list.innerHTML = filtered.map(o => {
            const isBlocked = o.status === 'blocked';
            const avClass   = isBlocked ? 'blocked'
                            : (o.status === 'active' ? 'status-active' : 'status-inactive');

            return `<div class="owner-item ${currentOwner && currentOwner.id === o.id ? 'active' : ''} ${isBlocked ? 'blocked' : ''}"
                        onclick="selectOwner(${o.id})">
                <div class="owner-avatar ${avClass}">${firstChar(o.full_name)}</div>
                <div class="owner-info">
                    <div class="owner-name">${esc(o.full_name || '')}${isBlocked ? '<span class="blocked-tag">محظور</span>' : ''}</div>
                    <div class="owner-meta"><i class="fas fa-mobile-screen"></i> ${esc(o.phone || '')}</div>
                </div>
                <div class="owner-actions-mini">
                    <button type="button" class="btn-mini"
                        onclick="event.stopPropagation(); openOwnerModal(${o.id})" title="تعديل">
                        <i class="fas fa-pen"></i></button>
                    <button type="button" class="btn-mini ${isBlocked ? 'success' : 'warning'}"
                        onclick="event.stopPropagation(); openBlockOwnerModal(${o.id})"
                        title="${isBlocked ? 'إلغاء' : 'حظر'}">
                        <i class="fas fa-${isBlocked ? 'unlock' : 'ban'}"></i></button>
                    <button type="button" class="btn-mini danger"
                        onclick="event.stopPropagation(); openDeleteOwnerModal(${o.id})" title="حذف">
                        <i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('');
    }

    // ============================================================
    // اختيار مالك
    // ============================================================
    function selectOwner(id) {
        const owner = owners.find(o => Number(o.id) === Number(id));
        if (!owner) return;

        currentOwner = owner;

        // إعادة تعيين بحث الوكلاء
        agentSearchTerm = '';
        const agentSearch = $('agentSearchInput');
        if (agentSearch) agentSearch.value = '';

        // إظهار التفاصيل
        const empty = $('emptyState');
        const details = $('detailsContent');
        if (empty) empty.style.display = 'none';
        if (details) details.classList.add('active');

        // الصورة الرمزية
        const av = $('detailsAvatar');
        if (av) {
            av.textContent = firstChar(owner.full_name);
            av.className = 'details-avatar';
            if (owner.status === 'blocked') av.classList.add('blocked');
            else if (owner.status === 'active') av.classList.add('status-active');
            else av.classList.add('status-inactive');
        }

        // الاسم
        if ($('detailsName')) $('detailsName').textContent = owner.full_name || '—';

        // الحالة
        const stEl = $('detailsStatus');
        if (stEl) {
            if (owner.status === 'active') {
                stEl.className = 'status-tag active';
                stEl.innerHTML = '<span></span> نشط';
            } else if (owner.status === 'inactive') {
                stEl.className = 'status-tag inactive';
                stEl.innerHTML = '<span></span> غير نشط';
            } else {
                stEl.className = 'status-tag blocked';
                stEl.innerHTML = '<span></span> محظور';
            }
        }

        // معلومات مختصرة
        if ($('detailsIdNumber')) $('detailsIdNumber').textContent = owner.id_number || 'غير محدد';
        if ($('detailsAgency'))   $('detailsAgency').textContent   = owner.agency   || 'غير محدد';
        if ($('detailsCreated'))  $('detailsCreated').textContent  = owner.created_at || '—';

        // زر الحظر
        const blockBtn = $('blockBtn');
        if (blockBtn) {
            if (owner.status === 'blocked') {
                blockBtn.className = 'btn-action unblock';
                blockBtn.innerHTML = '<i class="fas fa-unlock"></i><span>إلغاء الحظر</span>';
            } else {
                blockBtn.className = 'btn-action block';
                blockBtn.innerHTML = '<i class="fas fa-ban"></i><span>حظر</span>';
            }
        }

        // معلومات تفصيلية
        const setInfo = (id, value) => {
            const el = $(id); if (!el) return;
            if (value) {
                el.textContent = value;
                el.classList.remove('empty');
            } else {
                el.textContent = 'غير محدد';
                el.classList.add('empty');
            }
        };

        setInfo('infoPhone',       owner.phone);
        setInfo('infoEmail',       owner.email);
        setInfo('infoNationality', owner.nationality);
        setInfo('infoIdType',      labelFor(idTypeLabels, owner.id_type));
        setInfo('infoAddress',     owner.address);
        setInfo('infoBank',        owner.bank_name);
        setInfo('infoIban',        owner.bank_iban);
        setInfo('infoPayment',     labelFor(paymentLabels, owner.payment));

        // ملاحظات
        const notesEl = $('infoNotes');
        if (notesEl) {
            if (owner.notes) {
                notesEl.textContent = owner.notes;
                notesEl.classList.add('has-content');
            } else {
                notesEl.textContent = 'لا توجد ملاحظات.';
                notesEl.classList.remove('has-content');
            }
        }

        // عرض الوكلاء + التحديد
        renderAgents();
        renderOwnersList();
    }

    // ============================================================
    // عرض الوكلاء
    // ============================================================
    function renderAgents() {
        const list = $('agentsList');
        const cnt  = $('agentsCount');
        if (!list || !cnt) return;

        if (!currentOwner) {
            list.innerHTML = '';
            cnt.textContent = '0';
            return;
        }

        let agents = currentOwner.agents || [];

        if (agentSearchTerm) {
            const t = agentSearchTerm.toLowerCase();
            agents = agents.filter(a =>
                agentName(a).toLowerCase().includes(t) ||
                (a.phone || '').includes(t) ||
                (a.email || '').toLowerCase().includes(t)
            );
        }

        cnt.textContent = (currentOwner.agents || []).length;

        if (!(currentOwner.agents || []).length) {
            list.innerHTML = `<div class="agent-empty">
                <i class="fas fa-user-tie"></i>لا يوجد وكلاء<br>
                <button type="button" class="btn-add-small" style="margin-top:.45rem"
                    onclick="openAgentModal()">
                    <i class="fas fa-plus"></i> إضافة أول وكيل
                </button></div>`;
            return;
        }

        if (!agents.length) {
            list.innerHTML = `<div class="agent-empty"><i class="fas fa-search"></i>لا توجد نتائج</div>`;
            return;
        }

        list.innerHTML = agents.map(a => {
            const isBlocked = a.status === 'blocked';
            const nm = agentName(a);
            const typeV = labelFor(agentTypeLabels, agentRole(a));

            return `<div class="agent-card ${isBlocked ? 'blocked' : ''}">
                <div class="agent-avatar ${isBlocked ? 'blocked' : ''}">${firstChar(nm)}</div>
                <div class="agent-info">
                    <div class="agent-name">${esc(nm)}${isBlocked ? '<span class="blocked-tag">معطل</span>' : ''}</div>
                    <div class="agent-meta">
                        <span><i class="fas fa-mobile-screen"></i> ${esc(a.phone || '')}</span>
                        ${typeV ? `<span class="agent-type-tag">${esc(typeV)}</span>` : ''}
                    </div>
                </div>
                <div class="agent-actions">
                    <button type="button" class="btn-agent-action view" onclick="viewAgent(${a.id})" title="عرض">
                        <i class="fas fa-eye"></i></button>
                    <button type="button" class="btn-agent-action edit" onclick="openAgentModal(${a.id})" title="تعديل">
                        <i class="fas fa-pen"></i></button>
                    <button type="button" class="btn-agent-action ${isBlocked ? 'unblock' : 'block'}" onclick="openBlockAgentModal(${a.id})" title="${isBlocked ? 'تفعيل' : 'تعطيل'}">
                        <i class="fas fa-${isBlocked ? 'unlock' : 'user-slash'}"></i></button>
                    <button type="button" class="btn-agent-action delete" onclick="openDeleteAgentModal(${a.id})" title="حذف">
                        <i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('');
    }

    // ============================================================
    // Modal Owner — فتح
    // ============================================================
    function openOwnerModal(id = null) {
        editingOwnerId = id;
        const form = $('ownerForm');
        if (form) form.reset();

        const title = $('ownerModalTitle');
        const sub   = $('ownerModalSubtitle');
        const st    = $('saveOwnerText');

        if (id) {
            const o = owners.find(x => Number(x.id) === Number(id));
            if (!o) return;

            if (title) title.textContent = 'تعديل بيانات المالك';
            if (sub)   sub.textContent   = 'قم بتحديث البيانات ثم احفظ التغييرات';
            if (st)    st.textContent    = 'حفظ التعديلات';

            if ($('f_full_name'))   $('f_full_name').value   = o.full_name || '';
            if ($('f_phone'))       $('f_phone').value       = o.phone || '';
            if ($('f_phone2'))      $('f_phone2').value      = o.phone2 || '';
            if ($('f_email'))       $('f_email').value       = o.email || '';
            if ($('f_id_type'))     $('f_id_type').value     = o.id_type || 'national_id';
            if ($('f_id_number'))   $('f_id_number').value   = o.id_number || '';
            if ($('f_nationality')) $('f_nationality').value = o.nationality || 'سعودي';
            if ($('f_address'))     $('f_address').value     = o.address || '';
            if ($('f_bank_name'))   $('f_bank_name').value   = o.bank_name || '';
            if ($('f_bank_iban'))   $('f_bank_iban').value   = o.bank_iban || '';
            if ($('f_payment'))     $('f_payment').value     = o.payment || 'bank_transfer';
            if ($('f_status'))      $('f_status').value      = o.status || 'active';
            if ($('f_notes'))       $('f_notes').value       = o.notes || '';

            // المؤسسة — نحاول بـ organization_id أولاً ثم بالاسم
            const agSel = $('f_agency');
            if (agSel) {
                if (o.organization_id != null &&
                    [...agSel.options].some(x => x.value === String(o.organization_id))) {
                    agSel.value = String(o.organization_id);
                } else if (o.agency) {
                    const opt = [...agSel.options].find(x => x.textContent.trim() === o.agency.trim());
                    agSel.value = opt ? opt.value : '';
                } else {
                    agSel.value = '';
                }
            }
        } else {
            if (title) title.textContent = 'إضافة مالك جديد';
            if (sub)   sub.textContent   = 'أدخل بيانات المالك الجديد';
            if (st)    st.textContent    = 'حفظ المالك';

            if ($('f_nationality')) $('f_nationality').value = 'سعودي';
            if ($('f_status'))      $('f_status').value      = 'active';
        }

        openModal('ownerModal');
    }

    // ============================================================
    // Modal Owner — حفظ
    // ============================================================
    function saveOwner() {
        const name  = $('f_full_name')?.value.trim();
        const phone = $('f_phone')?.value.trim();

        if (!name || !phone) {
            showToast('يرجى إدخال الاسم ورقم الجوال', 'error');
            return;
        }

        const fAgencyEl     = $('f_agency');
        const orgId         = fAgencyEl?.value || null;
        const agencyName    = fAgencyEl?.options[fAgencyEl.selectedIndex]?.textContent?.trim() || '';
        const paymentValue  = $('f_payment')?.value || 'bank_transfer';

        const data = {
            full_name:       name,
            phone:           phone,
            phone2:          $('f_phone2')?.value.trim() || '',
            email:           $('f_email')?.value.trim() || '',
            id_type:         $('f_id_type')?.value || 'national_id',
            id_number:       $('f_id_number')?.value.trim() || '',
            nationality:     $('f_nationality')?.value.trim() || 'سعودي',
            organization_id: orgId,
            agency:          agencyName,
            address:         $('f_address')?.value.trim() || '',
            bank_name:       $('f_bank_name')?.value.trim() || '',
            bank_iban:       $('f_bank_iban')?.value.trim() || '',
            payment:         paymentValue,
            preferred_payment_method: paymentValue,
            status:          $('f_status')?.value || 'active',
            notes:           $('f_notes')?.value.trim() || ''
        };

        if (livewireAvailable()) {
            callLivewire('saveOwner', data, editingOwnerId);
            return; // الـ bridge سيُغلق النافذة عند النجاح
        }

        // وضع تجريبي (بدون Livewire)
        if (editingOwnerId) {
            const o = owners.find(x => Number(x.id) === Number(editingOwnerId));
            if (o) Object.assign(o, data);
            showToast('تم تحديث بيانات المالك ✓', 'success');
        } else {
            const newOwner = {
                id: Math.max(...owners.map(o => Number(o.id) || 0), 0) + 1,
                ...data,
                created_at: new Date().toISOString().split('T')[0],
                agents: []
            };
            owners.unshift(newOwner);
            currentOwner = newOwner;
            showToast('تمت إضافة المالك ✓', 'success');
        }

        closeModal('ownerModal');
        editingOwnerId = null;
        updateStats();
        renderOwnersList();
        if (currentOwner) selectOwner(currentOwner.id);
    }

    // ============================================================
    // حظر / إلغاء حظر مالك
    // ============================================================
    function openBlockOwnerModal(id = null) {
        blockingOwnerId = id || currentOwner?.id || null;
        if (!blockingOwnerId) return;

        const o = owners.find(x => Number(x.id) === Number(blockingOwnerId));
        if (!o) return;

        const isBlocked = o.status === 'blocked';
        blockAction = isBlocked ? 'unblock' : 'block';

        if ($('blockOwnerName')) $('blockOwnerName').textContent = o.full_name || '';

        const t  = $('blockModalTitle');
        const s  = $('blockModalSubtitle');
        const ct = $('blockConfirmTitle');
        const txt= $('blockConfirmText');
        const ci = $('blockConfirmIcon');
        const cb = $('blockConfirmBtn');
        const mi = $('blockModalIcon');

        if (isBlocked) {
            if (t)   t.textContent = 'إلغاء حظر المالك';
            if (s)   s.textContent = 'سيتم إعادة الوصول للمالك';
            if (ct)  ct.textContent = 'إلغاء الحظر؟';
            if (txt) txt.innerHTML = `سيتم إلغاء حظر المالك <strong>${esc(o.full_name)}</strong> وسيتمكن من الوصول مجدداً.`;
            if (ci)  { ci.className = 'confirm-icon success'; ci.innerHTML = '<i class="fas fa-unlock"></i>'; }
            if (cb)  { cb.className = 'btn btn-success'; cb.innerHTML = '<i class="fas fa-unlock"></i><span>إلغاء الحظر</span>'; }
            if (mi)  { mi.style.background = 'linear-gradient(135deg,var(--emerald),#10B981)'; mi.innerHTML = '<i class="fas fa-unlock"></i>'; }
        } else {
            if (t)   t.textContent = 'حظر المالك';
            if (s)   s.textContent = 'سيتم منع الوصول';
            if (ct)  ct.textContent = 'حظر المالك؟';
            if (txt) txt.innerHTML = `سيتم حظر المالك <strong>${esc(o.full_name)}</strong> ولن يتمكن من الوصول. يمكنك إلغاء الحظر لاحقاً.`;
            if (ci)  { ci.className = 'confirm-icon warning'; ci.innerHTML = '<i class="fas fa-ban"></i>'; }
            if (cb)  { cb.className = 'btn btn-warning'; cb.innerHTML = '<i class="fas fa-ban"></i><span>حظر المالك</span>'; }
            if (mi)  { mi.style.background = 'linear-gradient(135deg,var(--terracotta),#F59E0B)'; mi.innerHTML = '<i class="fas fa-ban"></i>'; }
        }

        openModal('blockOwnerModal');
    }

    function confirmBlockOwner() {
        if (!blockingOwnerId) return;

        if (livewireAvailable()) {
            callLivewire('blockOwner', blockingOwnerId);
            return; // bridge يغلق
        }

        // demo
        const o = owners.find(x => Number(x.id) === Number(blockingOwnerId));
        if (!o) return;
        o.status = blockAction === 'unblock' ? 'active' : 'blocked';
        closeModal('blockOwnerModal');
        updateStats();
        renderOwnersList();
        if (currentOwner?.id === blockingOwnerId) selectOwner(blockingOwnerId);
        showToast(blockAction === 'unblock' ? 'تم إلغاء الحظر ✓' : 'تم الحظر ✓', 'success');
    }

    // ============================================================
    // حذف مالك
    // ============================================================
    function openDeleteOwnerModal(id = null) {
        deletingOwnerId = id || currentOwner?.id || null;
        if (!deletingOwnerId) return;

        const o = owners.find(x => Number(x.id) === Number(deletingOwnerId));
        if (!o) return;

        if ($('deleteOwnerName')) $('deleteOwnerName').textContent = o.full_name || '';
        openModal('deleteOwnerModal');
    }

    function confirmDeleteOwner() {
        if (!deletingOwnerId) return;

        if (livewireAvailable()) {
            callLivewire('deleteOwner', deletingOwnerId);
            return;
        }

        // demo
        const wasCurrent = currentOwner?.id === deletingOwnerId;
        owners = owners.filter(x => Number(x.id) !== Number(deletingOwnerId));
        if (wasCurrent) {
            currentOwner = null;
            $('detailsContent')?.classList.remove('active');
            const es = $('emptyState');
            if (es) es.style.display = 'flex';
        }
        closeModal('deleteOwnerModal');
        updateStats();
        renderOwnersList();
        showToast('تم حذف المالك ✓', 'success');
    }

    // ============================================================
    // Modal Agent — فتح / حفظ
    // ============================================================
    function openAgentModalStandalone() {
        if (!currentOwner && owners.length > 0) selectOwner(owners[0].id);
        if (!currentOwner) {
            showToast('يرجى إضافة مالك أولاً', 'error');
            return;
        }
        openAgentModal();
    }

    function openAgentModal(agentId = null) {
        if (!currentOwner) {
            showToast('اختر مالكاً أولاً', 'error');
            return;
        }

        editingAgentId = agentId;
        const form = $('agentForm');
        if (form) form.reset();

        const title = $('agentModalTitle');
        const sub   = $('agentModalSubtitle');
        const st    = $('saveAgentText');

        if (agentId) {
            const a = (currentOwner.agents || []).find(x => Number(x.id) === Number(agentId));
            if (!a) return;

            if (title) title.textContent = 'تعديل بيانات الوكيل';
            if (sub)   sub.textContent   = 'قم بتحديث بيانات الوكيل';
            if (st)    st.textContent    = 'حفظ التعديلات';

            if ($('a_name'))  $('a_name').value  = agentName(a);
            if ($('a_phone')) $('a_phone').value = a.phone || '';
            if ($('a_email')) $('a_email').value = a.email || '';
            if ($('a_type'))  $('a_type').value  = agentRole(a);
            if ($('a_notes')) $('a_notes').value = a.notes || '';
        } else {
            if (title) title.textContent = 'إضافة وكيل';
            if (sub)   sub.textContent   = 'أضف وكيلاً للمالك المحدد';
            if (st)    st.textContent    = 'إضافة الوكيل';
        }

        openModal('agentModal');
    }

    function saveAgent() {
        const name  = $('a_name')?.value.trim();
        const phone = $('a_phone')?.value.trim();

        if (!name || !phone) {
            showToast('يرجى إدخال اسم الوكيل ورقم جواله', 'error');
            return;
        }
        if (!currentOwner) return;

        const typeValue  = $('a_type')?.value || 'administrative';
        const emailValue = $('a_email')?.value.trim() || '';
        const notesValue = $('a_notes')?.value.trim() || '';

        const data = {
            name:      name,
            full_name: name,
            phone:     phone,
            email:     emailValue,
            type:      typeValue,
            role:      typeValue,
            notes:     notesValue
        };

        if (livewireAvailable()) {
            callLivewire('saveAgent', currentOwner.id, data, editingAgentId);
            return;
        }

        // demo
        if (!currentOwner.agents) currentOwner.agents = [];
        if (editingAgentId) {
            const a = currentOwner.agents.find(x => Number(x.id) === Number(editingAgentId));
            if (a) Object.assign(a, { name, phone, email: emailValue, type: typeValue, notes: notesValue });
            showToast('تم تحديث الوكيل ✓', 'success');
        } else {
            const newAgent = {
                id: Math.max(...currentOwner.agents.map(a => Number(a.id) || 0), 0) + 1,
                name, phone, email: emailValue, type: typeValue, notes: notesValue,
                status: 'active'
            };
            currentOwner.agents.push(newAgent);
            showToast('تمت إضافة الوكيل ✓', 'success');
        }
        closeModal('agentModal');
        editingAgentId = null;
        renderAgents();
        updateStats();
    }

    // ============================================================
    // عرض الوكيل
    // ============================================================
    function viewAgent(agentId) {
        if (!currentOwner) return;
        const a = (currentOwner.agents || []).find(x => Number(x.id) === Number(agentId));
        if (!a) return;

        viewingAgentId = agentId;
        const nm = agentName(a);
        const typeV = labelFor(agentTypeLabels, agentRole(a));

        const body = $('agentViewBody');
        if (body) {
            body.innerHTML = `
                <div class="agent-detail-row">
                    <div class="agent-detail-icon"><i class="fas fa-user"></i></div>
                    <div class="agent-detail-content">
                        <div class="agent-detail-label">اسم الوكيل</div>
                        <div class="agent-detail-value">${esc(nm)}</div>
                    </div>
                </div>
                <div class="agent-detail-row">
                    <div class="agent-detail-icon"><i class="fas fa-mobile-screen"></i></div>
                    <div class="agent-detail-content">
                        <div class="agent-detail-label">رقم الجوال</div>
                        <div class="agent-detail-value">${esc(a.phone || '')}</div>
                    </div>
                </div>
                <div class="agent-detail-row">
                    <div class="agent-detail-icon"><i class="fas fa-envelope"></i></div>
                    <div class="agent-detail-content">
                        <div class="agent-detail-label">البريد</div>
                        <div class="agent-detail-value">${esc(a.email || 'غير محدد')}</div>
                    </div>
                </div>
                <div class="agent-detail-row">
                    <div class="agent-detail-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="agent-detail-content">
                        <div class="agent-detail-label">النوع</div>
                        <div class="agent-detail-value">${esc(typeV)}</div>
                    </div>
                </div>
                <div class="agent-detail-row">
                    <div class="agent-detail-icon"><i class="fas fa-circle-info"></i></div>
                    <div class="agent-detail-content">
                        <div class="agent-detail-label">الحالة</div>
                        <div class="agent-detail-value">${a.status === 'blocked' ? 'معطل' : 'نشط'}</div>
                    </div>
                </div>
                <div class="agent-detail-row">
                    <div class="agent-detail-icon"><i class="fas fa-note-sticky"></i></div>
                    <div class="agent-detail-content">
                        <div class="agent-detail-label">ملاحظات</div>
                        <div class="agent-detail-value">${esc(a.notes || 'لا توجد')}</div>
                    </div>
                </div>
                <div class="agent-detail-row">
                    <div class="agent-detail-icon"><i class="fas fa-user-tie"></i></div>
                    <div class="agent-detail-content">
                        <div class="agent-detail-label">المالك</div>
                        <div class="agent-detail-value">${esc(currentOwner.full_name || '')}</div>
                    </div>
                </div>`;
        }
        openModal('agentViewModal');
    }

    function editAgentFromView() {
        closeModal('agentViewModal');
        if (!viewingAgentId) return;
        setTimeout(() => openAgentModal(viewingAgentId), 200);
    }

    // ============================================================
    // حظر / إلغاء حظر وكيل
    // ============================================================
    function openBlockAgentModal(agentId) {
        if (!currentOwner) return;
        const a = (currentOwner.agents || []).find(x => Number(x.id) === Number(agentId));
        if (!a) return;

        blockingAgentId = agentId;
        const isBlocked = a.status === 'blocked';
        blockAgentAction = isBlocked ? 'unblock' : 'block';

        if ($('blockAgentName')) $('blockAgentName').textContent = agentName(a);

        const t  = $('blockAgentTitle');
        const s  = $('blockAgentSubtitle');
        const ct = $('blockAgentConfirmTitle');
        const txt= $('blockAgentConfirmText');
        const ci = $('blockAgentConfirmIcon');
        const cb = $('blockAgentConfirmBtn');
        const mi = $('blockAgentIcon');

        if (isBlocked) {
            if (t)   t.textContent = 'إلغاء حظر الوكيل';
            if (s)   s.textContent = 'سيتم إعادة التفعيل';
            if (ct)  ct.textContent = 'إلغاء الحظر؟';
            if (txt) txt.innerHTML = `سيتم إلغاء حظر الوكيل <strong>${esc(agentName(a))}</strong>.`;
            if (ci)  { ci.className = 'confirm-icon success'; ci.innerHTML = '<i class="fas fa-unlock"></i>'; }
            if (cb)  { cb.className = 'btn btn-success'; cb.innerHTML = '<i class="fas fa-unlock"></i><span>تفعيل</span>'; }
            if (mi)  { mi.style.background = 'linear-gradient(135deg,var(--emerald),#10B981)'; mi.innerHTML = '<i class="fas fa-unlock"></i>'; }
        } else {
            if (t)   t.textContent = 'حظر الوكيل';
            if (s)   s.textContent = 'سيتم التعطيل';
            if (ct)  ct.textContent = 'حظر الوكيل؟';
            if (txt) txt.innerHTML = `سيتم حظر الوكيل <strong>${esc(agentName(a))}</strong>. يمكنك إلغاء الحظر لاحقاً.`;
            if (ci)  { ci.className = 'confirm-icon warning'; ci.innerHTML = '<i class="fas fa-user-slash"></i>'; }
            if (cb)  { cb.className = 'btn btn-warning'; cb.innerHTML = '<i class="fas fa-user-slash"></i><span>تعطيل</span>'; }
            if (mi)  { mi.style.background = 'linear-gradient(135deg,var(--terracotta),#F59E0B)'; mi.innerHTML = '<i class="fas fa-user-slash"></i>'; }
        }

        openModal('blockAgentModal');
    }

    function confirmBlockAgent() {
        if (!currentOwner || !blockingAgentId) return;

        if (livewireAvailable()) {
            callLivewire('blockAgent', blockingAgentId, currentOwner.id);
            return;
        }

        // demo
        const a = (currentOwner.agents || []).find(x => Number(x.id) === Number(blockingAgentId));
        if (!a) return;
        a.status = blockAgentAction === 'unblock' ? 'active' : 'blocked';
        closeModal('blockAgentModal');
        renderAgents();
        showToast(blockAgentAction === 'unblock' ? 'تم التفعيل ✓' : 'تم التعطيل ✓', 'success');
    }

    // ============================================================
    // حذف وكيل
    // ============================================================
    function openDeleteAgentModal(agentId) {
        if (!currentOwner) return;
        const a = (currentOwner.agents || []).find(x => Number(x.id) === Number(agentId));
        if (!a) return;

        deletingAgentId = agentId;
        if ($('deleteAgentName')) $('deleteAgentName').textContent = agentName(a);
        openModal('deleteAgentModal');
    }

    function confirmDeleteAgent() {
        if (!currentOwner || !deletingAgentId) return;

        if (livewireAvailable()) {
            callLivewire('deleteAgent', deletingAgentId, currentOwner.id);
            return;
        }

        // demo
        currentOwner.agents = (currentOwner.agents || []).filter(a => Number(a.id) !== Number(deletingAgentId));
        closeModal('deleteAgentModal');
        renderAgents();
        updateStats();
        showToast('تم حذف الوكيل ✓', 'success');
    }

    // ============================================================
    // Modals + Toast
    // ============================================================
    function openModal(id) {
        const el = $(id); if (!el) return;
        el.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        const el = $(id); if (!el) return;
        el.classList.remove('show');
        document.body.style.overflow = '';
    }

    let toastTimer;
    function showToast(msg, type = 'success') {
        const t = $('toast'), m = $('toastMsg');
        if (!t || !m) return;
        clearTimeout(toastTimer);
        t.className = 'toast ' + type;
        m.textContent = msg;
        const icon = t.querySelector('i');
        if (icon) icon.className = type === 'success'
            ? 'fas fa-circle-check' : 'fas fa-circle-exclamation';
        t.classList.add('show');
        toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
    }

    // ============================================================
    // ربط الفلاتر
    // ============================================================
    function bindFilters() {
        const b = (id, key, immediate) => {
            const el = $(id); if (!el) return;
            el.addEventListener(immediate ? 'input' : 'change', () => {
                filters[key] = el.value;
                renderOwnersList();
            });
        };
        b('searchInput',       'search', true);
        b('filterStatus',      'status');
        b('filterAgency',      'agency');
        b('filterIdType',      'id_type');
        b('filterNationality', 'nationality');
        b('filterDateFrom',    'dateFrom');
        b('filterDateTo',      'dateTo');
        b('filterPayment',     'payment');
        b('filterAgents',      'agents');
        b('filterSort',        'sort');

        const agentSearch = $('agentSearchInput');
        if (agentSearch) {
            agentSearch.addEventListener('input', e => {
                agentSearchTerm = e.target.value;
                renderAgents();
            });
        }
    }

    function clearFilters() {
        filters = {
            search: '', status: 'all', agency: 'all', id_type: 'all', nationality: 'all',
            dateFrom: '', dateTo: '', payment: 'all', agents: 'all', sort: 'newest'
        };
        const setV = (id, v) => { const el = $(id); if (el) el.value = v; };
        setV('searchInput', '');
        setV('filterStatus', 'all');
        setV('filterAgency', 'all');
        setV('filterIdType', 'all');
        setV('filterNationality', 'all');
        setV('filterDateFrom', '');
        setV('filterDateTo', '');
        setV('filterPayment', 'all');
        setV('filterAgents', 'all');
        setV('filterSort', 'newest');
        renderOwnersList();
        showToast('تم مسح الفلاتر', 'success');
    }

    // ============================================================
    // ربط النوافذ + زر Escape
    // ============================================================
    function bindModalInteractions() {
        document.querySelectorAll('.modal-overlay').forEach(o => {
            o.addEventListener('click', e => {
                if (e.target === o) closeModal(o.id);
            });
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.show').forEach(m => closeModal(m.id));
            }
        });
    }

    // ============================================================
    // التهيئة
    // ============================================================
    function init() {
        console.log('[UF] Initializing', {
            mode: livewireAvailable() ? 'Livewire' : 'Demo',
            owners: owners.length,
            orgs: organizations.length
        });

        populateStaticOptions();
        bindFilters();
        bindModalInteractions();
        setupLivewireBridge();

        updateStats();
        renderOwnersList();

        if (owners.length > 0) selectOwner(owners[0].id);
    }

    // ============================================================
    // عرض الدوال للنطاق العام (لمطابقة onclick في HTML)
    // ============================================================
    Object.assign(window, {
        // Owner
        openOwnerModal, saveOwner,
        openBlockOwnerModal, confirmBlockOwner,
        openDeleteOwnerModal, confirmDeleteOwner,
        selectOwner, renderOwnersList,

        // Agent
        openAgentModalStandalone, openAgentModal, saveAgent,
        viewAgent, editAgentFromView,
        openBlockAgentModal, confirmBlockAgent,
        openDeleteAgentModal, confirmDeleteAgent,

        // Utilities
        openModal, closeModal, showToast,
        clearFilters, callLivewire, populateStaticOptions,
        updateStats, renderAgents, renderOwnersList,

        // كائن UF موحّد للوصول
        UF: {
            selectOwner, openOwnerModal, saveOwner,
            openAgentModal, saveAgent, viewAgent,
            openBlockOwnerModal, openBlockAgentModal,
            openDeleteOwnerModal, openDeleteAgentModal,
            callLivewire, showToast, clearFilters,
            get currentOwner() { return currentOwner; },
            get owners()       { return owners; },
        }
    });

    // ============================================================
    // Boot
    // ============================================================
    document.addEventListener('livewire:init', setupLivewireBridge);
    document.addEventListener('livewire:load', setupLivewireBridge);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();