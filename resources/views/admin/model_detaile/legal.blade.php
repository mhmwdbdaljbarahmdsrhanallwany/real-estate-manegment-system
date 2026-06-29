<div class="card shadow-lg border-0 rounded-4 overflow-hidden p-3">

    @if ($errors->any())
    <div class="alert alert-danger">
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
    <input type="hidden" name="section" value="legal">
    
    <div class="row g-3">
    
        <div class="col-12">
            <label class="form-label">اسم الممثل القانوني</label>
            <input type="text"
                   name="legal_representative_name"
                   value="{{ old('legal_representative_name',$data['legal_representative_name']) }}"
                   class="form-control"
                   placeholder="ادخل اسم الممثل القانوني">
            @error('legal_representative_name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    
        <div class="col-md-6">
            <label class="form-label">رقم الهوية</label>
            <input type="text"
                   name="legal_representative_id"
                   value="{{ old('legal_representative_id',$data['legal_representative_id']) }}"
                   class="form-control"
                   placeholder="ادخل رقم الهوية">
            @error('legal_representative_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    
        <div class="col-md-6">
            <label class="form-label">رقم الهاتف</label>
            <input type="text"
                   name="legal_representative_phone"
                   value="{{ old('legal_representative_phone',$data['legal_representative_phone']) }}"
                   class="form-control"
                   placeholder="ادخل رقم الهاتف">
            @error('legal_representative_phone')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    
    </div>
    
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-5">
            حفظ التعديلات
        </button>
    </div>
    
    </form>
    </div>