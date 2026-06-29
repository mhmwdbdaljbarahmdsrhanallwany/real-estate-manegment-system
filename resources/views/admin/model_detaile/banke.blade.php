<div class="card shadow-lg border-0 rounded-4 overflow-hidden">

    <!-- Header -->
    <div class="card-header bg-gradient-success text-white fw-bold">
        <i class="fas fa-university me-2"></i> تعديل المعلومات البنكية
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
            <input type="hidden" name="section" value="banke">

            <div class="row g-3">

                <!-- اسم البنك -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">اسم البنك</label>
                    <input type="text"
                           name="bank_name"
                           value="{{ old('bank_name',$data['bank_name']) }}"
                           class="form-control rounded-3"
                           placeholder="مثال: بنك الراجحي">
                    @error('bank_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- اسم الحساب -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">اسم الحساب</label>
                    <input type="text"
                           name="bank_account_name"
                           value="{{ old('bank_account_name',$data['bank_account_name']) }}"
                           class="form-control rounded-3"
                           placeholder="اسم صاحب الحساب">
                    @error('bank_account_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- رقم الحساب -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">رقم الحساب</label>
                    <input type="text"
                           name="bank_account_number"
                           value="{{ old('bank_account_number',$data['bank_account_number']) }}"
                           class="form-control rounded-3"
                           placeholder="1234567890">
                    @error('bank_account_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- الايبان -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">IBAN</label>
                    <input type="text"
                           name="bank_iban"
                           value="{{ old('bank_iban',$data['bank_iban']) }}"
                           class="form-control rounded-3"
                           placeholder="SA00 0000 0000 0000 0000 0000">
                    @error('bank_iban') <small class="text-danger">{{ $message }}</small> @enderror
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