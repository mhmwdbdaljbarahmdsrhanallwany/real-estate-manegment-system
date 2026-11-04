<div wire:key="uf-root-{{ $entityKey }}">
    <div wire:ignore>

        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <div class="uf-wrapper">
            <div class="content-area">

                <div class="page-toolbar">
                    <div class="toolbar-title">
                        <div class="toolbar-icon"><i class="{{ $config['icon'] }}"></i></div>
                        <div class="toolbar-text">
                            <h1>{{ $config['title'] }}</h1>
                            <p>{{ $config['subtitle'] }}</p>
                        </div>
                    </div>
                    <div class="toolbar-actions">
                        <button type="button" class="btn-add-main blue" onclick="openAgentModalStandalone()">
                            <i class="{{ $config['sub_section_icon'] }}"></i> {{ $config['btn_add_sub'] }}
                        </button>
                        <button type="button" class="btn-add-main" onclick="openOwnerModal()">
                            <i class="fas fa-user-plus"></i> {{ $config['btn_add_main'] }}
                        </button>
                    </div>
                </div>

                <div class="filters-bar">
                    <div class="filters-row">
                        <div class="filter-search">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="بحث بالاسم، الجوال، الهوية، البريد...">
                        </div>
                        <div class="filter-item small">
                            <label class="filter-label"><i class="fas fa-toggle-on"></i> الحالة</label>
                            <select class="filter-control" id="filterStatus">
                                <option value="all">الكل</option>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                                <option value="blocked">محظور</option>
                            </select>
                        </div>
                        <div class="filter-item small">
                            <label class="filter-label"><i class="fas fa-building"></i> المؤسسة</label>
                            <select class="filter-control" id="filterAgency">
                                <option value="all">الكل</option>
                            </select>
                        </div>
                        <div class="filter-item small">
                            <label class="filter-label"><i class="fas fa-id-card"></i> نوع الهوية</label>
                            <select class="filter-control" id="filterIdType">
                                <option value="all">الكل</option>
                            </select>
                        </div>
                        <div class="filter-item small">
                            <label class="filter-label"><i class="fas fa-globe"></i> الجنسية</label>
                            <select class="filter-control" id="filterNationality">
                                <option value="all">الكل</option>
                                <option value="سعودي">سعودي</option>
                                <option value="يمني">يمني</option>
                                <option value="مقيم">مقيم</option>
                            </select>
                        </div>
                    </div>
                    <div class="filters-row">
                        <div class="filter-item">
                            <label class="filter-label"><i class="fas fa-calendar-plus"></i> من تاريخ</label>
                            <input type="date" class="filter-control" id="filterDateFrom">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label"><i class="fas fa-calendar-check"></i> إلى تاريخ</label>
                            <input type="date" class="filter-control" id="filterDateTo">
                        </div>
                        <div class="filter-item small">
                            <label class="filter-label"><i class="fas fa-wallet"></i> طريقة الدفع</label>
                            <select class="filter-control" id="filterPayment">
                                <option value="all">الكل</option>
                            </select>
                        </div>
                        <div class="filter-item small">
                            <label class="filter-label"><i class="fas fa-user-tie"></i> الوكلاء</label>
                            <select class="filter-control" id="filterAgents">
                                <option value="all">الكل</option>
                                <option value="has">لديه وكلاء</option>
                                <option value="none">بدون وكلاء</option>
                            </select>
                        </div>
                        <div class="filter-item small">
                            <label class="filter-label"><i class="fas fa-sort"></i> الترتيب</label>
                            <select class="filter-control" id="filterSort">
                                <option value="newest">الأحدث أولاً</option>
                                <option value="oldest">الأقدم أولاً</option>
                                <option value="name_asc">الاسم (أ-ي)</option>
                                <option value="name_desc">الاسم (ي-أ)</option>
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button type="button" class="btn-filter-action clear" onclick="clearFilters()">
                                <i class="fas fa-rotate-left"></i> مسح
                            </button>
                        </div>
                    </div>
                </div>

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon royal"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <div class="stat-value" id="statTotal">0</div>
                            <div class="stat-label">إجمالي {{ $config['entity_name'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon emerald"><i class="fas fa-user-check"></i></div>
                        <div class="stat-info">
                            <div class="stat-value" id="statActive">0</div>
                            <div class="stat-label">نشط</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon cherry"><i class="fas fa-user-slash"></i></div>
                        <div class="stat-info">
                            <div class="stat-value" id="statBlocked">0</div>
                            <div class="stat-label">محظور</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon sky"><i class="{{ $config['sub_section_icon'] }}"></i></div>
                        <div class="stat-info">
                            <div class="stat-value" id="statAgents">0</div>
                            <div class="stat-label">{{ $config['sub_section_title'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon gold"><i class="fas fa-building"></i></div>
                        <div class="stat-info">
                            <div class="stat-value" id="statAgencies">0</div>
                            <div class="stat-label">المؤسسات</div>
                        </div>
                    </div>
                </div>

                <div class="workspace">
                    <aside class="owners-panel">
                        <div class="panel-header">
                            <div class="panel-header-title">
                                <i class="fas fa-list"></i> قائمة {{ $config['entity_name'] }}
                                <span class="count" id="listCount">0</span>
                            </div>
                        </div>
                        <div class="owners-list" id="ownersList"></div>
                    </aside>

                    <main class="details-panel">
                        <div class="empty-state" id="emptyState">
                            <i class="fas fa-user-tie"></i>
                            <h3>اختر {{ $config['entity_singular'] }}اً لعرض تفاصيله</h3>
                            <p>انقر على أي {{ $config['entity_singular'] }} من القائمة لعرض بياناته وإدارة {{
                                $config['entity_sub'] }}</p>
                            <button type="button" class="btn-add-main" onclick="openOwnerModal()">
                                <i class="fas fa-user-plus"></i> إضافة {{ $config['entity_singular'] }} جديد
                            </button>
                        </div>

                        <div class="details-content" id="detailsContent">
                            <div class="details-header">
                                <div class="details-avatar" id="detailsAvatar">م</div>
                                <div class="details-info">
                                    <div class="details-name">
                                        <span id="detailsName">—</span>
                                        <span class="status-tag active" id="detailsStatus"><span></span> نشط</span>
                                    </div>
                                    <div class="details-id">
                                        <span><i class="fas fa-hashtag"></i> <span id="detailsIdNumber">—</span></span>
                                        <span><i class="fas fa-building"></i> <span id="detailsAgency">—</span></span>
                                        <span><i class="fas fa-calendar"></i> <span id="detailsCreated">—</span></span>
                                    </div>
                                </div>
                                <div class="details-actions">
                                    <button type="button" class="btn-action agent" onclick="openAgentModal()"><i
                                            class="fas fa-user-plus"></i> إضافة {{ $config['entity_sub'] }}</button>
                                    <button type="button" class="btn-action edit" onclick="editOwnerWithLivewire()">
                                        <i class="fas fa-pen"></i>
                                        تعديل
                                    </button>
                                    <button type="button" class="btn-action block" id="blockBtn"
                                        onclick="openBlockOwnerModal()"><i
                                            class="fas fa-ban"></i><span>حظر</span></button>
                                    <button type="button" class="btn-action delete" onclick="openDeleteOwnerModal()"><i
                                            class="fas fa-trash"></i> حذف</button>
                                </div>
                            </div>

                            <div class="details-body">
                                <div class="info-grid">
                                    <div class="info-card">
                                        <div class="info-card-icon blue"><i class="fas fa-mobile-screen"></i></div>
                                        <div>
                                            <div class="info-card-label">رقم الجوال</div>
                                            <div class="info-card-value" id="infoPhone">—</div>
                                        </div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon gold"><i class="fas fa-envelope"></i></div>
                                        <div>
                                            <div class="info-card-label">البريد</div>
                                            <div class="info-card-value" id="infoEmail">—</div>
                                        </div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon green"><i class="fas fa-globe"></i></div>
                                        <div>
                                            <div class="info-card-label">الجنسية</div>
                                            <div class="info-card-value" id="infoNationality">—</div>
                                        </div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon orange"><i class="fas fa-id-card"></i></div>
                                        <div>
                                            <div class="info-card-label">نوع الهوية</div>
                                            <div class="info-card-value" id="infoIdType">—</div>
                                        </div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon purple"><i class="fas fa-location-dot"></i></div>
                                        <div>
                                            <div class="info-card-label">العنوان</div>
                                            <div class="info-card-value" id="infoAddress">—</div>
                                        </div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon sky"><i class="fas fa-building-columns"></i></div>
                                        <div>
                                            <div class="info-card-label">البنك</div>
                                            <div class="info-card-value" id="infoBank">—</div>
                                        </div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon blue"><i class="fas fa-credit-card"></i></div>
                                        <div>
                                            <div class="info-card-label">الآيبان</div>
                                            <div class="info-card-value" id="infoIban">—</div>
                                        </div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon green"><i class="fas fa-wallet"></i></div>
                                        <div>
                                            <div class="info-card-label">طريقة الدفع</div>
                                            <div class="info-card-value" id="infoPayment">—</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-card">
                                    <div class="section-card-header">
                                        <i class="{{ $config['sub_section_icon'] }} section-icon"></i>
                                        <h3>{{ $config['sub_section_title'] }}</h3>
                                        <span class="badge" id="agentsCount">0</span>
                                        <div class="agents-tools">
                                            <div class="agent-search-mini">
                                                <i class="fas fa-search"></i>
                                                <input type="text" id="agentSearchInput" placeholder="بحث...">
                                            </div>
                                            <button type="button" class="btn-add-small" onclick="openAgentModal()"><i
                                                    class="fas fa-plus"></i> إضافة</button>
                                        </div>
                                    </div>
                                    <div class="section-card-body">
                                        <div id="agentsList"></div>
                                    </div>
                                </div>

                                <div class="section-card">
                                    <div class="section-card-header"><i class="fas fa-note-sticky section-icon"></i>
                                        <h3>ملاحظات</h3>
                                    </div>
                                    <div class="section-card-body">
                                        <div class="notes-box" id="infoNotes">لا توجد ملاحظات.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>

            {{-- Owner Modal --}}
            <div class="modal-overlay" id="ownerModal">
                <div class="modal">
                    <div class="modal-header">
                        <div class="modal-header-icon"><i class="fas fa-user-plus"></i></div>
                        <div class="modal-header-text">
                            <h2 id="ownerModalTitle">إضافة</h2>
                            <p id="ownerModalSubtitle">أدخل البيانات</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal('ownerModal')"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <form id="ownerForm" onsubmit="return false">
                            <div class="form-grid">
                                <div class="form-group"><label class="form-label">الاسم <span
                                            class="required">*</span></label><input type="text" class="form-control"
                                        id="f_full_name"></div>
                                <div class="form-group"><label class="form-label">الجوال <span
                                            class="required">*</span></label><input type="tel" class="form-control"
                                        id="f_phone"></div>
                                <div class="form-group"><label class="form-label">جوال ثانوي</label><input type="tel"
                                        class="form-control" id="f_phone2"></div>
                                <div class="form-group"><label class="form-label">البريد</label><input type="email"
                                        class="form-control" id="f_email"></div>
                                <div class="form-group"><label class="form-label">نوع الهوية</label><select
                                        class="form-control" id="f_id_type"></select></div>
                                <div class="form-group"><label class="form-label">رقم الهوية</label><input type="text"
                                        class="form-control" id="f_id_number"></div>
                                <div class="form-group"><label class="form-label">الجنسية</label><input type="text"
                                        class="form-control" id="f_nationality" value="سعودي"></div>
                                <div class="form-group"><label class="form-label">المؤسسة</label><select
                                        class="form-control" id="f_agency"></select></div>
                                <div class="form-group full"><label class="form-label">العنوان</label><input type="text"
                                        class="form-control" id="f_address"></div>
                                <div class="form-group"><label class="form-label">البنك</label><input type="text"
                                        class="form-control" id="f_bank_name"></div>
                                <div class="form-group"><label class="form-label">الآيبان</label><input type="text"
                                        class="form-control" id="f_bank_iban"></div>
                                <div class="form-group"><label class="form-label">طريقة الدفع</label><select
                                        class="form-control" id="f_payment"></select></div>
                                <div class="form-group"><label class="form-label">الحالة</label>
                                    <select class="form-control" id="f_status">
                                        <option value="active">نشط</option>
                                        <option value="inactive">غير نشط</option>
                                        <option value="blocked">محظور</option>
                                    </select>
                                </div>
                                <div class="form-group full"><label class="form-label">ملاحظات</label><textarea
                                        class="form-control" id="f_notes" rows="3"></textarea></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal('ownerModal')">إلغاء</button>
                        <button type="button" class="btn btn-primary" onclick="saveOwner()"><i
                                class="fas fa-floppy-disk"></i> <span id="saveOwnerText">حفظ</span></button>
                    </div>
                </div>
            </div>

            {{-- Agent Modal --}}
            <div class="modal-overlay" id="agentModal">
                <div class="modal medium">
                    <div class="modal-header">
                        <div class="modal-header-icon"><i class="{{ $config['sub_section_icon'] }}"></i></div>
                        <div class="modal-header-text">
                            <h2 id="agentModalTitle">إضافة وكيل</h2>
                            <p id="agentModalSubtitle">أضف وكيلاً</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal('agentModal')"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <form id="agentForm" onsubmit="return false">
                            <div class="form-grid">
                                <div class="form-group"><label class="form-label">الاسم <span
                                            class="required">*</span></label><input type="text" class="form-control"
                                        id="a_name"></div>
                                <div class="form-group"><label class="form-label">الجوال <span
                                            class="required">*</span></label><input type="tel" class="form-control"
                                        id="a_phone"></div>
                                <div class="form-group"><label class="form-label">البريد</label><input type="email"
                                        class="form-control" id="a_email"></div>
                                <div class="form-group"><label class="form-label">نوع الوكالة</label><select
                                        class="form-control" id="a_type"></select></div>
                                <div class="form-group full"><label class="form-label">ملاحظات</label><textarea
                                        class="form-control" id="a_notes" rows="2"></textarea></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal('agentModal')">إلغاء</button>
                        <button type="button" class="btn btn-gold" onclick="saveAgent()"><i
                                class="fas fa-floppy-disk"></i> <span id="saveAgentText">حفظ</span></button>
                    </div>
                </div>
            </div>

            {{-- Agent View --}}
            <div class="modal-overlay" id="agentViewModal">
                <div class="modal small">
                    <div class="modal-header">
                        <div class="modal-header-icon"><i class="fas fa-id-badge"></i></div>
                        <div class="modal-header-text">
                            <h2>تفاصيل الوكيل</h2>
                            <p>معلومات كاملة</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal('agentViewModal')"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                    <div class="modal-body" id="agentViewBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal('agentViewModal')">إغلاق</button>
                        <button type="button" class="btn btn-primary" onclick="editAgentFromView()"><i
                                class="fas fa-pen"></i> تعديل</button>
                    </div>
                </div>
            </div>

            {{-- Delete Owner --}}
            <div class="modal-overlay" id="deleteOwnerModal">
                <div class="modal small">
                    <div class="modal-header">
                        <div class="modal-header-icon"
                            style="background:linear-gradient(135deg,var(--cherry),#EF4444);color:#fff"><i
                                class="fas fa-trash"></i></div>
                        <div class="modal-header-text">
                            <h2>تأكيد الحذف</h2>
                            <p>لا يمكن التراجع</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal('deleteOwnerModal')"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="confirm-content">
                            <div class="confirm-icon"><i class="fas fa-triangle-exclamation"></i></div>
                            <h3>هل أنت متأكد؟</h3>
                            <p>سيتم حذف <strong id="deleteOwnerName">—</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal('deleteOwnerModal')">إلغاء</button>
                        <button type="button" class="btn btn-danger" onclick="confirmDeleteOwner()"><i
                                class="fas fa-trash"></i> نعم</button>
                    </div>
                </div>
            </div>

            {{-- Delete Agent --}}
            <div class="modal-overlay" id="deleteAgentModal">
                <div class="modal small">
                    <div class="modal-header">
                        <div class="modal-header-icon"
                            style="background:linear-gradient(135deg,var(--cherry),#EF4444);color:#fff"><i
                                class="fas fa-user-xmark"></i></div>
                        <div class="modal-header-text">
                            <h2>حذف الوكيل</h2>
                            <p>لا يمكن التراجع</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal('deleteAgentModal')"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="confirm-content">
                            <div class="confirm-icon"><i class="fas fa-triangle-exclamation"></i></div>
                            <h3>حذف الوكيل؟</h3>
                            <p><strong id="deleteAgentName">—</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal('deleteAgentModal')">إلغاء</button>
                        <button type="button" class="btn btn-danger" onclick="confirmDeleteAgent()"><i
                                class="fas fa-trash"></i> نعم</button>
                    </div>
                </div>
            </div>

            {{-- Block Owner --}}
            <div class="modal-overlay" id="blockOwnerModal">
                <div class="modal small">
                    <div class="modal-header">
                        <div class="modal-header-icon" id="blockModalIcon"
                            style="background:linear-gradient(135deg,var(--terracotta),#F59E0B);color:#fff"><i
                                class="fas fa-ban"></i></div>
                        <div class="modal-header-text">
                            <h2 id="blockModalTitle">حظر</h2>
                            <p id="blockModalSubtitle">سيتم منع الوصول</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal('blockOwnerModal')"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="confirm-content">
                            <div class="confirm-icon warning" id="blockConfirmIcon"><i class="fas fa-ban"></i></div>
                            <h3 id="blockConfirmTitle">حظر؟</h3>
                            <p>سيتم حظر <strong id="blockOwnerName">—</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal('blockOwnerModal')">إلغاء</button>
                        <button type="button" class="btn" id="blockConfirmBtn" onclick="confirmBlockOwner()"><i
                                class="fas fa-ban"></i> <span>حظر</span></button>
                    </div>
                </div>
            </div>

            {{-- Block Agent --}}
            <div class="modal-overlay" id="blockAgentModal">
                <div class="modal small">
                    <div class="modal-header">
                        <div class="modal-header-icon" id="blockAgentIcon"
                            style="background:linear-gradient(135deg,var(--terracotta),#F59E0B);color:#fff"><i
                                class="fas fa-user-slash"></i></div>
                        <div class="modal-header-text">
                            <h2 id="blockAgentTitle">حظر الوكيل</h2>
                            <p id="blockAgentSubtitle">سيتم التعطيل</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal('blockAgentModal')"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="confirm-content">
                            <div class="confirm-icon warning" id="blockAgentConfirmIcon"><i
                                    class="fas fa-user-slash"></i></div>
                            <h3 id="blockAgentConfirmTitle">حظر؟</h3>
                            <p><strong id="blockAgentName">—</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal('blockAgentModal')">إلغاء</button>
                        <button type="button" class="btn" id="blockAgentConfirmBtn" onclick="confirmBlockAgent()"><i
                                class="fas fa-user-slash"></i> <span>حظر</span></button>
                    </div>
                </div>
            </div>

            <div class="toast" id="toast"><i class="fas fa-circle-check"></i><span id="toastMsg"></span></div>
        </div>

        <script>
            // ============================================================
// البيانات القادمة من Laravel
// ============================================================

window.UF_DATA = {
    owners: @js($owners ?? []),
    stats: @js($stats ?? []),
    organizations: @js($organizations ?? []),

    idTypeLabels: @js(data_get($labels ?? [], 'idType', [])),
    paymentLabels: @js(data_get($labels ?? [], 'payment', [])),
    agentTypeLabels: @js(data_get($labels ?? [], 'agent', []))
};

let owners = window.UF_DATA.owners || [];
const organizations = window.UF_DATA.organizations || [];
const idTypeLabels = window.UF_DATA.idTypeLabels || {};
const paymentLabels = window.UF_DATA.paymentLabels || {};
const agentTypeLabels = window.UF_DATA.agentTypeLabels || {};

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

let filters = { search:'', status:'all', agency:'all', id_type:'all', nationality:'all',
                dateFrom:'', dateTo:'', payment:'all', agents:'all', sort:'newest' };
// ============================================================
// تعديل المالك عبر Livewire
// ============================================================
// ============================================================
// تعديل المالك عبر Livewire والنافذة الحالية
// ============================================================
async function editOwnerWithLivewire(id = null) {

    const ownerId = Number(id ?? currentOwner?.id);

    if (!ownerId || !Number.isFinite(ownerId)) {
        showToast('يرجى اختيار مالك أولاً', 'error');
        return;
    }

    const owner = owners.find(
        o => Number(o.id) === ownerId
    );

    if (!owner) {
        showToast('لم يتم العثور على بيانات المالك', 'error');
        return;
    }

    // تحميل السجل والتحقق منه في Livewire
    const result = await callLivewire('edit', ownerId);

    if (result !== true) {
        return;
    }

    // تحديد المالك وفتح النافذة الموجودة
    currentOwner = owner;

    openOwnerModal(ownerId);
}
// ============================================================
// استدعاء Livewire
// ============================================================
// ============================================================
// استدعاء Livewire 4 بطريقة آمنة
// ============================================================

async function callLivewire(method, ...args) {
    try {
        // انتظار جاهزية Livewire
        for (let i = 0; i < 30; i++) {
            if (
                window.Livewire &&
                typeof window.Livewire.find === 'function'
            ) {
                break;
            }

            await new Promise(resolve => setTimeout(resolve, 150));
        }

        if (!window.Livewire || typeof window.Livewire.find !== 'function') {
            throw new Error('Livewire JavaScript غير محمّل');
        }

        // العثور على المكوّن
        const root =
            document.querySelector('.uf-wrapper[wire\\:id]') ||
            document.querySelector('[wire\\:id]');

        if (!root) {
            throw new Error('لم يتم العثور على عنصر Livewire');
        }

        const componentId = root.getAttribute('wire:id');
        const component = window.Livewire.find(componentId);

        if (!component) {
            throw new Error('تعذر العثور على مكوّن Livewire');
        }

        // الاستدعاء المباشر وفق نتيجة الفحص
        if (typeof component.$call !== 'function') {
            throw new Error('واجهة استدعاء دوال Livewire غير متاحة');
        }

        const result = await component.$call(method, ...args);

        return result;

    } catch (error) {
        console.error('UniversalForm Livewire Error:', {
            method,
            args,
            error
        });

        showToast(
            error.message || 'تعذر تنفيذ العملية',
            'error'
        );

        return false;
    }
}

// ============================================================
// الإحصائيات
// ============================================================
function updateStats() {
    document.getElementById('statTotal').textContent   = owners.length;
    document.getElementById('statActive').textContent  = owners.filter(o => o.status === 'active').length;
    document.getElementById('statBlocked').textContent = owners.filter(o => o.status === 'blocked').length;
    document.getElementById('statAgents').textContent  = owners.reduce((s, o) => s + ((o.agents||[]).length), 0);
    document.getElementById('statAgencies').textContent = new Set(owners.map(o => o.agency).filter(Boolean)).size;
}

// ============================================================
// الفلترة
// ============================================================
function applyFilters() {
    let f = [...owners];
    if (filters.search) {
        const t = filters.search.toLowerCase();
        f = f.filter(o => (o.full_name||'').toLowerCase().includes(t) || (o.phone||'').includes(t)
            || (o.phone2||'').includes(t) || (o.id_number||'').includes(t) || (o.email||'').toLowerCase().includes(t));
    }
    if (filters.status !== 'all')      f = f.filter(o => o.status === filters.status);
    if (filters.agency !== 'all')      f = f.filter(o => o.agency === filters.agency);
    if (filters.id_type !== 'all')     f = f.filter(o => o.id_type === filters.id_type);
    if (filters.nationality !== 'all') f = f.filter(o => o.nationality === filters.nationality);
    if (filters.payment !== 'all')     f = f.filter(o => o.payment === filters.payment);
    if (filters.agents === 'has')      f = f.filter(o => (o.agents||[]).length > 0);
    else if (filters.agents === 'none') f = f.filter(o => !(o.agents||[]).length);
    if (filters.dateFrom) f = f.filter(o => (o.created_at||'') >= filters.dateFrom);
    if (filters.dateTo)   f = f.filter(o => (o.created_at||'') <= filters.dateTo);
    switch (filters.sort) {
        case 'newest':    f.sort((a,b) => (b.created_at||'').localeCompare(a.created_at||'')); break;
        case 'oldest':    f.sort((a,b) => (a.created_at||'').localeCompare(b.created_at||'')); break;
        case 'name_asc':  f.sort((a,b) => (a.full_name||'').localeCompare(b.full_name||'','ar')); break;
        case 'name_desc': f.sort((a,b) => (b.full_name||'').localeCompare(a.full_name||'','ar')); break;
    }
    return f;
}

// ============================================================
// العرض
// ============================================================
function renderOwnersList() {
    const list = document.getElementById('ownersList');
    const filtered = applyFilters();
    document.getElementById('listCount').textContent = filtered.length;
    if (!filtered.length) {
        list.innerHTML = `<div class="agent-empty" style="padding:1.75rem 1rem;"><i class="fas fa-user-slash"></i>لا توجد نتائج</div>`;
        return;
    }
    list.innerHTML = filtered.map(o => {
        const isBlocked = o.status === 'blocked';
        const avClass = isBlocked ? 'blocked' : (o.status === 'active' ? 'status-active' : 'status-inactive');
        return `<div class="owner-item ${currentOwner?.id === o.id ? 'active' : ''} ${isBlocked ? 'blocked' : ''}" onclick="selectOwner(${o.id})">
            <div class="owner-avatar ${avClass}">${(o.full_name||'?').charAt(0)}</div>
            <div class="owner-info">
                <div class="owner-name">${o.full_name||''}${isBlocked?'<span class="blocked-tag">محظور</span>':''}</div>
                <div class="owner-meta"><i class="fas fa-mobile-screen"></i> ${o.phone||''}</div>
            </div>
            <div class="owner-actions-mini">
<button type="button" class="btn-mini"
    onclick="event.stopPropagation(); editOwnerWithLivewire(${o.id})"
    title="تعديل">
    <i class="fas fa-pen"></i>
</button>                <button type="button" class="btn-mini ${isBlocked?'success':'warning'}" onclick="event.stopPropagation(); openBlockOwnerModal(${o.id})" title="${isBlocked?'إلغاء':'حظر'}"><i class="fas fa-${isBlocked?'unlock':'ban'}"></i></button>
                <button type="button" class="btn-mini danger" onclick="event.stopPropagation(); openDeleteOwnerModal(${o.id})" title="حذف"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
}

// ============================================================
// اختيار المالك وعرض تفاصيله ووكلائه
// ============================================================
function selectOwner(id) {

    const ownerId = Number(id);

    // البحث عن المالك
    const owner = owners.find(
        o => Number(o.id) === ownerId
    );

    if (!owner) {
        showToast('تعذر العثور على بيانات المالك', 'error');
        return;
    }

    // تحديد المالك الحالي
    currentOwner = owner;

    // إعادة ضبط البحث عن الوكلاء
    agentSearchTerm = '';

    const agentSearch = document.getElementById('agentSearchInput');
    if (agentSearch) {
        agentSearch.value = '';
    }

    // إظهار لوحة التفاصيل وإخفاء الحالة الفارغة
    const emptyState = document.getElementById('emptyState');
    const detailsContent = document.getElementById('detailsContent');

    if (emptyState) emptyState.style.display = 'none';
    if (detailsContent) detailsContent.classList.add('active');

    // ========================================================
    // بيانات المالك الأساسية
    // ========================================================

    const avatar = document.getElementById('detailsAvatar');

    if (avatar) {
        avatar.textContent = (owner.full_name || '?').charAt(0);
        avatar.className = 'details-avatar';

        if (owner.status === 'blocked') {
            avatar.classList.add('blocked');
        } else if (owner.status === 'active') {
            avatar.classList.add('status-active');
        } else {
            avatar.classList.add('status-inactive');
        }
    }

    const detailsName = document.getElementById('detailsName');
    if (detailsName) {
        detailsName.textContent = owner.full_name || '—';
    }

    // حالة المالك
    const statusElement = document.getElementById('detailsStatus');

    if (statusElement) {

        if (owner.status === 'active') {
            statusElement.className = 'status-tag active';
            statusElement.innerHTML = '<span></span> نشط';

        } else if (owner.status === 'inactive') {
            statusElement.className = 'status-tag inactive';
            statusElement.innerHTML = '<span></span> غير نشط';

        } else {
            statusElement.className = 'status-tag blocked';
            statusElement.innerHTML = '<span></span> محظور';
        }
    }

    // المعلومات المختصرة
    const setText = (elementId, value, fallback = 'غير محدد') => {
        const element = document.getElementById(elementId);

        if (element) {
            element.textContent = value || fallback;
        }
    };

    setText('detailsIdNumber', owner.id_number);
    setText('detailsAgency', owner.agency);
    setText('detailsCreated', owner.created_at, '—');

    // زر الحظر
    const blockButton = document.getElementById('blockBtn');

    if (blockButton) {

        if (owner.status === 'blocked') {
            blockButton.className = 'btn-action unblock';
            blockButton.innerHTML =
                '<i class="fas fa-unlock"></i><span>إلغاء الحظر</span>';
        } else {
            blockButton.className = 'btn-action block';
            blockButton.innerHTML =
                '<i class="fas fa-ban"></i><span>حظر</span>';
        }
    }

    // ========================================================
    // المعلومات التفصيلية للمالك
    // ========================================================

    const setInfo = (elementId, value) => {

        const element = document.getElementById(elementId);

        if (!element) return;

        if (value) {
            element.textContent = value;
            element.classList.remove('empty');
        } else {
            element.textContent = 'غير محدد';
            element.classList.add('empty');
        }
    };

    setInfo('infoPhone', owner.phone);
    setInfo('infoEmail', owner.email);
    setInfo('infoNationality', owner.nationality);
    setInfo(
        'infoIdType',
        idTypeLabels[owner.id_type] || owner.id_type
    );
    setInfo('infoAddress', owner.address);
    setInfo('infoBank', owner.bank_name);
    setInfo('infoIban', owner.bank_iban);
    setInfo(
        'infoPayment',
        paymentLabels[owner.payment] || owner.payment
    );

    const notesElement = document.getElementById('infoNotes');

    if (notesElement) {
        if (owner.notes) {
            notesElement.textContent = owner.notes;
            notesElement.classList.add('has-content');
        } else {
            notesElement.textContent = 'لا توجد ملاحظات.';
            notesElement.classList.remove('has-content');
        }
    }

    // ========================================================
    // عرض وكلاء المالك المحدد
    // ========================================================

    renderAgents();

    // تحديث القائمة لإظهار المالك المحدد
    renderOwnersList();

    console.log('[UF] Selected owner:', {
        id: owner.id,
        name: owner.full_name,
        agents: (owner.agents || []).length
    });
}
function renderAgents() {
    const list = document.getElementById('agentsList');
    const cnt = document.getElementById('agentsCount');
    if (!currentOwner) { list.innerHTML = ''; cnt.textContent = '0'; return; }
    let agents = currentOwner.agents || [];
    if (agentSearchTerm) {
        const t = agentSearchTerm.toLowerCase();
        agents = agents.filter(a => (a.name||'').toLowerCase().includes(t) || (a.phone||'').includes(t) || (a.email||'').toLowerCase().includes(t));
    }
    cnt.textContent = (currentOwner.agents||[]).length;
    if (!(currentOwner.agents||[]).length) {
        list.innerHTML = `<div class="agent-empty"><i class="fas fa-user-tie"></i>لا يوجد وكلاء<br><button type="button" class="btn-add-small" style="margin-top:.45rem" onclick="openAgentModal()"><i class="fas fa-plus"></i> إضافة أول وكيل</button></div>`;
        return;
    }
    if (!agents.length) {
        list.innerHTML = `<div class="agent-empty"><i class="fas fa-search"></i>لا توجد نتائج</div>`;
        return;
    }
    list.innerHTML = agents.map(a => {
        const isBlocked = a.status === 'blocked';
        return `<div class="agent-card ${isBlocked?'blocked':''}">
            <div class="agent-avatar ${isBlocked?'blocked':''}">${(a.name||'?').charAt(0)}</div>
            <div class="agent-info">
                <div class="agent-name">${a.name||''}${isBlocked?'<span class="blocked-tag">معطل</span>':''}</div>
                <div class="agent-meta"><span><i class="fas fa-mobile-screen"></i> ${a.phone||''}</span><span class="agent-type-tag">${agentTypeLabels[a.type]||a.type||''}</span></div>
            </div>
            <div class="agent-actions">
                <button type="button" class="btn-agent-action view" onclick="viewAgent(${a.id})" title="عرض"><i class="fas fa-eye"></i></button>
                <button type="button" class="btn-agent-action edit" onclick="openAgentModal(${a.id})" title="تعديل"><i class="fas fa-pen"></i></button>
                <button type="button" class="btn-agent-action ${isBlocked?'unblock':'block'}" onclick="openBlockAgentModal(${a.id})" title="${isBlocked?'تفعيل':'تعطيل'}"><i class="fas fa-${isBlocked?'unlock':'user-slash'}"></i></button>
                <button type="button" class="btn-agent-action delete" onclick="openDeleteAgentModal(${a.id})" title="حذف"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
}

// ============================================================
// Modal Owner
// ============================================================
function openOwnerModal(id = null) {
    editingOwnerId = id;
    const form = document.getElementById('ownerForm');
    if (form) form.reset();

    const title = document.getElementById('ownerModalTitle');
    const sub = document.getElementById('ownerModalSubtitle');
    const st = document.getElementById('saveOwnerText');

    if (id) {
        const o = owners.find(
    x => Number(x.id) === Number(id)
);
        if (!o) return;
        title.textContent = 'تعديل البيانات';
        sub.textContent = 'قم بتحديث البيانات';
        st.textContent = 'حفظ التعديلات';
        document.getElementById('f_full_name').value = o.full_name || '';
        document.getElementById('f_phone').value = o.phone || '';
        document.getElementById('f_phone2').value = o.phone2 || '';
        document.getElementById('f_email').value = o.email || '';
        document.getElementById('f_id_type').value = o.id_type || 'national_id';
        document.getElementById('f_id_number').value = o.id_number || '';
        document.getElementById('f_nationality').value = o.nationality || 'سعودي';
        document.getElementById('f_agency').value = o.organization_id || '';
        document.getElementById('f_address').value = o.address || '';
        document.getElementById('f_bank_name').value = o.bank_name || '';
        document.getElementById('f_bank_iban').value = o.bank_iban || '';
        document.getElementById('f_payment').value = o.payment || 'bank_transfer';
        document.getElementById('f_status').value = o.status || 'active';
        document.getElementById('f_notes').value = o.notes || '';
    } else {
        title.textContent = 'إضافة جديد';
        sub.textContent = 'أدخل البيانات';
        st.textContent = 'حفظ';
        document.getElementById('f_nationality').value = 'سعودي';
        document.getElementById('f_status').value = 'active';
    }
    openModal('ownerModal');
}

function saveOwner() {
    const name = document.getElementById('f_full_name').value.trim();
    const phone = document.getElementById('f_phone').value.trim();
    if (!name || !phone) { showToast('يرجى إدخال الاسم ورقم الجوال', 'error'); return; }
    const data = {
        full_name:   name,
        phone:       phone,
        phone2:      document.getElementById('f_phone2').value.trim(),
        email:       document.getElementById('f_email').value.trim(),
        id_type:     document.getElementById('f_id_type').value,
        id_number:   document.getElementById('f_id_number').value.trim(),
        nationality: document.getElementById('f_nationality').value.trim(),
        organization_id: document.getElementById('f_agency').value,
        address:     document.getElementById('f_address').value.trim(),
        bank_name:   document.getElementById('f_bank_name').value.trim(),
        bank_iban:   document.getElementById('f_bank_iban').value.trim(),
        payment:     document.getElementById('f_payment').value,
        status:      document.getElementById('f_status').value,
        notes:       document.getElementById('f_notes').value.trim(),
    };
    callLivewire('saveOwner', data, editingOwnerId);
}

// ============================================================
// Block / Delete Owner
// ============================================================
function openBlockOwnerModal(id = null) {
    blockingOwnerId = id || currentOwner?.id;
    if (!blockingOwnerId) return;
    const o = owners.find(x => x.id === blockingOwnerId);
    if (!o) return;
    const isBlocked = o.status === 'blocked';
    blockAction = isBlocked ? 'unblock' : 'block';
    document.getElementById('blockOwnerName').textContent = o.full_name;

    const t = document.getElementById('blockModalTitle');
    const s = document.getElementById('blockModalSubtitle');
    const ct = document.getElementById('blockConfirmTitle');
    const ci = document.getElementById('blockConfirmIcon');
    const cb = document.getElementById('blockConfirmBtn');
    const mi = document.getElementById('blockModalIcon');

    if (isBlocked) {
        t.textContent = 'إلغاء الحظر';
        s.textContent = 'سيتم إعادة الوصول';
        ct.textContent = 'إلغاء الحظر؟';
        ci.className = 'confirm-icon success';
        ci.innerHTML = '<i class="fas fa-unlock"></i>';
        cb.className = 'btn btn-success';
        cb.innerHTML = '<i class="fas fa-unlock"></i><span>إلغاء</span>';
        mi.style.background = 'linear-gradient(135deg,var(--emerald),#10B981)';
        mi.innerHTML = '<i class="fas fa-unlock"></i>';
    } else {
        t.textContent = 'حظر';
        s.textContent = 'سيتم منع الوصول';
        ct.textContent = 'حظر؟';
        ci.className = 'confirm-icon warning';
        ci.innerHTML = '<i class="fas fa-ban"></i>';
        cb.className = 'btn btn-warning';
        cb.innerHTML = '<i class="fas fa-ban"></i><span>حظر</span>';
        mi.style.background = 'linear-gradient(135deg,var(--terracotta),#F59E0B)';
        mi.innerHTML = '<i class="fas fa-ban"></i>';
    }
    openModal('blockOwnerModal');
}

function confirmBlockOwner() {
    if (!blockingOwnerId) return;
    callLivewire('blockOwner', blockingOwnerId);
    closeModal('blockOwnerModal');
}

function openDeleteOwnerModal(id = null) {
    deletingOwnerId = id || currentOwner?.id;
    if (!deletingOwnerId) return;
    const o = owners.find(x => x.id === deletingOwnerId);
    if (!o) return;
    document.getElementById('deleteOwnerName').textContent = o.full_name;
    openModal('deleteOwnerModal');
}

function confirmDeleteOwner() {
    if (!deletingOwnerId) return;
    callLivewire('deleteOwner', deletingOwnerId);
    closeModal('deleteOwnerModal');
}

// ============================================================
// Modal Agent
// ============================================================
function openAgentModalStandalone() {
    if (!currentOwner && owners.length > 0) selectOwner(owners[0].id);
    if (!currentOwner) { showToast('يرجى إضافة مالك أولاً', 'error'); return; }
    openAgentModal();
}

function openAgentModal(agentId = null) {
    if (!currentOwner) { showToast('اختر مالكاً أولاً', 'error'); return; }
    editingAgentId = agentId;
    const form = document.getElementById('agentForm');
    if (form) form.reset();

    const t = document.getElementById('agentModalTitle');
    const s = document.getElementById('agentModalSubtitle');
    const st = document.getElementById('saveAgentText');

    if (agentId) {
        const a = (currentOwner.agents||[]).find(x => x.id === agentId);
        if (!a) return;
        t.textContent = 'تعديل الوكيل';
        s.textContent = 'حدّث البيانات';
        st.textContent = 'حفظ التعديلات';
        document.getElementById('a_name').value = a.name || '';
        document.getElementById('a_phone').value = a.phone || '';
        document.getElementById('a_email').value = a.email || '';
        document.getElementById('a_type').value = a.type || 'administrative';
        document.getElementById('a_notes').value = a.notes || '';
    } else {
        t.textContent = 'إضافة وكيل';
        s.textContent = 'أضف وكيلاً';
        st.textContent = 'إضافة';
    }
    openModal('agentModal');
}

function saveAgent() {
    const name = document.getElementById('a_name').value.trim();
    const phone = document.getElementById('a_phone').value.trim();
    if (!name || !phone) { showToast('يرجى إدخال الاسم والجوال', 'error'); return; }
    if (!currentOwner) return;
    const data = {
        name:  name,
        phone: phone,
        email: document.getElementById('a_email').value.trim(),
        type:  document.getElementById('a_type').value,
        notes: document.getElementById('a_notes').value.trim(),
    };
    callLivewire('saveAgent', currentOwner.id, data, editingAgentId);
}

function viewAgent(agentId) {
    if (!currentOwner) return;
    const a = (currentOwner.agents||[]).find(x => x.id === agentId);
    if (!a) return;
    viewingAgentId = agentId;
    document.getElementById('agentViewBody').innerHTML = `
        <div class="agent-detail-row"><div class="agent-detail-icon"><i class="fas fa-user"></i></div><div><div class="agent-detail-label">الاسم</div><div class="agent-detail-value">${a.name||''}</div></div></div>
        <div class="agent-detail-row"><div class="agent-detail-icon"><i class="fas fa-mobile-screen"></i></div><div><div class="agent-detail-label">الجوال</div><div class="agent-detail-value">${a.phone||''}</div></div></div>
        <div class="agent-detail-row"><div class="agent-detail-icon"><i class="fas fa-envelope"></i></div><div><div class="agent-detail-label">البريد</div><div class="agent-detail-value">${a.email||'غير محدد'}</div></div></div>
        <div class="agent-detail-row"><div class="agent-detail-icon"><i class="fas fa-briefcase"></i></div><div><div class="agent-detail-label">النوع</div><div class="agent-detail-value">${agentTypeLabels[a.type]||a.type||''}</div></div></div>
        <div class="agent-detail-row"><div class="agent-detail-icon"><i class="fas fa-circle-info"></i></div><div><div class="agent-detail-label">الحالة</div><div class="agent-detail-value">${a.status==='blocked'?'معطل':'نشط'}</div></div></div>
        <div class="agent-detail-row"><div class="agent-detail-icon"><i class="fas fa-note-sticky"></i></div><div><div class="agent-detail-label">ملاحظات</div><div class="agent-detail-value">${a.notes||'لا توجد'}</div></div></div>
    `;
    openModal('agentViewModal');
}

function editAgentFromView() {
    closeModal('agentViewModal');
    setTimeout(() => openAgentModal(viewingAgentId), 200);
}

function openBlockAgentModal(agentId) {
    if (!currentOwner) return;
    const a = (currentOwner.agents||[]).find(x => x.id === agentId);
    if (!a) return;
    blockingAgentId = agentId;
    const isBlocked = a.status === 'blocked';
    blockAgentAction = isBlocked ? 'unblock' : 'block';
    document.getElementById('blockAgentName').textContent = a.name;

    const t = document.getElementById('blockAgentTitle');
    const s = document.getElementById('blockAgentSubtitle');
    const ct = document.getElementById('blockAgentConfirmTitle');
    const ci = document.getElementById('blockAgentConfirmIcon');
    const cb = document.getElementById('blockAgentConfirmBtn');
    const mi = document.getElementById('blockAgentIcon');

    if (isBlocked) {
        t.textContent = 'تفعيل الوكيل';
        s.textContent = 'سيتم التفعيل';
        ct.textContent = 'تفعيل؟';
        ci.className = 'confirm-icon success';
        ci.innerHTML = '<i class="fas fa-unlock"></i>';
        cb.className = 'btn btn-success';
        cb.innerHTML = '<i class="fas fa-unlock"></i><span>تفعيل</span>';
        mi.style.background = 'linear-gradient(135deg,var(--emerald),#10B981)';
        mi.innerHTML = '<i class="fas fa-unlock"></i>';
    } else {
        t.textContent = 'تعطيل الوكيل';
        s.textContent = 'سيتم التعطيل';
        ct.textContent = 'تعطيل؟';
        ci.className = 'confirm-icon warning';
        ci.innerHTML = '<i class="fas fa-user-slash"></i>';
        cb.className = 'btn btn-warning';
        cb.innerHTML = '<i class="fas fa-user-slash"></i><span>تعطيل</span>';
        mi.style.background = 'linear-gradient(135deg,var(--terracotta),#F59E0B)';
        mi.innerHTML = '<i class="fas fa-user-slash"></i>';
    }
    openModal('blockAgentModal');
}

function confirmBlockAgent() {
    if (!blockingAgentId || !currentOwner) return;
    callLivewire('blockAgent', blockingAgentId, currentOwner.id);
    closeModal('blockAgentModal');
}

function openDeleteAgentModal(agentId) {
    if (!currentOwner) return;
    const a = (currentOwner.agents||[]).find(x => x.id === agentId);
    if (!a) return;
    deletingAgentId = agentId;
    document.getElementById('deleteAgentName').textContent = a.name;
    openModal('deleteAgentModal');
}

function confirmDeleteAgent() {
    if (!deletingAgentId || !currentOwner) return;
    callLivewire('deleteAgent', deletingAgentId, currentOwner.id);
    closeModal('deleteAgentModal');
}

// ============================================================
// Modals + Toast
// ============================================================
function openModal(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }

document.querySelectorAll('.uf-wrapper .modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) closeModal(o.id); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.uf-wrapper .modal-overlay.show').forEach(m => closeModal(m.id));
});

let toastTimeout;
function showToast(msg, type = 'success') {
    clearTimeout(toastTimeout);
    const t = document.getElementById('toast');
    const m = document.getElementById('toastMsg');
    t.className = 'toast ' + type;
    m.textContent = msg;
    t.querySelector('i').className = type === 'success' ? 'fas fa-circle-check' : 'fas fa-circle-exclamation';
    t.classList.add('show');
    toastTimeout = setTimeout(() => t.classList.remove('show'), 3500);
}

// ============================================================
// الفلاتر
// ============================================================
function bindFilter(id, key, immediate) {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener(immediate ? 'input' : 'change', () => {
        filters[key] = el.value;
        renderOwnersList();
    });
}
bindFilter('searchInput', 'search', true);
bindFilter('filterStatus', 'status');
bindFilter('filterAgency', 'agency');
bindFilter('filterIdType', 'id_type');
bindFilter('filterNationality', 'nationality');
bindFilter('filterDateFrom', 'dateFrom');
bindFilter('filterDateTo', 'dateTo');
bindFilter('filterPayment', 'payment');
bindFilter('filterAgents', 'agents');
bindFilter('filterSort', 'sort');

document.getElementById('agentSearchInput')?.addEventListener('input', e => {
    agentSearchTerm = e.target.value;
    renderAgents();
});

function clearFilters() {
    filters = { search:'', status:'all', agency:'all', id_type:'all', nationality:'all', dateFrom:'', dateTo:'', payment:'all', agents:'all', sort:'newest' };
    const setV = (id, v) => { const el = document.getElementById(id); if (el) el.value = v; };
    ['searchInput','filterStatus','filterAgency','filterIdType','filterNationality','filterDateFrom','filterDateTo','filterPayment','filterAgents','filterSort'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        if (id === 'filterSort') el.value = 'newest';
        else if (id === 'searchInput' || id === 'filterDateFrom' || id === 'filterDateTo') el.value = '';
        else el.value = 'all';
    });
    renderOwnersList();
    showToast('تم مسح الفلاتر', 'success');
}

// ============================================================
// تعبئة القوائم (id_type, payment, agency, agent_type)
// ============================================================
function populateStaticOptions() {
    // id_type
    const idSel = document.getElementById('f_id_type');
    if (idSel) {
        idSel.innerHTML = '';
        Object.entries(idTypeLabels).forEach(([v, l]) => {
            idSel.innerHTML += `<option value="${v}">${l}</option>`;
        });
    }
    // payment
    const paySel = document.getElementById('f_payment');
    if (paySel) {
        paySel.innerHTML = '';
        Object.entries(paymentLabels).forEach(([v, l]) => {
            paySel.innerHTML += `<option value="${v}">${l}</option>`;
        });
    }
    // agency
    const agSel = document.getElementById('f_agency');
    if (agSel) {
        agSel.innerHTML = '<option value="">— اختر —</option>';
        organizations.forEach(org => {
            agSel.innerHTML += `<option value="${org.id}">${org.name}</option>`;
        });
    }
    // filter agency
    const agFilter = document.getElementById('filterAgency');
    if (agFilter) {
        agFilter.innerHTML = '<option value="all">الكل</option>';
        organizations.forEach(org => {
            agFilter.innerHTML += `<option value="${org.name}">${org.name}</option>`;
        });
    }
    // filter id_type
    const idFilter = document.getElementById('filterIdType');
    if (idFilter) {
        idFilter.innerHTML = '<option value="all">الكل</option>';
        Object.entries(idTypeLabels).forEach(([v, l]) => {
            idFilter.innerHTML += `<option value="${v}">${l}</option>`;
        });
    }
    // filter payment
    const payFilter = document.getElementById('filterPayment');
    if (payFilter) {
        payFilter.innerHTML = '<option value="all">الكل</option>';
        Object.entries(paymentLabels).forEach(([v, l]) => {
            payFilter.innerHTML += `<option value="${v}">${l}</option>`;
        });
    }
    // agent type
    const agTypeSel = document.getElementById('a_type');
    if (agTypeSel) {
        agTypeSel.innerHTML = '';
        Object.entries(agentTypeLabels).forEach(([v, l]) => {
            agTypeSel.innerHTML += `<option value="${v}">${l}</option>`;
        });
    }
}

// ============================================================
// Livewire Bridge
// ============================================================
// ============================================================
// Livewire Bridge - Livewire 4
// ============================================================
function setupLivewireBridge() {

    const LW = window.Livewire;

    if (!LW || typeof LW.on !== 'function') {
        console.warn('[UF] Waiting for Livewire...');
        return;
    }

    // منع تسجيل الأحداث أكثر من مرة
    if (window.__ufBridgeReady) {
        return;
    }

    window.__ufBridgeReady = true;

    console.log('[UF] Registering Livewire events');

    // ========================================================
    // تحديث البيانات بعد عمليات CRUD
    // ========================================================
    LW.on('uf-data-updated', (payload) => {

        console.log('[UF] Data updated:', payload);

        const data = payload?.detail ?? payload ?? {};

        if (data.owners) {
            owners = data.owners;
        }

        // تحديث الإحصائيات
        updateStats();

        // إعادة عرض القائمة
        renderOwnersList();

        // المحافظة على المالك المحدد
        if (data.keepOwner) {

            const found = owners.find(
                owner => Number(owner.id) === Number(data.keepOwner)
            );

            if (found) {
                currentOwner = found;
                selectOwner(found.id);
            } else {
                currentOwner = null;
            }

        } else if (currentOwner) {

            const found = owners.find(
                owner => Number(owner.id) === Number(currentOwner.id)
            );

            if (found) {
                currentOwner = found;
                selectOwner(found.id);
            } else {

                currentOwner = null;

                document.getElementById('detailsContent')
                    ?.classList.remove('active');

                const emptyState = document.getElementById('emptyState');

                if (emptyState) {
                    emptyState.style.display = 'flex';
                }
            }
        }

        // إغلاق النوافذ بعد نجاح العملية
        document.querySelectorAll(
            '.uf-wrapper .modal-overlay.show'
        ).forEach(modal => {
            modal.classList.remove('show');
        });

        document.body.style.overflow = '';

        if (data.message) {
            showToast(data.message, 'success');
        }

    });

    // ========================================================
    // استقبال أخطاء التحقق والعمليات
    // ========================================================
    LW.on('uf-error', (payload) => {

        console.error('[UF] Backend error:', payload);

        const data = payload?.detail ?? payload ?? {};

        showToast(
            data.message || 'حدث خطأ أثناء تنفيذ العملية',
            'error'
        );

    });

    console.log('[UF] Livewire Bridge Ready');
}

// ============================================================
// التهيئة الأولية
// ============================================================

function initializeUniversalForm() {

    console.log('[UF] Initializing Universal Form');

    populateStaticOptions();

    updateStats();

    renderOwnersList();

    if (owners.length > 0) {
        selectOwner(owners[0].id);
    }

    setupLivewireBridge();

    console.log('[UF] Initial data:', {
        owners: owners.length,
        organizations: organizations.length,
        idTypes: Object.keys(idTypeLabels).length
    });
}

// ============================================================
// انتظار جاهزية Livewire
// ============================================================

document.addEventListener('livewire:init', () => {

    console.log('[UF] livewire:init received');

    setupLivewireBridge();

});

// ============================================================
// تهيئة الواجهة
// ============================================================

if (document.readyState === 'loading') {

    document.addEventListener(
        'DOMContentLoaded',
        initializeUniversalForm,
        { once: true }
    );

} else {

    initializeUniversalForm();

}
// Livewire
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupLivewireBridge);
} else {
    setupLivewireBridge();
}

document.addEventListener('livewire:init', setupLivewireBridge);
document.addEventListener('livewire:load', setupLivewireBridge);

// Diagnostic
console.log('[UF] Initial data:', { owners: owners.length, orgs: organizations.length, idTypes: Object.keys(idTypeLabels).length });
        </script>

    </div>
</div>