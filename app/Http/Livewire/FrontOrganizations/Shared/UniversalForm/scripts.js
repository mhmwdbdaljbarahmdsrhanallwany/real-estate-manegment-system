/* ============================================================
 * UniversalForm — scripts.js
 * ============================================================
 * الدور:
 * 1) استقبال بيانات الكيان من Livewire عبر uf:bootstrap و uf:update.
 * 2) تحويل البيانات إلى حالة JavaScript موحّدة.
 * 3) رسم أجزاء الواجهة الموجودة في قالب Blade.
 * 4) إرسال عمليات الإضافة والتعديل والحذف إلى مكوّن Livewire نفسه.
 *
 * ملاحظة:
 * هذا الملف يتوقع وجود هيكل الواجهة في Blade، مثل عناصر الإحصائيات
 * والقائمة والتفاصيل والنوافذ المنبثقة ذات المعرفات المستخدمة أدناه.
 * JavaScript يملأ هذه العناصر ويرسم محتوياتها الديناميكية.
 * ============================================================ */

(function () {
  'use strict';

  /* ============================================================
   * الحالة العامة للمكوّن
   * ============================================================ */

  let UF = {};
  let LIVE_MODE = false;

  let records = [];
  let config = {};
  let lookups = {};
  let organizations = [];
  let statsData = {};

  // هذه القيم قابلة للتحديث عند وصول حزمة جديدة من Livewire؛ لذلك يجب أن تكون let لا const.
  let idTypeLabels = {};
  let paymentLabels = {};
  let agentTypeLabels = {};

  // مرجع مباشر للمكوّن الذي أرسل البيانات، حتى لا نستدعي مكوّناً آخر في الصفحة بالخطأ.
  let UF_WIRE = window.__UF_WIRE || null;
  let UF_ROOT = window.__UF_ROOT || null;

  // متغيرات حالة الواجهة الحالية.
  let currentRecord = null;
  let editingId = null;
  let editingChildId = null;
  let deletingId = null;
  let deletingChildId = null;
  let viewingChildId = null;
  let blockingId = null;
  let blockingChildId = null;
  let blockAction = 'block';
  let blockChildAction = 'block';

  let agentSearchTerm = '';
  let filters = {};
  let initialized = false;
  let boundRoot = null;
  let toastTimer = null;

  /* ============================================================
   * أدوات مساعدة عامة
   * ============================================================ */

  /**
   * يعيد أول عنصر مطابق لمعرفه داخل جذر المكوّن، ثم يبحث في الصفحة كاملة كخطة بديلة.
   * يساعد ذلك على إبقاء عمليات البحث داخل مكوّن UniversalForm عند وجود أكثر من مكوّن في الصفحة.
   */
  function byId(id) {
    if (UF_ROOT && typeof UF_ROOT.querySelector === 'function') {
      const local = UF_ROOT.querySelector(`[id="${String(id).replace(/"/g, '\\"')}"]`);
      if (local) return local;
    }

    return document.getElementById(id);
  }

  /**
   * يعيد أول عنصر يطابق محدداً CSS داخل جذر المكوّن، مع الرجوع إلى document عند الحاجة.
   */
  function query(selector) {
    if (UF_ROOT && typeof UF_ROOT.querySelector === 'function') {
      const local = UF_ROOT.querySelector(selector);
      if (local) return local;
    }

    return document.querySelector(selector);
  }

  /**
   * يعيد جميع العناصر المطابقة داخل جذر المكوّن، لمنع ربط أحداث عناصر مكوّن آخر.
   */
  function queryAll(selector) {
    if (UF_ROOT && typeof UF_ROOT.querySelectorAll === 'function') {
      const local = UF_ROOT.querySelectorAll(selector);
      if (local.length) return Array.from(local);
    }

    return Array.from(document.querySelectorAll(selector));
  }

  /**
   * يحوّل القيمة إلى نص آمن للعرض داخل HTML، ويمنع إدخال وسوم أو خصائص HTML غير مقصودة.
   */
  function esc(value) {
    if (value === null || value === undefined) return '';

    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  /**
   * يقرأ قيمة متداخلة من إعدادات config باستخدام مسار مثل ui.details.title_field.
   * إذا لم يوجد المسار يعيد القيمة الافتراضية المحددة.
   */
  function getCfg(path, fallback = '') {
    const parts = String(path).split('.');
    let current = config;

    for (const part of parts) {
      if (current && typeof current === 'object' && part in current) {
        current = current[part];
      } else {
        return fallback;
      }
    }

    return current !== undefined && current !== null ? current : fallback;
  }

  /**
   * يعيد اسم أول علاقة مفعّلة في إعدادات الكيان، أو null إذا لم توجد علاقة مفعّلة.
   */
  function relKey() {
    if (!config.relations || typeof config.relations !== 'object') return null;

    for (const key of Object.keys(config.relations)) {
      if (config.relations[key] && config.relations[key].enabled) return key;
    }

    return null;
  }

  /**
   * يقرأ قيمة حقل من سجل تابع، ويعيد نصاً فارغاً عند غياب السجل أو الحقل.
   */
  function childRecordField(record, key) {
    return record && record[key] !== undefined && record[key] !== null
      ? record[key]
      : '';
  }

  /**
   * يحوّل رمز الحالة إلى التسمية العربية المعرفة في config.status.labels.
   */
  function statusLabel(status) {
    return (config.status && config.status.labels && config.status.labels[status])
      || status
      || '';
  }

  /**
   * يعيد اسماً عربياً عاماً للكيان أو الاسم الافتراضي عند عدم تعريفه في الإعدادات.
   */
  function entitySingular() {
    return config.entityKey_singular || config.entity_singular || 'السجل';
  }

  /**
   * يعيد اسماً جمعياً للعناصر التابعة وفق الإعدادات، مع استخدام تسمية عامة عند غيابها.
   */
  function childEntityLabel() {
    return config.entityKey_sub || config.child_entity_label || 'العناصر التابعة';
  }

  /**
   * يحوّل معرف السجل إلى رقم آمن للاستخدام داخل استدعاءات أحداث HTML المضمنة.
   * مفاتيح SmartEstate الحالية رقمية؛ إذا كان المعرف غير رقمي فلن يتم إنشاء زر لهذا السجل.
   */
  function safeNumericId(value) {
    const number = Number(value);
    return Number.isFinite(number) ? number : null;
  }

  /**
   * يقرأ قيمة عنصر نموذج مع مراعاة checkbox وحقول النص والأرقام والقوائم.
   */
  function readFieldValue(element) {
    if (!element) return '';
    if (element.type === 'checkbox') return element.checked ? 1 : 0;
    return typeof element.value === 'string' ? element.value.trim() : element.value;
  }

  /**
   * يحدد ما إذا كان الحقل مخصصاً لرفع ملف وفق type=file في إعدادات الكيان.
   */
  function isFileField(field) {
    return !!field && field.type === 'file';
  }

  /**
   * ينشئ مساحة مساعدة أسفل مدخل الملف لعرض اسم الملف المختار أو الملف المحفوظ حالياً.
   */
  function fileInfoElement(input) {
    if (!input) return null;

    let info = input.parentElement?.querySelector(`[data-uf-file-info="${input.id}"]`);
    if (!info) {
      info = document.createElement('small');
      info.dataset.ufFileInfo = input.id;
      info.className = 'uf-file-info';
      info.style.display = 'block';
      info.style.marginTop = '6px';
      info.style.fontSize = '12px';
      info.style.color = '#64748b';
      input.insertAdjacentElement('afterend', info);
    }

    return info;
  }

  /**
   * يحول عنصر الحقل الموجود في Blade إلى input type=file عند تعريف الحقل كملف،
   * ويضبط accept من إعدادات upload.mimes ويعرض حالة المرفق السابق في وضع التعديل.
   */
  function configureFileInput(field, prefix, existingRecord = {}) {
    if (!isFileField(field)) return;

    const input = byId(prefix + field.key);
    if (!input) {
      logError(`لم يتم العثور على عنصر رفع الملف: ${prefix + field.key}`);
      return;
    }

    if (input.tagName !== 'INPUT') {
      logError(`يجب أن يكون عنصر الحقل ${prefix + field.key} من نوع input حتى يتحول إلى file.`);
      return;
    }

    input.type = 'file';
    input.dataset.ufFileInput = '1';

    const upload = field.upload || {};
    const mimes = Array.isArray(upload.mimes)
      ? upload.mimes
      : String(upload.mimes || '').split(',').map((item) => item.trim()).filter(Boolean);

    const accept = upload.accept || mimes.map((mime) => '.' + String(mime).replace(/^\./, '')).join(',');
    if (accept) input.accept = accept;

    const info = fileInfoElement(input);
    const existingPath = existingRecord && existingRecord[field.key];

    if (existingPath && upload.disk === 'public' && upload.public_url === true) {
      const safePath = String(existingPath).split('/').map((part) => encodeURIComponent(part)).join('/');
      info.innerHTML = `يوجد ملف مرفق حالياً. <a href="/storage/${safePath}" target="_blank" rel="noopener">عرض الملف الحالي</a> — اختيار ملف جديد سيستبدله.`;
    } else {
      info.textContent = existingPath
        ? 'يوجد ملف مرفق حالياً. اختيار ملف جديد سيستبدل الملف الحالي.'
        : (field.required ? 'هذا الملف مطلوب.' : 'لم يتم اختيار ملف.');
    }

    input.onchange = () => {
      const selected = input.files && input.files[0];
      info.textContent = selected
        ? `الملف المختار: ${selected.name}`
        : (existingPath ? 'سيتم الاحتفاظ بالملف الحالي.' : 'لم يتم اختيار ملف.');
      input.classList.remove('is-invalid');
      clearOneFieldError(input);
    };
  }

  /**
   * يزيل رسالة تحقق سابقة لحقل واحد عندما يبدأ المستخدم بتعديل قيمته.
   */
  function clearOneFieldError(input) {
    if (!input) return;
    const next = input.parentElement?.querySelector(`[data-uf-error-for="${input.id}"]`)
      || input.nextElementSibling;
    if (next && next.classList && next.classList.contains('uf-validation-error')) next.remove();
    input.removeAttribute('aria-invalid');
  }

  /**
   * يمسح رسائل التحقق السابقة من نموذج محدد قبل إعادة استخدامه للإضافة أو التعديل.
   */
  function clearValidationErrors(prefix = null) {
    const root = UF_ROOT || document;
    root.querySelectorAll('.uf-validation-error').forEach((element) => element.remove());
    root.querySelectorAll('.is-invalid').forEach((element) => element.classList.remove('is-invalid'));

    if (prefix) {
      const fields = prefix === 'main' ? (config.main_fields || []) : (config.relations?.[relKey()]?.fields || []);
      fields.forEach((field) => {
        const input = byId((prefix === 'main' ? 'f_' : 'a_') + field.key);
        if (input) input.removeAttribute('aria-invalid');
      });
    }
  }

  /**
   * يعرض أخطاء التحقق القادمة من Laravel بجوار الحقول التي فشلت،
   * باستخدام مفاتيح f_field للحقول الرئيسية وa_field للحقول التابعة.
   */
  function showValidationErrors(errors = {}) {
    Object.entries(errors || {}).forEach(([inputId, messages]) => {
      const input = byId(inputId);
      const message = Array.isArray(messages) ? messages[0] : String(messages || '');
      if (!input || !message) return;

      clearOneFieldError(input);
      input.classList.add('is-invalid');
      input.setAttribute('aria-invalid', 'true');

      const error = document.createElement('small');
      error.className = 'uf-validation-error';
      error.dataset.ufErrorFor = inputId;
      error.textContent = message;
      error.style.display = 'block';
      error.style.marginTop = '5px';
      error.style.color = '#dc2626';
      error.style.fontSize = '12px';
      input.insertAdjacentElement('afterend', error);
    });
  }

  /**
   * يرفع جميع ملفات النموذج المحددة إلى خاصية Livewire المناسبة بالترتيب،
   * ولا يستدعي saveRecord أو saveChild إلا بعد اكتمال رفع كل الملفات بنجاح.
   */
  async function uploadConfiguredFiles(fields, prefix, uploadProperty, existingRecord = {}) {
    if (!LIVE_MODE) return true;
    if (!UF_WIRE || typeof UF_WIRE.upload !== 'function') {
      showToast('واجهة رفع الملفات في Livewire غير متاحة.', 'error');
      return false;
    }

    for (const field of (fields || [])) {
      if (!isFileField(field)) continue;

      const input = byId(prefix + field.key);
      const file = input?.files?.[0] || null;
      const oldPath = existingRecord?.[field.key] || null;

      if (!file) {
        if (field.required && !oldPath) {
          showToast(`يرجى اختيار ملف في حقل ${field.label || field.key}.`, 'error');
          input?.classList.add('is-invalid');
          return false;
        }
        continue;
      }

      const uploadConfig = field.upload || {};
      const maxKb = Number(uploadConfig.max_kb || 10240);
      if (maxKb > 0 && file.size > maxKb * 1024) {
        showToast(`حجم الملف في ${field.label || field.key} أكبر من ${maxKb} كيلوبايت.`, 'error');
        input.classList.add('is-invalid');
        return false;
      }

      const mimes = Array.isArray(uploadConfig.mimes)
        ? uploadConfig.mimes
        : String(uploadConfig.mimes || '').split(',').map((item) => item.trim()).filter(Boolean);
      const extension = file.name.includes('.') ? file.name.split('.').pop().toLowerCase() : '';
      if (mimes.length && !mimes.map((item) => String(item).replace(/^\./, '').toLowerCase()).includes(extension)) {
        showToast(`امتداد الملف في ${field.label || field.key} غير مسموح.`, 'error');
        input.classList.add('is-invalid');
        return false;
      }

      const info = fileInfoElement(input);
      const propertyPath = `${uploadProperty}.${field.key}`;

      try {
        await new Promise((resolve, reject) => {
          UF_WIRE.upload(
            propertyPath,
            file,
            () => resolve(true),
            (error) => reject(error || new Error('فشل رفع الملف.')),
            (event) => {
              const progress = event?.detail?.progress ?? 0;
              if (info) info.textContent = `جاري رفع ${file.name}: ${progress}%`;
            },
            () => reject(new Error('تم إلغاء رفع الملف.'))
          );
        });

        if (info) info.textContent = `تم رفع الملف المؤقت: ${file.name}`;
        input.classList.remove('is-invalid');
      } catch (error) {
        if (info) info.textContent = 'فشل رفع الملف. حاول مرة أخرى.';
        showToast(error.message || `تعذر رفع ${file.name}.`, 'error');
        return false;
      }
    }

    return true;
  }

  /**
   * ينشئ مجموعة الفلاتر الافتراضية حسب تعريفات الكيان، ويترك حقول التاريخ والبحث فارغة.
   */
  function buildEmptyFilters() {
    const output = { search: '', sort: 'newest' };

    (config.filters || []).forEach((filter) => {
      if (!filter || !filter.key) return;
      output[filter.key] = filter.type === 'date' ? '' : 'all';
    });

    output.dateFrom = output.dateFrom || '';
    output.dateTo = output.dateTo || '';
    output.agentSearch = '';

    return output;
  }

  /**
   * يطبع رسالة تشخيصية موحدة في وحدة التحكم مع بادئة UniversalForm.
   */
  function logError(message, error = null) {
    if (error) console.error('[UniversalForm] ' + message, error);
    else console.error('[UniversalForm] ' + message);
  }

  /* ============================================================
   * استقبال البيانات والتواصل مع Livewire
   * ============================================================ */

  /**
   * يوحّد أسماء مفاتيح حزمة البيانات القادمة من PHP، ويدعم entity و entityKey للتوافق.
   */
  function normalizePayload(rawPayload) {
    const data = rawPayload && rawPayload.data ? rawPayload.data : (rawPayload || {});
    const normalized = { ...data };

    normalized.entity = data.entity || data.entityKey || data.entity_key || '';
    normalized.entityKey = data.entityKey || data.entity || data.entity_key || '';
    normalized.config = data.config || {};
    normalized.records = Array.isArray(data.records) ? data.records : [];
    normalized.lookups = data.lookups || {};
    normalized.stats = data.stats || {};

    return normalized;
  }

  /**
   * يستقبل حزمة البيانات الأولية أو المحدثة من Blade، ويحدث حالة JavaScript ثم يرسم الواجهة.
   * يقرأ أيضاً مرجع $wire وجذر المكوّن المرسلَين ضمن context.
   */
  function receiveUniversalFormData(rawPayload, context = {}, isInitial = false) {
    const data = normalizePayload(rawPayload);

    if (!data.config || Object.keys(data.config).length === 0) {
      logError('وصلت حزمة بيانات بدون config صالح.');
      return false;
    }

    UF = data;
    LIVE_MODE = !!context.wire || !!data.livewireId;

    // عند تمرير root تابع لـ Livewire يمكن استخدامه للتحقق من وجود معرف المكوّن.
    if (!LIVE_MODE && context.root && context.root.closest) {
      LIVE_MODE = !!context.root.closest('[wire\\:id]');
    }

    UF_WIRE = context.wire || UF_WIRE || window.__UF_WIRE || null;
    UF_ROOT = context.root || UF_ROOT || window.__UF_ROOT || null;

    config = data.config || {};
    records = Array.isArray(data.records) ? data.records : [];
    lookups = data.lookups || {};
    organizations = data.organizations || lookups.organization_id || [];
    statsData = data.stats || {};

    idTypeLabels = data.idTypeLabels || {};
    paymentLabels = data.paymentLabels || {};
    agentTypeLabels = data.agentTypeLabels || {};

    if (isInitial || !initialized) {
      filters = buildEmptyFilters();
      initializeInterface();
    } else {
      refreshInterface(data);
    }

    console.log('[UF] تم استلام بيانات الصفحة:', {
      entity: data.entity || data.entityKey,
      records: records.length,
      liveMode: LIVE_MODE,
      wireAvailable: !!UF_WIRE
    });

    return true;
  }

  /**
   * يستدعي دالة PHP في مكوّن Livewire المرتبط بهذه الصفحة، مع دعم $wire.$call والتوافق الاحتياطي.
   * لا يبحث عن أول مكوّن عشوائي في الصفحة إذا كان مرجع المكوّن الحالي متاحاً.
   */
  async function callLivewire(method, ...args) {
    try {
      if (UF_WIRE) {
        if (typeof UF_WIRE.$call === 'function') {
          return await UF_WIRE.$call(method, ...args);
        }

        if (typeof UF_WIRE[method] === 'function') {
          return await UF_WIRE[method](...args);
        }
      }

      // مسار توافق احتياطي عند تشغيل السكربت دون تمرير $wire من Blade.
      if (window.Livewire && typeof window.Livewire.find === 'function') {
        const componentId =
          (UF_ROOT && UF_ROOT.closest('[wire\\:id]') && UF_ROOT.closest('[wire\\:id]').getAttribute('wire:id'))
          || (UF.livewireId || null);

        if (componentId) {
          const component = window.Livewire.find(componentId);
          if (component && typeof component.$call === 'function') {
            return await component.$call(method, ...args);
          }
        }
      }

      throw new Error('مرجع مكوّن Livewire غير متاح. مرّر $wire من Blade إلى uf:bootstrap.');
    } catch (error) {
      logError('فشل استدعاء Livewire: ' + method, { args, error });
      showToast(error.message || 'تعذر تنفيذ العملية', 'error');
      return false;
    }
  }

  /**
   * يستقبل حدث uf:bootstrap الذي يرسله Blade عند فتح المكوّن لأول مرة.
   */
  function handleBootstrapEvent(event) {
    const detail = event.detail || {};
    receiveUniversalFormData(detail.data || {}, detail, true);
  }

  /**
   * يستقبل حدث uf:update الذي يرسله Blade بعد نجاح عمليات الإضافة والتعديل والحذف.
   */
  function handleUpdateEvent(event) {
    const detail = event.detail || {};
    receiveUniversalFormData(detail.data || {}, detail, false);
  }

  /**
   * يستقبل حدث uf:error القادم من Livewire ويعرض رسالة الخطأ للمستخدم.
   */
  function handleErrorEvent(event) {
    const detail = event.detail || {};
    showValidationErrors(detail.errors || {});
    showToast(detail.message || 'حدث خطأ أثناء تنفيذ العملية', 'error');
  }

  /**
   * يربط مستمعي الأحداث المخصصة مرة واحدة لاستقبال البيانات والتحديثات والأخطاء من Blade.
   */
  function setupLivewireBridge() {
    // إذا كان ملف scripts.js قد نُفّذ قبل تحميل Livewire، أعد المحاولة عند تهيئته.
    if (!window.__universalFormLivewireInitHookReady) {
      document.addEventListener('livewire:init', setupLivewireBridge, { once: true });
      document.addEventListener('livewire:initialized', setupLivewireBridge, { once: true });
      window.__universalFormLivewireInitHookReady = true;
    }

    // ربط أحداث Blade المخصصة، وتسجيلها مرة واحدة فقط.
    if (!window.__universalFormCustomBridgeReady) {
      window.addEventListener('uf:bootstrap', handleBootstrapEvent);
      window.addEventListener('uf:update', handleUpdateEvent);
      window.addEventListener('uf:error', handleErrorEvent);
      window.__universalFormCustomBridgeReady = true;
    }

    // دعم أحداث PHP المباشرة: uf-data-updated و uf-error.
    // Livewire 4 يمرر معاملات dispatch ككائن مباشر إلى Livewire.on.
    if (window.Livewire && typeof window.Livewire.on === 'function' && !window.__universalFormLivewireEventsReady) {
      window.Livewire.on('uf-data-updated', (payload = {}) => {
        const data = payload.data || payload.detail?.data || payload.detail || payload;
        receiveUniversalFormData(data, {
          wire: window.__UF_WIRE || UF_WIRE,
          root: window.__UF_ROOT || UF_ROOT
        }, false);
      });

      window.Livewire.on('uf-error', (payload = {}) => {
        const detail = payload.detail || payload;
        showValidationErrors(detail.errors || {});
        showToast(detail.message || 'حدث خطأ أثناء تنفيذ العملية', 'error');
      });

      window.__universalFormLivewireEventsReady = true;
    }
  }

  /* ============================================================
   * الإحصائيات
   * ============================================================ */

  /**
   * يرسم بطاقات الإحصائيات داخل statsRow اعتماداً على الحزمة stats القادمة من PHP.
   */
  function updateStats() {
    const row = byId('statsRow');
    if (!row) return;

    row.innerHTML = Object.values(statsData).map((stat) => `
      <div class="stat-card">
        <div class="stat-icon ${esc(stat.color || 'royal')}">
          <i class="${esc(stat.icon || 'fas fa-chart-simple')}"></i>
        </div>
        <div class="stat-info">
          <div class="stat-value">${esc(stat.value ?? 0)}</div>
          <div class="stat-label">${esc(stat.label || '')}</div>
        </div>
      </div>
    `).join('');
  }

  /* ============================================================
   * البحث والفلترة والترتيب
   * ============================================================ */

/**
 * تطبيق جميع فلاتر الكيان العام.
 *
 * تدعم:
 * - البحث النصي.
 * - فلاتر الاختيار Select.
 * - فلاتر التاريخ.
 * - فلاتر وجود العلاقات التابعة.
 * - الترتيب.
 *
 * تعتمد على config.filters ولا تحتاج إلى كتابة فلتر
 * مستقل لكل كيان.
 */
function applyFilters() {

    let filtered = [...records];

    // =====================================================
    // 1. البحث العام
    // =====================================================

    const searchTerm = String(filters.search || '')
        .trim()
        .toLowerCase();

    if (searchTerm) {

        const searchFields = config.search?.fields || [];

        filtered = filtered.filter(record => {

            return searchFields.some(field => {

                const value = record[field];

                return String(value ?? '')
                    .toLowerCase()
                    .includes(searchTerm);

            });

        });
    }

    // =====================================================
    // 2. الفلاتر المعرفة في configs.php
    // =====================================================

    (config.filters || []).forEach(filter => {

        if (!filter || !filter.key) return;

        const key = filter.key;
        const type = filter.type || 'select';
        const value = filters[key];

        if (
            value === undefined ||
            value === null ||
            value === '' ||
            value === 'all'
        ) {
            return;
        }

        // -------------------------------------------------
        // تجاهل فلاتر الترتيب والبحث هنا.
        // -------------------------------------------------

        if (type === 'sort' || type === 'search') {
            return;
        }

        // -------------------------------------------------
        // فلتر وجود العلاقة التابعة.
        // مثال: هل لدى المالك وكلاء؟
        // -------------------------------------------------

        if (type === 'relation_exists') {

            const relationKey = filter.relation;

            filtered = filtered.filter(record => {

                const relationRecords = Array.isArray(
                    record[relationKey]
                )
                    ? record[relationKey]
                    : [];

                if (value === 'has') {
                    return relationRecords.length > 0;
                }

                if (value === 'none') {
                    return relationRecords.length === 0;
                }

                return true;

            });

            return;
        }

        // -------------------------------------------------
        // فلاتر التاريخ
        // -------------------------------------------------

        if (type === 'date') {

            const column = filter.column || key;

            filtered = filtered.filter(record => {

                const recordDate = String(
                    record[column] || ''
                ).substring(0, 10);

                if (!recordDate) return false;

                if (key === 'dateFrom') {
                    return recordDate >= value;
                }

                if (key === 'dateTo') {
                    return recordDate <= value;
                }

                return recordDate === value;

            });

            return;
        }

        // -------------------------------------------------
        // فلاتر الاختيار Select
        // -------------------------------------------------

        if (type === 'select') {

            const column = filter.column || key;

            filtered = filtered.filter(record => {

                // الحالة تأتي من القيمة المحسوبة في PHP.
                const recordValue = key === 'status'
                    ? record.status
                    : record[column];

                return String(recordValue ?? '').trim()
                    === String(value).trim();

            });

        }

    });

    // =====================================================
    // 3. الترتيب
    // =====================================================

    const sort = filters.sort || 'newest';

    const nameField = config.name_field || config.primary_key;

    switch (sort) {

        case 'oldest':

            filtered.sort((a, b) =>
                String(a.created_at || '')
                    .localeCompare(String(b.created_at || ''))
            );

            break;

        case 'name_asc':

            filtered.sort((a, b) =>
                String(a[nameField] || '')
                    .localeCompare(
                        String(b[nameField] || ''),
                        'ar'
                    )
            );

            break;

        case 'name_desc':

            filtered.sort((a, b) =>
                String(b[nameField] || '')
                    .localeCompare(
                        String(a[nameField] || ''),
                        'ar'
                    )
            );

            break;

        default:

            filtered.sort((a, b) =>
                String(b.created_at || '')
                    .localeCompare(String(a.created_at || ''))
            );

    }

    return filtered;
}
 


/**
 * ربط عنصر HTML بمفتاح الفلتر الخاص به.
 * عند تغيير القيمة يتم تحديث الفلاتر وإعادة رسم القائمة.
 */
function bindFilter(id, key) {

    const element = byId(id);

    if (!element) {

        console.warn(
            '[UF] لم يتم العثور على عنصر الفلتر:',
            id
        );

        return;
    }

    const update = () => {

        filters[key] = element.value;

        console.log('[UF] Filter changed:', {
            key: key,
            value: element.value
        });

        if (key === 'agentSearch') {

            agentSearchTerm = element.value;

            renderChildren();

        } else {

            renderList();

        }

    };

    if (element.tagName === 'INPUT' && element.type !== 'date') {
        element.oninput = update;
    } else {
        element.onchange = update;
    }
}


/**
 * ربط فلاتر المالكين والعلاقات.
 * يمكن إضافة أي كيان آخر عن طريق تعريف مفاتيحه في configs.php
 * وإضافة معرف عنصره في قالب الواجهة.
 */
function initFilters() {

    bindFilter('searchInput', 'search');

    bindFilter('filterStatus', 'status');

    bindFilter('filterAgency', 'organization_id');

    bindFilter('filterIdType', 'id_type');

    bindFilter('filterNationality', 'nationality');

    bindFilter('filterDateFrom', 'dateFrom');

    bindFilter('filterDateTo', 'dateTo');

    bindFilter('filterPayment', 'preferred_payment_method');

    bindFilter('filterAgents', 'hasSub');

    bindFilter('filterSort', 'sort');

    bindFilter('agentSearchInput', 'agentSearch');

    bindFilter('filterBlockStatus', 'is_blacklisted');
}
  /**
   * يعيد جميع قيم الفلاتر إلى وضعها الافتراضي، ثم يعيد رسم القائمة ويعرض إشعاراً.
   */
  function clearFilters() {
    filters = buildEmptyFilters();

    queryAll('.filters-bar .filter-control').forEach((element) => {
      if (element.tagName === 'SELECT') {
        element.value = element.options[0] ? element.options[0].value : '';
      } else {
        element.value = '';
      }
    });

    const search = byId('searchInput');
    if (search) search.value = '';

    const sort = byId('filterSort');
    if (sort) sort.value = 'newest';

    const childSearch = byId('agentSearchInput');
    if (childSearch) childSearch.value = '';
    agentSearchTerm = '';

    renderList();
    renderChildren();
    showToast('تم مسح الفلاتر', 'success');
  }

  /* ============================================================
   * رسم قائمة السجلات الرئيسية
   * ============================================================ */

  /**
   * يرسم قائمة السجلات في ownersList وفق إعدادات ui.list ويعرض إجراءات كل سجل.
   */
  function renderList() {
    const list = byId('ownersList');
    if (!list) return;

    const filtered = applyFilters();
    const countElement = byId('listCount');
    if (countElement) countElement.textContent = String(filtered.length);

    if (filtered.length === 0) {
      list.innerHTML = `
        <div class="agent-empty" style="padding:1.75rem 1rem;">
          <i class="fas fa-user-slash"></i>
          لا توجد نتائج مطابقة للفلاتر
        </div>`;
      return;
    }

    const primaryKey = config.primary_key;
    const avatarField = getCfg('ui.list.avatar_field', primaryKey);
    const titleField = getCfg('ui.list.title_field', primaryKey);
    const subtitleField = getCfg('ui.list.subtitle_field', '');
    const subtitleIcon = getCfg('ui.list.subtitle_icon', 'fas fa-circle-info');
    const statusField = getCfg('ui.list.status_field', 'status');

    list.innerHTML = filtered.map((record) => {
      const recordId = safeNumericId(record[primaryKey]);
      if (recordId === null) return '';

      const status = record[statusField] || record.status || 'active';
      const isBlocked = status === 'blocked';
      const avatarClass = isBlocked
        ? 'blocked'
        : (status === 'active' ? 'status-active' : 'status-inactive');
      const initial = String(record[avatarField] || '?').trim().charAt(0) || '?';
      const isActive = currentRecord && Number(currentRecord[primaryKey]) === recordId;

      return `
        <div class="owner-item ${isActive ? 'active' : ''} ${isBlocked ? 'blocked' : ''}"
             onclick="UF.selectRecord(${recordId})">
          <div class="owner-avatar ${avatarClass}">${esc(initial)}</div>
          <div class="owner-info">
            <div class="owner-name">
              ${esc(record[titleField] || '')}
              ${isBlocked ? `<span class="blocked-tag">${esc(statusLabel('blocked'))}</span>` : ''}
            </div>
            <div class="owner-meta">
              <i class="${esc(subtitleIcon)}"></i>
              ${esc(subtitleField ? record[subtitleField] : '')}
            </div>
          </div>
          <div class="owner-actions-mini">
            <button type="button" class="btn-mini"
                    onclick="event.stopPropagation(); UF.openRecordModal(${recordId})"
                    title="تعديل">
              <i class="fas fa-pen"></i>
            </button>
            ${config.actions && config.actions.status ? `
              <button type="button" class="btn-mini ${isBlocked ? 'success' : 'warning'}"
                      onclick="event.stopPropagation(); UF.openBlockModal(${recordId})"
                      title="${isBlocked ? 'إلغاء الحظر' : 'حظر'}">
                <i class="fas fa-${isBlocked ? 'unlock' : 'ban'}"></i>
              </button>` : ''}
            <button type="button" class="btn-mini danger"
                    onclick="event.stopPropagation(); UF.openDeleteModal(${recordId})"
                    title="حذف">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>`;
    }).join('');
  }

  /* ============================================================
   * تفاصيل السجل الرئيسي
   * ============================================================ */

  /**
   * يحدد السجل الحالي بحسب المفتاح الأساسي، ثم يحدث بطاقة التفاصيل والعلاقات التابعة.
   */
  function selectRecord(id) {
    const primaryKey = config.primary_key;
    currentRecord = records.find((record) => Number(record[primaryKey]) === Number(id)) || null;
    if (!currentRecord) return;

    agentSearchTerm = '';
    const agentSearch = byId('agentSearchInput');
    if (agentSearch) agentSearch.value = '';

    const emptyState = byId('emptyState');
    const detailsContent = byId('detailsContent');
    if (emptyState) emptyState.style.display = 'none';
    if (detailsContent) detailsContent.classList.add('active');

    const status = currentRecord.status || 'active';
    const avatarField = getCfg('ui.details.avatar_field', primaryKey);
    const titleField = getCfg('ui.details.title_field', primaryKey);

    const avatar = byId('detailsAvatar');
    if (avatar) {
      avatar.textContent = String(currentRecord[avatarField] || '?').trim().charAt(0) || '?';
      avatar.className = 'details-avatar';
      if (status === 'blocked') avatar.classList.add('blocked');
      else if (status === 'active') avatar.classList.add('status-active');
      else avatar.classList.add('status-inactive');
    }

    const detailsName = byId('detailsName');
    if (detailsName) detailsName.textContent = currentRecord[titleField] || '—';

    const statusElement = byId('detailsStatus');
    if (statusElement) {
      statusElement.className = 'status-tag ' + (
        status === 'active' ? 'active' : (status === 'inactive' ? 'inactive' : 'blocked')
      );
      statusElement.innerHTML = `<span></span> ${esc(statusLabel(status))}`;
    }

    // عرض معلومات رأس بطاقة التفاصيل وفق إعدادات الكيان.
    const headerFields = getCfg('ui.details.header_fields', []);
    const headerValues = headerFields.map((headerField) => {
      let value = currentRecord[headerField.field];
      if (headerField.field === 'organization_id') {
        value = lookupName('organization_id', value);
      }

      return `<span><i class="${esc(headerField.icon || '')}"></i> ${esc(value || '—')}</span>`;
    }).join('');

    const detailsId = query('.details-id');
    if (detailsId) detailsId.innerHTML = headerValues;

    // تحديث زر الحظر أو إلغاء الحظر في بطاقة التفاصيل.
    const blockButton = byId('blockBtn');
    if (blockButton) {
      if (status === 'blocked') {
        blockButton.className = 'btn-action unblock';
        blockButton.innerHTML = '<i class="fas fa-unlock"></i><span>إلغاء الحظر</span>';
      } else {
        blockButton.className = 'btn-action block';
        blockButton.innerHTML = '<i class="fas fa-ban"></i><span>حظر</span>';
      }
    }

    // رسم حقول المعلومات التفصيلية.
    const infoFields = getCfg('ui.details.info_fields', []);
    const infoGrid = query('.info-grid');

    if (infoGrid) {
      infoGrid.innerHTML = infoFields.map((field) => {
        let value = currentRecord[field.field];

        if (field.type === 'select' && field.field === 'id_type') {
          value = idTypeLabels[value] || value;
        }

        if (field.type === 'select' && field.field === 'preferred_payment_method') {
          value = paymentLabels[value] || value;
        }

        const isEmpty = value === null || value === undefined || value === '';

        return `
          <div class="info-card">
            <div class="info-card-icon ${esc(field.color || 'blue')}">
              <i class="${esc(field.icon || 'fas fa-circle-info')}"></i>
            </div>
            <div class="info-card-content">
              <div class="info-card-label">${esc(field.label || '')}</div>
              <div class="info-card-value ${isEmpty ? 'empty' : ''}">
                ${esc(isEmpty ? 'غير محدد' : value)}
              </div>
            </div>
          </div>`;
      }).join('');
    }

    // عرض الملاحظات إذا كان الحقل معرفاً في الإعدادات.
    const notesField = getCfg('ui.details.notes_field', '');
    const notesElement = byId('infoNotes');

    if (notesElement && notesField) {
      const notes = currentRecord[notesField];
      notesElement.textContent = notes || 'لا توجد ملاحظات.';
      notesElement.classList.toggle('has-content', !!notes);
    }

    renderChildren();
    renderList();
  }

  /**
   * يحول قيمة مفتاح أجنبي إلى اسم مقروء من lookups أو organizations.
   */
  function lookupName(lookupKey, value) {
    if (value === null || value === undefined || value === '') return '';

    const list = lookups[lookupKey] || (lookupKey === 'organization_id' ? organizations : []);
    const item = list.find((entry) => Number(entry.id) === Number(value));

    return item ? item.name : value;
  }

  /**
   * يخفي بطاقة التفاصيل ويعرض الحالة الفارغة عندما لا يوجد سجل محدد.
   */
  function clearDetails() {
    currentRecord = null;

    const detailsContent = byId('detailsContent');
    const emptyState = byId('emptyState');

    if (detailsContent) detailsContent.classList.remove('active');
    if (emptyState) emptyState.style.display = 'flex';
  }

  /* ============================================================
   * عرض العلاقة التابعة
   * ============================================================ */

  /**
   * يرسم العناصر التابعة للسجل الحالي، مثل وكلاء المالك، وفق إعدادات relations وui.relation.
   */
  function renderChildren() {
    const relationKey = relKey();
    const listElement = byId('agentsList');
    const countElement = byId('agentsCount');

    if (!listElement || !relationKey || !currentRecord) return;

    const relation = config.relations[relationKey];
    const relationItems = Array.isArray(currentRecord[relationKey])
      ? currentRecord[relationKey]
      : [];

    let children = [...relationItems];

    if (agentSearchTerm) {
      const term = agentSearchTerm.toLocaleLowerCase('ar');
      children = children.filter((child) =>
        String(child[relation.name_field] || '').toLocaleLowerCase('ar').includes(term)
        || String(child.phone || '').includes(term)
        || String(child.email || '').toLocaleLowerCase('ar').includes(term)
      );
    }

    if (countElement) countElement.textContent = String(relationItems.length);

    if (relationItems.length === 0) {
      listElement.innerHTML = `
        <div class="agent-empty">
          <i class="${esc(config.sub_section_icon || 'fas fa-list')}"></i>
          لا يوجد ${esc(childEntityLabel())} لهذا ${esc(entitySingular())}
          <br>
          <button type="button" class="btn-add-small" onclick="UF.openChildModal()">
            <i class="fas fa-plus"></i>
            إضافة أول ${esc(childEntityLabel())}
          </button>
        </div>`;
      return;
    }

    if (children.length === 0) {
      listElement.innerHTML = '<div class="agent-empty"><i class="fas fa-search"></i>لا توجد نتائج مطابقة للبحث</div>';
      return;
    }

    const relationUi = getCfg('ui.relation', {});
    const childPrimaryKey = relation.primary_key;

    listElement.innerHTML = children.map((child) => {
      const childId = safeNumericId(child[childPrimaryKey]);
      if (childId === null) return '';

      const status = child.status || 'active';
      const isBlocked = status === 'blocked';
      const name = child[relation.name_field] || '';
      const initial = String(name || '?').trim().charAt(0) || '?';
      const typeField = relationUi.type_field || 'role';
      const typeValue = child[typeField] || '';
      const typeLabel = agentTypeLabels[typeValue] || typeValue;

      return `
        <div class="agent-card ${isBlocked ? 'blocked' : ''}">
          <div class="agent-avatar ${isBlocked ? 'blocked' : ''}">${esc(initial)}</div>
          <div class="agent-info">
            <div class="agent-name">
              ${esc(name)}
              ${isBlocked ? `<span class="blocked-tag">${esc(statusLabel('blocked'))}</span>` : ''}
            </div>
            <div class="agent-meta">
              <span>
                <i class="${esc(relationUi.status_icon || 'fas fa-mobile-screen')}"></i>
                ${esc(child[relationUi.subtitle_field || 'phone'] || '')}
              </span>
              ${typeLabel ? `<span class="agent-type-tag">${esc(typeLabel)}</span>` : ''}
            </div>
          </div>
          <div class="agent-actions">
            <button type="button" class="btn-agent-action view" onclick="UF.viewChild(${childId})" title="عرض">
              <i class="fas fa-eye"></i>
            </button>
            <button type="button" class="btn-agent-action edit" onclick="UF.openChildModal(${childId})" title="تعديل">
              <i class="fas fa-pen"></i>
            </button>
            <button type="button" class="btn-agent-action ${isBlocked ? 'unblock' : 'block'}"
                    onclick="UF.openBlockChildModal(${childId})"
                    title="${isBlocked ? 'إلغاء الحظر' : 'حظر'}">
              <i class="fas fa-${isBlocked ? 'unlock' : 'user-slash'}"></i>
            </button>
            <button type="button" class="btn-agent-action delete"
                    onclick="UF.openDeleteChildModal(${childId})" title="حذف">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>`;
    }).join('');
  }

  /* ============================================================
   * نموذج السجل الرئيسي
   * ============================================================ */

  /**
   * يفتح نموذج إضافة سجل رئيسي أو تعديل سجل موجود ويملأ حقوله من config.main_fields.
   */
  function openRecordModal(id = null) {
    editingId = id;

    const form = byId('ownerForm');
    if (form) form.reset();
    clearValidationErrors('main');

    const titleElement = byId('ownerModalTitle');
    const subtitleElement = byId('ownerModalSubtitle');
    const saveTextElement = byId('saveOwnerText');

    if (id !== null && id !== undefined) {
      const record = records.find((item) => Number(item[config.primary_key]) === Number(id));
      if (!record) return;

      if (titleElement) titleElement.textContent = 'تعديل بيانات ' + entitySingular();
      if (subtitleElement) subtitleElement.textContent = 'قم بتحديث البيانات ثم احفظ التغييرات';
      if (saveTextElement) saveTextElement.textContent = 'حفظ التعديلات';

      (config.main_fields || []).forEach((field) => {
        const element = byId('f_' + field.key);
        if (!element) return;

        if (isFileField(field)) {
          configureFileInput(field, 'f_', record);
          return;
        }

        if (element.type === 'checkbox') element.checked = !!record[field.key];
        else element.value = record[field.key] ?? '';
      });
    } else {
      if (titleElement) titleElement.textContent = 'إضافة ' + entitySingular() + ' جديد';
      if (subtitleElement) subtitleElement.textContent = 'أدخل بيانات ' + entitySingular();
      if (saveTextElement) saveTextElement.textContent = 'حفظ ' + entitySingular();

      (config.main_fields || []).forEach((field) => {
        const element = byId('f_' + field.key);
        if (!element) return;

        if (isFileField(field)) {
          configureFileInput(field, 'f_', {});
          return;
        }

        if (field.default === undefined) return;
        if (element.type === 'checkbox') element.checked = !!field.default;
        else element.value = field.default;
      });
    }

    openModal('ownerModal');
  }

  /**
   * يجمع قيم حقول النموذج ويتحقق من الحقول المطلوبة، ثم يرسل البيانات إلى saveRecord في PHP.
   */
  async function saveRecord() {
    const data = {};
    let valid = true;
    const editingRecord = editingId !== null && editingId !== undefined
      ? records.find((item) => Number(item[config.primary_key]) === Number(editingId)) || {}
      : {};

    clearValidationErrors('main');

    (config.main_fields || []).forEach((field) => {
      const element = byId('f_' + field.key);
      if (!element) return;

      if (isFileField(field)) {
        const hasNewFile = !!element.files?.length;
        const hasOldFile = !!editingRecord[field.key];
        if (field.required && !hasNewFile && !hasOldFile) {
          valid = false;
          element.classList.add('is-invalid');
          showValidationErrors({ ['f_' + field.key]: [`يرجى اختيار ملف في حقل ${field.label || field.key}.`] });
        }
        return; // الملف يرفع عبر Livewire upload API ولا يرسل كنص ضمن data.
      }

      const value = readFieldValue(element);
      if (field.required && (value === '' || value === null || value === undefined)) {
        valid = false;
        element.classList.add('is-invalid');
        showValidationErrors({ ['f_' + field.key]: [`حقل ${field.label || field.key} مطلوب.`] });
      } else {
        element.classList.remove('is-invalid');
      }

      data[field.key] = value;
    });

    if (!valid) {
      showToast('يرجى مراجعة الحقول المطلوبة.', 'error');
      return false;
    }

    if (LIVE_MODE) {
      const uploaded = await uploadConfiguredFiles(
        config.main_fields || [],
        'f_',
        'mainUploads',
        editingRecord
      );
      if (!uploaded) return false;

      const result = await callLivewire('saveRecord', data, editingId);
      if (result !== false) closeModal('ownerModal');
      return result !== false;
    }

    // وضع العرض التجريبي؛ لا يستخدم لرفع الملفات أو حفظ البيانات الفعلية.
    if ((config.main_fields || []).some(isFileField)) {
      showToast('رفع الملفات يحتاج إلى اتصال Livewire فعلي.', 'error');
      return false;
    }

    if (editingId !== null && editingId !== undefined) {
      const record = records.find((item) => Number(item[config.primary_key]) === Number(editingId));
      if (record) Object.assign(record, data);
    } else {
      const newId = Math.max(0, ...records.map((item) => Number(item[config.primary_key]) || 0)) + 1;
      records.unshift({
        [config.primary_key]: newId,
        ...data,
        status: 'active',
        created_at: new Date().toISOString().slice(0, 10)
      });
    }

    closeModal('ownerModal');
    updateStats();
    renderList();
    showToast('تم الحفظ بنجاح.', 'success');
    return true;
  }

  /**
   * يفتح نافذة تأكيد حذف السجل الرئيسي ويعرض اسم السجل المراد حذفه.
   */
  function openDeleteModal(id = null) {
    deletingId = id || (currentRecord && currentRecord[config.primary_key]);
    if (!deletingId) return;

    const record = records.find((item) => Number(item[config.primary_key]) === Number(deletingId));
    if (!record) return;

    const nameElement = byId('deleteOwnerName');
    if (nameElement) nameElement.textContent = record[config.name_field] || '';

    openModal('deleteOwnerModal');
  }

  /**
   * ينفذ حذف السجل الرئيسي في Livewire أو يحذفه محلياً في الوضع التجريبي.
   */
  async function confirmDelete() {
    if (!deletingId) return false;

    if (LIVE_MODE) {
      const result = await callLivewire('deleteRecord', deletingId);
      if (result !== false) closeModal('deleteOwnerModal');
      return result !== false;
    }

    records = records.filter((record) => Number(record[config.primary_key]) !== Number(deletingId));

    if (currentRecord && Number(currentRecord[config.primary_key]) === Number(deletingId)) {
      clearDetails();
    }

    closeModal('deleteOwnerModal');
    updateStats();
    renderList();
    showToast('تم الحذف ✓', 'success');
    return true;
  }

  /**
   * يفتح نافذة تأكيد حظر السجل الرئيسي أو إلغاء حظره ويجهز النصوص والأيقونات المناسبة.
   */
  function openBlockModal(id = null) {
    blockingId = id || (currentRecord && currentRecord[config.primary_key]);
    if (!blockingId) return;

    const record = records.find((item) => Number(item[config.primary_key]) === Number(blockingId));
    if (!record) return;

    blockAction = record.status === 'blocked' ? 'unblock' : 'block';
    const isUnblock = blockAction === 'unblock';
    const name = record[config.name_field] || '';

    const title = byId('blockModalTitle');
    const subtitle = byId('blockModalSubtitle');
    const confirmTitle = byId('blockConfirmTitle');
    const confirmText = byId('blockConfirmText');
    const confirmIcon = byId('blockConfirmIcon');
    const confirmButton = byId('blockConfirmBtn');
    const modalIcon = byId('blockModalIcon');

    if (title) title.textContent = isUnblock ? 'إلغاء حظر ' + entitySingular() : 'حظر ' + entitySingular();
    if (subtitle) subtitle.textContent = isUnblock ? 'سيتم إعادة الوصول' : 'سيتم منع الوصول';
    if (confirmTitle) confirmTitle.textContent = isUnblock ? 'إلغاء الحظر؟' : 'حظر ' + entitySingular() + '؟';
    if (confirmText) {
      confirmText.innerHTML = isUnblock
        ? `سيتم إلغاء حظر <strong>${esc(name)}</strong>.`
        : `سيتم حظر <strong>${esc(name)}</strong>.`;
    }
    if (confirmIcon) {
      confirmIcon.className = 'confirm-icon ' + (isUnblock ? 'success' : 'warning');
      confirmIcon.innerHTML = `<i class="fas fa-${isUnblock ? 'unlock' : 'ban'}"></i>`;
    }
    if (confirmButton) {
      confirmButton.className = 'btn ' + (isUnblock ? 'btn-success' : 'btn-warning');
      confirmButton.innerHTML = `<i class="fas fa-${isUnblock ? 'unlock' : 'ban'}"></i><span>${isUnblock ? 'إلغاء الحظر' : 'حظر'}</span>`;
    }
    if (modalIcon) {
      modalIcon.style.background = isUnblock
        ? 'linear-gradient(135deg, var(--emerald), #10B981)'
        : 'linear-gradient(135deg, var(--terracotta), #F59E0B)';
      modalIcon.innerHTML = `<i class="fas fa-${isUnblock ? 'unlock' : 'ban'}"></i>`;
    }

    openModal('blockOwnerModal');
  }

  /**
   * يرسل طلب تغيير حالة السجل إلى toggleRecordStatus في Livewire أو يغيره محلياً في الوضع التجريبي.
   */
  async function confirmBlock() {
    if (!blockingId) return false;

    if (LIVE_MODE) {
      const result = await callLivewire('toggleRecordStatus', blockingId);
      if (result !== false) closeModal('blockOwnerModal');
      return result !== false;
    }

    const record = records.find((item) => Number(item[config.primary_key]) === Number(blockingId));
    if (record) record.status = blockAction === 'unblock' ? 'active' : 'blocked';

    closeModal('blockOwnerModal');
    updateStats();
    renderList();

    if (currentRecord && Number(currentRecord[config.primary_key]) === Number(blockingId)) {
      selectRecord(blockingId);
    }

    showToast('تم التحديث ✓', 'success');
    return true;
  }

  /* ============================================================
   * نماذج العناصر التابعة
   * ============================================================ */

  /**
   * يفتح نموذج إضافة عنصر تابع أو تعديل عنصر تابع محدد داخل السجل الرئيسي الحالي.
   */
  function openChildModal(childId = null) {
    if (!currentRecord) {
      showToast('يرجى اختيار ' + entitySingular() + ' أولاً', 'error');
      return;
    }

    const relationKey = relKey();
    if (!relationKey) return;

    const relation = config.relations[relationKey];
    editingChildId = childId;

    const form = byId('agentForm');
    if (form) form.reset();
    clearValidationErrors('child');

    const titleElement = byId('agentModalTitle');
    const subtitleElement = byId('agentModalSubtitle');
    const saveTextElement = byId('saveAgentText');

    if (childId !== null && childId !== undefined) {
      const child = (currentRecord[relationKey] || []).find(
        (item) => Number(item[relation.primary_key]) === Number(childId)
      );
      if (!child) return;

      if (titleElement) titleElement.textContent = 'تعديل بيانات ' + childEntityLabel();
      if (subtitleElement) subtitleElement.textContent = 'قم بتحديث البيانات';
      if (saveTextElement) saveTextElement.textContent = 'حفظ التعديلات';

      (relation.fields || []).forEach((field) => {
        const element = byId('a_' + field.key);
        if (!element) return;

        if (isFileField(field)) {
          configureFileInput(field, 'a_', child);
          return;
        }

        if (element.type === 'checkbox') element.checked = !!child[field.key];
        else element.value = child[field.key] ?? '';
      });
    } else {
      if (titleElement) titleElement.textContent = 'إضافة ' + childEntityLabel();
      if (subtitleElement) subtitleElement.textContent = 'أضف ' + childEntityLabel() + ' للـ' + entitySingular();
      if (saveTextElement) saveTextElement.textContent = 'إضافة ' + childEntityLabel();

      (relation.fields || []).forEach((field) => {
        const element = byId('a_' + field.key);
        if (!element) return;

        if (isFileField(field)) {
          configureFileInput(field, 'a_', {});
          return;
        }

        if (field.default === undefined) return;
        if (element.type === 'checkbox') element.checked = !!field.default;
        else element.value = field.default;
      });
    }

    openModal('agentModal');
  }

  /**
   * يجمع حقول العنصر التابع ويتحقق منها ثم يستدعي saveChild في PHP مع معرف السجل الأب.
   */
  async function saveChild() {
    if (!currentRecord) return false;

    const relationKey = relKey();
    if (!relationKey) return false;

    const relation = config.relations[relationKey];
    const data = {};
    let valid = true;
    const editingChild = editingChildId !== null && editingChildId !== undefined
      ? (currentRecord[relationKey] || []).find((item) => Number(item[relation.primary_key]) === Number(editingChildId)) || {}
      : {};

    clearValidationErrors('child');

    (relation.fields || []).forEach((field) => {
      const element = byId('a_' + field.key);
      if (!element) return;

      if (isFileField(field)) {
        const hasNewFile = !!element.files?.length;
        const hasOldFile = !!editingChild[field.key];
        if (field.required && !hasNewFile && !hasOldFile) {
          valid = false;
          element.classList.add('is-invalid');
          showValidationErrors({ ['a_' + field.key]: [`يرجى اختيار ملف في حقل ${field.label || field.key}.`] });
        }
        return;
      }

      const value = readFieldValue(element);
      if (field.required && (value === '' || value === null || value === undefined)) {
        valid = false;
        element.classList.add('is-invalid');
        showValidationErrors({ ['a_' + field.key]: [`حقل ${field.label || field.key} مطلوب.`] });
      } else {
        element.classList.remove('is-invalid');
      }

      data[field.key] = value;
    });

    if (!valid) {
      showToast('يرجى مراجعة الحقول المطلوبة.', 'error');
      return false;
    }

    const parentId = currentRecord[config.primary_key];

    if (LIVE_MODE) {
      const uploaded = await uploadConfiguredFiles(
        relation.fields || [],
        'a_',
        'childUploads',
        editingChild
      );
      if (!uploaded) return false;

      const result = await callLivewire('saveChild', parentId, data, editingChildId);
      if (result !== false) closeModal('agentModal');
      return result !== false;
    }

    if ((relation.fields || []).some(isFileField)) {
      showToast('رفع الملفات يحتاج إلى اتصال Livewire فعلي.', 'error');
      return false;
    }

    if (editingChildId !== null && editingChildId !== undefined) {
      const child = (currentRecord[relationKey] || []).find(
        (item) => Number(item[relation.primary_key]) === Number(editingChildId)
      );
      if (child) Object.assign(child, data);
    } else {
      if (!Array.isArray(currentRecord[relationKey])) currentRecord[relationKey] = [];
      const newId = Math.max(
        0,
        ...currentRecord[relationKey].map((item) => Number(item[relation.primary_key]) || 0)
      ) + 1;

      currentRecord[relationKey].push({
        [relation.primary_key]: newId,
        ...data,
        status: 'active'
      });
    }

    closeModal('agentModal');
    renderChildren();
    updateStats();
    showToast('تم الحفظ بنجاح.', 'success');
    return true;
  }

  /**
   * يعرض تفاصيل عنصر تابع في نافذة مستقلة، مع تحويل الحقول ذات الخيارات إلى تسمياتها.
   */
  function viewChild(childId) {
    const relationKey = relKey();
    if (!relationKey || !currentRecord) return;

    const relation = config.relations[relationKey];
    const child = (currentRecord[relationKey] || []).find(
      (item) => Number(item[relation.primary_key]) === Number(childId)
    );
    if (!child) return;

    viewingChildId = childId;

    const rows = (relation.fields || []).map((field) => {
      let value = child[field.key];
      if (field.key === 'role') value = agentTypeLabels[value] || value;

      return `
        <div class="agent-detail-row">
          <div class="agent-detail-icon">
            <i class="${esc(field.icon || 'fas fa-circle-info')}"></i>
          </div>
          <div class="agent-detail-content">
            <div class="agent-detail-label">${esc(field.label || '')}</div>
            <div class="agent-detail-value">${esc(value || 'غير محدد')}</div>
          </div>
        </div>`;
    }).join('');

    const body = byId('agentViewBody');
    if (body) body.innerHTML = rows;

    openModal('agentViewModal');
  }

  /**
   * يغلق نافذة عرض العنصر التابع ثم يفتح نموذج التعديل للعنصر الذي كان معروضاً.
   */
  function editChildFromView() {
    closeModal('agentViewModal');
    if (viewingChildId !== null && viewingChildId !== undefined) {
      window.setTimeout(() => openChildModal(viewingChildId), 200);
    }
  }

  /**
   * يفتح نافذة تأكيد حظر عنصر تابع أو إلغاء حظره ويملأ بيانات الاسم والرسائل والأزرار.
   */
  function openBlockChildModal(childId) {
    const relationKey = relKey();
    if (!relationKey || !currentRecord) return;

    const relation = config.relations[relationKey];
    const child = (currentRecord[relationKey] || []).find(
      (item) => Number(item[relation.primary_key]) === Number(childId)
    );
    if (!child) return;

    blockingChildId = childId;
    blockChildAction = child.status === 'blocked' ? 'unblock' : 'block';

    const isUnblock = blockChildAction === 'unblock';
    const name = child[relation.name_field] || '';

    const title = byId('blockAgentTitle');
    const subtitle = byId('blockAgentSubtitle');
    const confirmTitle = byId('blockAgentConfirmTitle');
    const confirmText = byId('blockAgentConfirmText');
    const confirmIcon = byId('blockAgentConfirmIcon');
    const confirmButton = byId('blockAgentConfirmBtn');

    if (title) title.textContent = isUnblock ? 'إلغاء حظر ' + childEntityLabel() : 'حظر ' + childEntityLabel();
    if (subtitle) subtitle.textContent = isUnblock ? 'سيتم إعادة التنشيط' : 'سيتم منع ' + childEntityLabel();
    if (confirmTitle) confirmTitle.textContent = isUnblock ? 'إلغاء الحظر؟' : 'حظر ' + childEntityLabel() + '؟';
    if (confirmText) {
      confirmText.innerHTML = isUnblock
        ? `سيتم إلغاء حظر <strong>${esc(name)}</strong>.`
        : `سيتم حظر <strong>${esc(name)}</strong>.`;
    }
    if (confirmIcon) {
      confirmIcon.className = 'confirm-icon ' + (isUnblock ? 'success' : 'warning');
      confirmIcon.innerHTML = `<i class="fas fa-${isUnblock ? 'unlock' : 'user-slash'}"></i>`;
    }
    if (confirmButton) {
      confirmButton.className = 'btn ' + (isUnblock ? 'btn-success' : 'btn-warning');
      confirmButton.innerHTML = `<i class="fas fa-${isUnblock ? 'unlock' : 'user-slash'}"></i><span>${isUnblock ? 'إلغاء الحظر' : 'حظر'}</span>`;
    }

    openModal('blockAgentModal');
  }

  /**
   * يرسل طلب تبديل حالة العنصر التابع إلى toggleChildStatus في PHP أو يحدثه محلياً في الوضع التجريبي.
   */
  async function confirmBlockChild() {
    if (!currentRecord || !blockingChildId) return false;

    const relationKey = relKey();
    if (!relationKey) return false;

    if (LIVE_MODE) {
      const result = await callLivewire(
        'toggleChildStatus',
        currentRecord[config.primary_key],
        blockingChildId
      );

      if (result !== false) closeModal('blockAgentModal');
      return result !== false;
    }

    const relation = config.relations[relationKey];
    const child = (currentRecord[relationKey] || []).find(
      (item) => Number(item[relation.primary_key]) === Number(blockingChildId)
    );
    if (child) child.status = blockChildAction === 'unblock' ? 'active' : 'blocked';

    closeModal('blockAgentModal');
    renderChildren();
    showToast('تم التحديث ✓', 'success');
    return true;
  }

  /**
   * يفتح نافذة تأكيد حذف عنصر تابع ويعرض اسمه قبل تنفيذ عملية الحذف.
   */
  function openDeleteChildModal(childId) {
    const relationKey = relKey();
    if (!relationKey || !currentRecord) return;

    const relation = config.relations[relationKey];
    const child = (currentRecord[relationKey] || []).find(
      (item) => Number(item[relation.primary_key]) === Number(childId)
    );
    if (!child) return;

    deletingChildId = childId;
    const nameElement = byId('deleteAgentName');
    if (nameElement) nameElement.textContent = child[relation.name_field] || '';

    openModal('deleteAgentModal');
  }

  /**
   * يحذف العنصر التابع من خلال deleteChild في Livewire أو يحذفه من المصفوفة محلياً في الوضع التجريبي.
   */
  async function confirmDeleteChild() {
    if (!currentRecord || !deletingChildId) return false;

    const relationKey = relKey();
    if (!relationKey) return false;

    if (LIVE_MODE) {
      const result = await callLivewire(
        'deleteChild',
        currentRecord[config.primary_key],
        deletingChildId
      );

      if (result !== false) closeModal('deleteAgentModal');
      return result !== false;
    }

    const relation = config.relations[relationKey];
    currentRecord[relationKey] = (currentRecord[relationKey] || []).filter(
      (child) => Number(child[relation.primary_key]) !== Number(deletingChildId)
    );

    closeModal('deleteAgentModal');
    renderChildren();
    updateStats();
    showToast('تم الحذف ✓', 'success');
    return true;
  }

  /* ============================================================
   * النوافذ المنبثقة والإشعارات
   * ============================================================ */

  /**
   * يفتح النافذة المنبثقة المحددة بإضافة class show ويمنع تمرير الصفحة خلفها.
   */
  function openModal(id) {
    const element = byId(id);
    if (!element) return;

    element.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  /**
   * يغلق النافذة المنبثقة المحددة ويعيد تمرير الصفحة.
   */
  function closeModal(id) {
    const element = byId(id);
    if (!element) return;

    element.classList.remove('show');

    if (!query('.modal-overlay.show')) {
      document.body.style.overflow = '';
    }
  }

  /**
   * يعرض إشعار نجاح أو خطأ في عنصر toast الموجود في Blade، ويخفيه تلقائياً بعد ثلاث ثوانٍ.
   */
  function showToast(message, type = 'success') {
    window.clearTimeout(toastTimer);

    const toast = byId('toast');
    const messageElement = byId('toastMsg');
    if (!toast || !messageElement) {
      if (type === 'error') logError(message);
      return;
    }

    toast.className = 'toast ' + type;
    messageElement.textContent = message;

    const icon = toast.querySelector('i');
    if (icon) {
      icon.className = type === 'success'
        ? 'fas fa-circle-check'
        : 'fas fa-circle-exclamation';
    }

    toast.classList.add('show');
    toastTimer = window.setTimeout(() => toast.classList.remove('show'), 3000);
  }

  /* ============================================================
   * تهيئة وربط أحداث الواجهة
   * ============================================================ */

  /**
   * يربط أزرار الصفحة والنماذج والنوافذ المنبثقة مرة واحدة بعد وصول البيانات الأولية.
   */
  function bindInterfaceEvents() {
    if (boundRoot === UF_ROOT && initialized) return;
    boundRoot = UF_ROOT;

    const addMain = byId('btnAddMain');
    if (addMain) addMain.onclick = () => openRecordModal();

    const addChildButton = byId('btnAddSub');
    if (addChildButton) addChildButton.onclick = () => openChildModal();

    const detailsAddChild = byId('btnDetailsAddSub');
    if (detailsAddChild) detailsAddChild.onclick = () => openChildModal();

    const detailsEdit = byId('btnDetailsEdit');
    if (detailsEdit) {
      detailsEdit.onclick = () => openRecordModal(
        currentRecord && currentRecord[config.primary_key]
      );
    }

    const detailsBlock = byId('blockBtn');
    if (detailsBlock) detailsBlock.onclick = () => openBlockModal();

    const detailsDelete = byId('btnDetailsDelete');
    if (detailsDelete) detailsDelete.onclick = () => openDeleteModal();

    byId('btnSaveOwner')?.addEventListener('click', saveRecord);
    byId('btnSaveAgent')?.addEventListener('click', saveChild);
    byId('btnConfirmDeleteOwner')?.addEventListener('click', confirmDelete);
    byId('btnConfirmDeleteAgent')?.addEventListener('click', confirmDeleteChild);
    byId('btnConfirmBlockOwner')?.addEventListener('click', confirmBlock);
    byId('btnConfirmBlockAgent')?.addEventListener('click', confirmBlockChild);
    byId('btnEditAgentFromView')?.addEventListener('click', editChildFromView);

    initFilters();

    queryAll('.modal-overlay').forEach((overlay) => {
      if (overlay.dataset.ufOverlayBound === '1') return;

      overlay.addEventListener('click', (event) => {
        if (event.target === overlay) closeModal(overlay.id);
      });

      overlay.dataset.ufOverlayBound = '1';
    });

    if (!document.documentElement.dataset.ufEscapeBound) {
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          queryAll('.modal-overlay.show').forEach((modal) => closeModal(modal.id));
        }
      });

      document.documentElement.dataset.ufEscapeBound = '1';
    }
  }

  /**
   * يرسم الإحصائيات والقائمة، ثم يعيد تحديد السجل السابق أو أول سجل متاح بعد تحديث البيانات.
   */
  function refreshInterface(data = {}) {
    const previousId = currentRecord ? currentRecord[config.primary_key] : null;
    const hasKeepRecord = Object.prototype.hasOwnProperty.call(data, 'keepRecord')
      || Object.prototype.hasOwnProperty.call(data, 'keepOwner');
    const keepId = hasKeepRecord
      ? (data.keepRecord ?? data.keepOwner)
      : previousId;

    updateStats();
    renderList();

    if (keepId !== null && keepId !== undefined) {
      const found = records.find(
        (record) => Number(record[config.primary_key]) === Number(keepId)
      );

      if (found) selectRecord(found[config.primary_key]);
      else if (records.length > 0) selectRecord(records[0][config.primary_key]);
      else clearDetails();
    } else if (records.length > 0) {
      selectRecord(records[0][config.primary_key]);
    } else {
      clearDetails();
    }

    // بعد إتمام العملية، أغلق النوافذ المنبثقة المفتوحة.
    queryAll('.modal-overlay.show').forEach((modal) => closeModal(modal.id));
    document.body.style.overflow = '';

    if (data.message) showToast(data.message, 'success');
  }

  /**
   * يهيئ الواجهة لأول مرة بعد استلام config والسجلات، ويربط جميع عناصر التفاعل.
   */
  function initializeInterface() {
    if (!config || Object.keys(config).length === 0) return;

    bindInterfaceEvents();
    initialized = true;

    updateStats();
    renderList();

    if (records.length > 0) {
      selectRecord(records[0][config.primary_key]);
    } else {
      clearDetails();
    }
  }

  /**
   * نقطة التشغيل العامة؛ تسجل مستمعي الأحداث وتدعم بيانات window.UF_DATA القديمة عند وجودها.
   */
  function init() {
    setupLivewireBridge();

    // استخدم مرجع Livewire الذي مرره Blade، مع السماح بالعمل مع window.UF_DATA القديمة.
    UF_WIRE = window.__UF_WIRE || UF_WIRE || null;
    UF_ROOT = window.__UF_ROOT || (UF_WIRE && UF_WIRE.$el) || document.querySelector('[data-uf-app]') || document;

    if (window.UF_DATA && Object.keys(window.UF_DATA).length > 0) {
      receiveUniversalFormData(window.UF_DATA, {
        root: UF_ROOT,
        wire: UF_WIRE
      }, true);
    } else {
      logError('لم تصل window.UF_DATA من Blade. تأكد من إرسال initialData قبل تضمين scripts.js.');
    }
  }

  /* ============================================================
   * واجهة JavaScript العامة
   * ============================================================ */

  // نكشف الدوال التي تستدعيها أزرار HTML المرسومة في القائمة أو الموجودة في Blade.
  window.UF = {
    init,
    receiveData: receiveUniversalFormData,
    selectRecord,
    openRecordModal,
    saveRecord,
    openDeleteModal,
    confirmDelete,
    openBlockModal,
    confirmBlock,
    openChildModal,
    saveChild,
    viewChild,
    editChildFromView,
    openBlockChildModal,
    confirmBlockChild,
    openDeleteChildModal,
    confirmDeleteChild,
    clearFilters,
    openModal,
    closeModal,
    showToast
  };

  // سجّل مستمعي الأحداث فور تحميل الملف حتى لا يفوت حدث bootstrap.
  setupLivewireBridge();

  // شغّل النسخة القديمة فقط عند توفر بياناتها، وإلا انتظر حدث uf:bootstrap.
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();