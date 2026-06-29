<div class="card shadow-lg border-0 rounded-4 overflow-hidden">

    <!-- Header -->
    <div class="card-header bg-gradient-info text-white fw-bold">
        <i class="fas fa-phone me-2"></i> تعديل بيانات الاتصال
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
            <input type="hidden" name="section" value="contact">

            <div class="row g-3">

                <!-- الهاتف + الجوال -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الهاتف</label>
                    <input type="text" name="phone"
                           class="form-control rounded-3"
                           value="{{ old('phone',$data['phone']) }}"
                           placeholder="مثال: 01xxxxxxx">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">الجوال</label>
                    <input type="text" name="mobile"
                           class="form-control rounded-3"
                           value="{{ old('mobile',$data['mobile']) }}"
                           placeholder="مثال: 7xxxxxxxx">
                    @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- البريد + الموقع -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">البريد الإلكتروني</label>
                    <input type="email" name="email"
                           class="form-control rounded-3"
                           value="{{ old('email',$data['email']) }}"
                           placeholder="example@mail.com">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">الموقع الإلكتروني</label>
                    <input type="url" name="website"
                           class="form-control rounded-3"
                           value="{{ old('website',$data['website']) }}"
                           placeholder="https://example.com">
                    @error('website') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- العنوان -->
                <div class="col-12">
                    <label class="form-label fw-semibold">العنوان</label>
                    <input type="text" name="address"
                           class="form-control rounded-3"
                           value="{{ old('address',$data['address']) }}">
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- المدينة + الحي -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">المدينة</label>
                    <input type="text" name="city"
                           class="form-control rounded-3"
                           value="{{ old('city',$data['city']) }}">
                    @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">الحي</label>
                    <input type="text" name="district"
                           class="form-control rounded-3"
                           value="{{ old('district',$data['district']) }}">
                    @error('district') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- الرمز + صندوق البريد -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الرمز البريدي</label>
                    <input type="text" name="postal_code"
                           class="form-control rounded-3"
                           value="{{ old('postal_code',$data['postal_code']) }}">
                    @error('postal_code') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">صندوق البريد</label>
                    <input type="text" name="po_box"
                           class="form-control rounded-3"
                           value="{{ old('po_box',$data['po_box']) }}">
                    @error('po_box') <small class="text-danger">{{ $message }}</small> @enderror
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