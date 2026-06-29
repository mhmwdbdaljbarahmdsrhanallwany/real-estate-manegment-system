<div class="card shadow-lg border-0 rounded-4 overflow-hidden">

    <!-- Header -->
    <div class="card-header bg-gradient-primary text-white fw-bold">
        <i class="fas fa-building me-2"></i> تعديل بيانات المؤسسة
    </div>

    <div class="card-body">

        <!-- Errors -->
        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="dynamicForm">

            @csrf

            <input type="hidden" name="id" value="{{ $data->agency_id }}">
            <input type="hidden" name="section" value="agency">

            <div class="row g-3">

                <!-- اسم المؤسسة -->
                <div class="col-12">
                    <label class="form-label fw-semibold">اسم المؤسسة</label>
                    <input type="text"
                           name="agency_name"
                           value="{{ old('agency_name',$data['agency_name']) }}"
                           class="form-control rounded-3"
                           placeholder="ادخل اسم المؤسسة">
                    @error('agency_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- السجل التجاري -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">السجل التجاري</label>
                    <input type="text"
                           name="commercial_registration"
                           value="{{ old('commercial_registration',$data['commercial_registration']) }}"
                           class="form-control rounded-3"
                           placeholder="مثال: 123456789">
                    @error('commercial_registration') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- الرقم الضريبي -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الرقم الضريبي</label>
                    <input type="text"
                           name="tax_number"
                           value="{{ old('tax_number',$data['tax_number']) }}"
                           class="form-control rounded-3"
                           placeholder="مثال: 300xxxxxxx">
                    @error('tax_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- الرقم الوطني -->
                <div class="col-12">
                    <label class="form-label fw-semibold">الرقم الوطني الموحد</label>
                    <input type="text"
                           name="unified_national_number"
                           value="{{ old('unified_national_number',$data['unified_national_number']) }}"
                           class="form-control rounded-3"
                           placeholder="ادخل الرقم الوطني">
                    @error('unified_national_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

            </div>

            <!-- زر الحفظ -->
            <div class="text-center mt-4">
                <button type="submit"
                        class="btn btn-success px-5 rounded-pill shadow-sm">
                    <i class="fas fa-save me-1"></i> حفظ التعديلات
                </button>
            </div>

        </form>
    </div>
</div>