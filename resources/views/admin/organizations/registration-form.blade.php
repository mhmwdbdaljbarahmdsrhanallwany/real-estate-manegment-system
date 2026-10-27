
<div class="card">

    {{-- رأس بطاقة التحكم --}}
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-alt mr-2"></i>
            استمارة تسجيل مؤسسة عقارية
        </h3>

        <button type="button"
        onclick="window.print()"
        class="btn btn-secondary btn-sm">
    <i class="fas fa-print mr-1"></i>
    طباعة الاستمارة
</button>

    <div class="card-body">

        {{-- =========================
             ورقة الاستمارة
        ========================== --}}
        <div class="registration-paper" dir="rtl">

            {{-- =========================
                 رأس الاستمارة
            ========================== --}}
            <div class="registration-header">

                <div class="registration-brand">

                    {{-- الشعار في اليمين --}}
                    <div class="registration-logo-wrapper">
                        <img src="{{ asset('assets/logo/favicon.png') }}"
                             alt="SmarteState"
                             class="registration-logo">
                    </div>

                    {{-- اسم النظام بجانب الشعار --}}
                    <div class="registration-brand-text">

                        <div class="registration-system-name">
                            SmarteState
                        </div>

                        <div class="registration-system-description">
                            منصة إدارة المؤسسات العقارية
                        </div>

                    </div>

                </div>

                {{-- عنوان الاستمارة --}}
                <div class="registration-title">
                    استمارة تسجيل مؤسسة عقارية
                </div>

                <div class="registration-subtitle">
                    نموذج تسجيل بيانات المؤسسة واعتمادها في النظام
                </div>

            </div>


            {{-- =========================
                 بيانات الاستمارة
            ========================== --}}
            <div class="registration-meta">

                <div class="meta-item">
                    <span class="meta-label">
                        رقم الاستمارة
                    </span>

                    <span class="meta-line"></span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">
                        التاريخ
                    </span>

                    <span class="meta-line"></span>
                </div>

            </div>


            {{-- =========================
                 1. البيانات الأساسية
            ========================== --}}
            <div class="registration-section">

                <div class="registration-section-title">
                    <i class="fas fa-building"></i>
                    البيانات الأساسية للمؤسسة
                </div>

                <div class="registration-grid">

                    <div class="registration-field field-full">
                        <span class="field-label">
                            اسم المؤسسة
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            الاسم التجاري
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            رقم السجل التجاري
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            الرقم الضريبي
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            الرقم الوطني الموحد
                        </span>
                        <span class="field-line"></span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 2. بيانات التواصل
            ========================== --}}
            <div class="registration-section">

                <div class="registration-section-title">
                    <i class="fas fa-address-book"></i>
                    بيانات التواصل
                </div>

                <div class="registration-grid">

                    <div class="registration-field">
                        <span class="field-label">
                            رقم الهاتف
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            رقم الجوال
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            البريد الإلكتروني
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            الموقع الإلكتروني
                        </span>
                        <span class="field-line"></span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 3. العنوان والموقع
            ========================== --}}
            <div class="registration-section">

                <div class="registration-section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    العنوان والموقع
                </div>

                <div class="registration-grid">

                    <div class="registration-field">
                        <span class="field-label">
                            الدولة
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            المحافظة / المنطقة
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            المدينة
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            الحي
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            الشارع
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            الرمز البريدي
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            صندوق البريد
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field field-full">
                        <span class="field-label">
                            العنوان التفصيلي
                        </span>
                        <span class="field-line"></span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 4. الممثل القانوني
            ========================== --}}
            <div class="registration-section">

                <div class="registration-section-title">
                    <i class="fas fa-user-tie"></i>
                    بيانات الممثل القانوني
                </div>

                <div class="registration-grid">

                    <div class="registration-field">
                        <span class="field-label">
                            اسم الممثل القانوني
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            رقم الهوية
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            رقم الجوال
                        </span>
                        <span class="field-line"></span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 5. البيانات البنكية
            ========================== --}}
            <div class="registration-section">

                <div class="registration-section-title">
                    <i class="fas fa-university"></i>
                    البيانات البنكية
                </div>

                <div class="registration-grid">

                    <div class="registration-field">
                        <span class="field-label">
                            اسم البنك
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            اسم صاحب الحساب
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            رقم الحساب
                            <b>*</b>
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            رقم الآيبان
                        </span>
                        <span class="field-line"></span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 6. المستندات المطلوبة
            ========================== --}}
            <div class="registration-section">

                <div class="registration-section-title">
                    <i class="fas fa-file-contract"></i>
                    المستندات المطلوبة
                </div>

                <div class="documents-list">

                    <div class="document-item">
                        <span class="document-checkbox"></span>
                        <span>
                            صورة رخصة البلدية
                        </span>
                    </div>

                    <div class="document-item">
                        <span class="document-checkbox"></span>
                        <span>
                            صورة رخصة الوساطة العقارية
                        </span>
                    </div>

                    <div class="document-item">
                        <span class="document-checkbox"></span>
                        <span>
                            مستندات داعمة أخرى إن وجدت
                        </span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 7. إقرار ممثل المؤسسة
            ========================== --}}
            <div class="registration-section">

                <div class="registration-section-title">
                    <i class="fas fa-file-signature"></i>
                    إقرار ممثل المؤسسة
                </div>

                <div class="declaration-box">

                    أقر أنا ممثل المؤسسة بأن البيانات والمعلومات المقدمة
                    في هذه الاستمارة صحيحة وكاملة، وأتحمل المسؤولية عن
                    صحة المستندات والبيانات المقدمة لغرض تسجيل المؤسسة
                    في نظام SmarteState.

                </div>

                <div class="signature-grid">

                    <div class="signature-item">
                        <span>اسم ممثل المؤسسة</span>
                        <span class="signature-line"></span>
                    </div>

                    <div class="signature-item">
                        <span>التوقيع</span>
                        <span class="signature-line"></span>
                    </div>

                    <div class="signature-item">
                        <span>التاريخ</span>
                        <span class="signature-line"></span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 8. خاص بالموظف
            ========================== --}}
            <div class="employee-section">

                <div class="employee-section-title">
                    خاص بالموظف المختص
                </div>

                <div class="registration-grid">

                    <div class="registration-field">
                        <span class="field-label">
                            اسم الموظف
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            تاريخ الإدخال
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            حالة التسجيل
                        </span>
                        <span class="field-line"></span>
                    </div>

                    <div class="registration-field">
                        <span class="field-label">
                            ملاحظات
                        </span>
                        <span class="field-line"></span>
                    </div>

                </div>

            </div>


            {{-- =========================
                 Footer
            ========================== --}}
            <div class="registration-footer">

                <span>
                    SmarteState
                </span>

                <span>
                    منصة إدارة المؤسسات العقارية
                </span>

            </div>

        </div>

    </div>

</div>


<style>

    /* =========================================
       الهوية العامة
    ========================================= */

    .registration-paper {
        width: 210mm;
        min-height: 297mm;

        margin: 0 auto;
        padding: 14mm;

        background: #FFFFFF;

        color: #0A192F;

        border: 1px solid #0A192F;

        box-shadow: 0 2px 10px rgba(10, 25, 47, 0.10);

        font-family: "Tahoma", "Arial", sans-serif;
        font-size: 13px;

        box-sizing: border-box;
    }


    /* =========================================
       رأس الاستمارة
    ========================================= */

    .registration-header {
        border-bottom: 3px solid #00B4D8;

        padding-bottom: 12px;
        margin-bottom: 14px;
    }


    /*
     * الشعار في اليمين
     * واسم النظام بجانبه
     */

    .registration-brand {
        display: flex;

        align-items: center;

        justify-content: flex-start;

        direction: rtl;

        width: 100%;
    }


    .registration-logo-wrapper {
        width: 68px;
        height: 68px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-left: 12px;
    }


    .registration-logo {
        width: 64px;
        height: 64px;

        object-fit: contain;

        display: block;
    }


    .registration-brand-text {
        text-align: right;
    }


    .registration-system-name {
        color: #0A192F;

        font-size: 25px;

        font-weight: 700;

        line-height: 1.2;
    }


    .registration-system-description {
        color: #495057;

        font-size: 12px;

        margin-top: 4px;
    }


    .registration-title {
        color: #0A192F;

        text-align: center;

        font-size: 20px;

        font-weight: 700;

        margin-top: 13px;
    }


    .registration-subtitle {
        text-align: center;

        color: #6c757d;

        font-size: 11px;

        margin-top: 4px;
    }


    /* =========================================
       بيانات الاستمارة
    ========================================= */

    .registration-meta {
        display: flex;

        justify-content: space-between;

        gap: 30px;

        margin-bottom: 15px;
    }


    .meta-item {
        width: 50%;

        display: flex;

        align-items: center;

        gap: 8px;
    }


    .meta-label {
        font-weight: 700;

        white-space: nowrap;

        color: #0A192F;
    }


    .meta-line {
        flex: 1;

        height: 20px;

        border-bottom: 1px solid #0A192F;
    }


    /* =========================================
       الأقسام
    ========================================= */

    .registration-section {
        margin-bottom: 13px;
    }


    .registration-section-title {
        display: flex;

        align-items: center;

        gap: 7px;

        color: #0A192F;

        font-size: 14px;

        font-weight: 700;

        border-right: 4px solid #00B4D8;

        border-bottom: 1px solid #0A192F;

        padding: 6px 9px;

        margin-bottom: 9px;

        background: #FFFFFF;
    }


    .registration-section-title i {
        color: #00B4D8;

        width: 18px;

        text-align: center;
    }


    /* =========================================
       شبكة الحقول
    ========================================= */

    .registration-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 8px 14px;
    }


    .registration-field {
        display: flex;

        align-items: flex-end;

        min-height: 30px;
    }


    .field-full {
        grid-column: 1 / -1;
    }


    .field-label {
        white-space: nowrap;

        font-weight: 600;

        color: #0A192F;

        margin-left: 8px;
    }


    .field-label b {
        color: #00B4D8;

        font-weight: 700;
    }


    .field-line {
        flex: 1;

        height: 21px;

        border-bottom: 1px solid #6c757d;
    }


    /* =========================================
       المستندات
    ========================================= */

    .documents-list {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 8px 20px;
    }


    .document-item {
        display: flex;

        align-items: center;

        gap: 8px;

        min-height: 27px;

        color: #0A192F;
    }


    .document-checkbox {
        width: 16px;
        height: 16px;

        border: 1px solid #0A192F;

        display: inline-block;

        flex-shrink: 0;
    }


    /* =========================================
       الإقرار
    ========================================= */

    .declaration-box {
        border: 1px solid #0A192F;

        padding: 10px 12px;

        line-height: 1.8;

        text-align: justify;

        margin-bottom: 12px;
    }


    .signature-grid {
        display: grid;

        grid-template-columns: repeat(3, 1fr);

        gap: 15px;
    }


    .signature-item {
        display: flex;

        flex-direction: column;

        gap: 7px;

        font-weight: 600;

        color: #0A192F;
    }


    .signature-line {
        height: 25px;

        border-bottom: 1px solid #0A192F;
    }


    /* =========================================
       قسم الموظف
    ========================================= */

    .employee-section {
        border: 1px solid #0A192F;

        padding: 10px;

        margin-top: 15px;
    }


    .employee-section-title {
        color: #0A192F;

        font-weight: 700;

        font-size: 13px;

        border-bottom: 2px solid #00B4D8;

        padding-bottom: 5px;

        margin-bottom: 9px;
    }


    /* =========================================
       Footer
    ========================================= */

    .registration-footer {
        display: flex;

        justify-content: space-between;

        border-top: 1px solid #0A192F;

        margin-top: 15px;

        padding-top: 7px;

        color: #0A192F;

        font-size: 10px;

        font-weight: 600;
    }


    .registration-footer span:first-child {
        color: #00B4D8;

        font-weight: 700;
    }


    /* =========================================
       الطباعة
    ========================================= */

    @media print {

        body * {
            visibility: hidden;
        }


        .registration-paper,
        .registration-paper * {
            visibility: visible;
        }


        .registration-paper {
            position: absolute;

            right: 0;
            top: 0;

            width: 210mm;

            min-height: 297mm;

            margin: 0;

            padding: 12mm;

            border: 0;

            box-shadow: none;

            background: #FFFFFF;
        }


        .card,
        .card-body {
            border: 0 !important;

            box-shadow: none !important;

            padding: 0 !important;

            margin: 0 !important;
        }


        .card-header {
            display: none !important;
        }


        @page {
            size: A4;

            margin: 0;
        }
    }


    /* =========================================
       الشاشات الصغيرة
    ========================================= */

    @media screen and (max-width: 900px) {

        .registration-paper {
            width: 100%;

            min-height: auto;

            overflow-x: auto;
        }

    }

</style>
