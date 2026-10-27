<style>.sForm-field {
    margin-bottom: 1.25rem;
}
.sForm-label {
    display: flex;
    align-items: center;
    gap: .45rem;
    font-size: .82rem;
    font-weight: 600;
    color: #64748B; /* لون رمادي داكن احترافي وواضح */
    margin-bottom: .4rem;
}
.sForm-label i {
    color: #00F5D4; /* الأخضر الرقمي المستوحى من الشعار */
    font-size: .78rem;
    width: 16px;
}
.sForm-control {
    width: 100%;
    padding: .55rem .85rem;
    font-size: .88rem;
    color: #0B132B; /* الأزرق الداكن للنصوص */
    background: #fff;
    border: 1px solid #d9e3ee;
    border-radius: 10px;
    transition: all .2s;
    outline: none;
    box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.sForm-control:focus {
    border-color: #00F5D4; /* تفعيل بالأخضر الرقمي الذكي */
    box-shadow: 0 0 0 3px rgba(0, 245, 212, .15);
}
.sForm-control.is-invalid {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,.1);
}
.sForm-control::placeholder {
    color: #94a3b8;
}
select.sForm-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left .75rem center;
    padding-left: 2rem;
}
.sForm-error {
    display: flex;
    align-items: center;
    gap: .3rem;
    margin-top: .3rem;
    font-size: .75rem;
    color: #ef4444;
    font-weight: 500;
}
.sForm-error::before {
    content: '\f071';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: .65rem;
}
.sForm-input-group {
    display: flex;
    gap: 0;
}
.sForm-input-group .sForm-control:first-child {
    border-radius: 0 10px 10px 0;
    border-left: none;
}
.sForm-input-group .sForm-control:last-child {
    border-radius: 10px 0 0 10px;
}
.sForm-input-group .sForm-control:focus {
    position: relative;
    z-index: 1;
}

/* تنسيقات بحث الخريطة */
.organization-map-search {
    background: #fff;
    padding: 8px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(11, 19, 43, 0.1);
    width: 300px;
    border: 1px solid #d9e3ee;
}

.organization-map-search input {
    width: 100%;
    height: 38px;
    border: 1px solid #d9e3ee;
    border-radius: 8px;
    padding: 0 10px;
    font-size: 14px;
    outline: none;
    color: #0B132B;
}

.organization-map-search input:focus {
    border-color: #00F5D4;
    box-shadow: 0 0 0 3px rgba(0, 245, 212, .15);
}

.organization-search-results {
    margin-top: 5px;
    max-height: 250px;
    overflow-y: auto;
}

.organization-search-result {
    padding: 8px 10px;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    background: #fff;
    color: #334155;
    font-size: 13px;
    transition: background 0.2s;
}

.organization-search-result:hover {
    background: #f0faf7; /* خلفية خضراء فاتحة جداً عند التحويم */
    color: #0B132B;
}
</style>
{{-- =========================
     الحقول النصية
========================= --}}
@foreach ($types ?? [] as $key => $value)
    @php
        $fieldName = $value['name'] ?? $key;
        $fieldType = $value['type'] ?? 'text';
        $fieldId = $value['id'] ?? $fieldName;

        $fieldValue = old(
            $fieldName,
            isset($rows) ? data_get($rows, $fieldName, '') : ''
        );

        $isRequired = !empty($value['required']);
        $isReadonly = !empty($value['readonly']);
        $isDisabled = !empty($value['disabled']);
    @endphp

    <div class="col-12 col-md-6 sForm-field" style="margin-top: 10px">
        <label class="sForm-label" for="{{ $fieldId }}">
            <i class="{{ !empty($value['icon']) ? $value['icon'] : 'fas fa-keyboard' }}"></i>
            {{ $value['label'] ?? $fieldName }}

            @if ($isRequired)
                <span class="text-danger">*</span>
            @endif
        </label>

        @if ($fieldType === 'textarea')
            <textarea
                id="{{ $fieldId }}"
                name="{{ $fieldName }}"
                class="sForm-control @error($fieldName) is-invalid @enderror"
                placeholder="{{ $value['placeholder'] ?? ($value['label'] ?? '') }}"
                @if (!empty($value['maxlength'])) maxlength="{{ $value['maxlength'] }}" @endif
                rows="{{ $value['rows'] ?? 3 }}"
                @if ($isRequired) required @endif
                @if ($isReadonly) readonly @endif
                @if ($isDisabled) disabled @endif
            >{{ $fieldValue }}</textarea>
        @else
            <input
                id="{{ $fieldId }}"
                name="{{ $fieldName }}"
                type="{{ $fieldType }}"
                class="sForm-control @error($fieldName) is-invalid @enderror"
                placeholder="{{ $value['placeholder'] ?? ($value['label'] ?? '') }}"
                @if (isset($value['maxlength']) && $value['maxlength'] !== '') maxlength="{{ $value['maxlength'] }}" @endif
                @if (isset($value['min']) && $value['min'] !== '') min="{{ $value['min'] }}" @endif
                @if (isset($value['max']) && $value['max'] !== '') max="{{ $value['max'] }}" @endif
                value="{{ $fieldValue }}"
                @if (isset($value['step']) && $value['step'] !== '') step="{{ $value['step'] }}" @endif
                @if (!empty($value['multiple'])) multiple @endif
                @if (!empty($value['show'])) {{ $value['show'] }} @endif
                @if ($isRequired) required @endif
                @if ($isReadonly) readonly @endif
                @if ($isDisabled) disabled @endif
            >
        @endif

        @error($fieldName)
            <span class="sForm-error">{{ $message }}</span>
        @enderror
    </div>
@endforeach


{{-- =========================
     القوائم المنسدلة
========================= --}}
@foreach ($selectes ?? [] as $key => $value)
    @php
        $fieldName = $value['name'] ?? $key;
        $fieldId = $value['id'] ?? $fieldName;
        $fieldType = $value['type'] ?? 'select';

        $fieldValue = old(
            $fieldName,
            isset($rows) ? data_get($rows, $fieldName, $value['default'] ?? '') : ($value['default'] ?? '')
        );

        $isRequired = !empty($value['required']);
        $isDisabled = !empty($value['disabled']);
    @endphp

<div
    class="col-12 col-md-6 sForm-field {{ $value['wrapper_class'] ?? '' }}"
    style="margin-top: 10px"
>        <label class="sForm-label" for="{{ $fieldId }}">
            <i class="{{ !empty($value['icon']) ? $value['icon'] : 'fas fa-list' }}"></i>
            {{ $value['label'] ?? $fieldName }}

            @if ($isRequired)
                <span class="text-danger">*</span>
            @endif
        </label>

        @if ($fieldType === 'relation')
            <select
                id="{{ $fieldId }}"
                name="{{ $fieldName }}"
                class="sForm-control @error($fieldName) is-invalid @enderror"
                @if ($isRequired) required @endif
                @if ($isDisabled) disabled @endif
            >
                @if (!$isRequired)
                    <option value="">
                        {{ $value['placeholder'] ?? 'اختر ' . ($value['label'] ?? 'القيمة') }}
                    </option>
                @endif

                @foreach (($value['option'] ?? []) as $option)
                    @php
                        $optionValue = data_get($option, $value['value'] ?? 'id');
                        $optionDisplay = data_get($option, $value['display'] ?? 'name', $optionValue);
                    @endphp

                    <option
                        value="{{ $optionValue }}"
                        @selected((string) $fieldValue === (string) $optionValue)
                        @foreach (($value['meta'] ?? []) as $metaField)
                            data-{{ \Illuminate\Support\Str::snake($metaField, '-') }}="{{ data_get($option, $metaField, '') }}"
                        @endforeach
                    >
                        {{ $optionDisplay }}
                    </option>
                @endforeach
            </select>

        @elseif (!empty($value['input_name']))
            <div class="d-flex" style="gap: 8px">
                <input
                    id="{{ $value['input_id'] ?? $value['input_name'] }}"
                    name="{{ $value['input_name'] }}"
                    type="{{ $value['input_type'] ?? 'text' }}"
                    class="sForm-control @error($value['input_name']) is-invalid @enderror"
                    value="{{ old($value['input_name'], isset($rows) ? data_get($rows, $value['input_name'], '') : '') }}"
                    placeholder="{{ $value['input_placeholder'] ?? '' }}"
                    @if (!empty($value['input_required'])) required @endif
                    @if (!empty($value['input_readonly'])) readonly @endif
                    @if (!empty($value['input_disabled'])) disabled @endif
                >

                <select
                    id="{{ $fieldId }}"
                    name="{{ $fieldName }}"
                    class="sForm-control @error($fieldName) is-invalid @enderror"
                    @if ($isRequired) required @endif
                    @if ($isDisabled) disabled @endif
                >
                    @foreach (($value['option'] ?? []) as $optionKey => $optionLabel)
                        <option
                            value="{{ $optionKey }}"
                            @selected((string) $fieldValue === (string) $optionKey)
                        >
                            {{ $optionLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

        @else
            <select
                id="{{ $fieldId }}"
                name="{{ $fieldName }}"
                class="sForm-control @error($fieldName) is-invalid @enderror"
                @if ($isRequired) required @endif
                @if ($isDisabled) disabled @endif
                @if (!empty($value['multiple'])) multiple @endif
            >
                @if (!$isRequired && empty($value['multiple']))
                    <option value="">
                        {{ $value['placeholder'] ?? 'اختر ' . ($value['label'] ?? 'القيمة') }}
                    </option>
                @endif

                @foreach (($value['option'] ?? []) as $optionKey => $optionLabel)
                    <option
                        value="{{ $optionKey }}"
                        @selected((string) $fieldValue === (string) $optionKey)
                    >
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
        @endif

        @error($fieldName)
            <span class="sForm-error">{{ $message }}</span>
        @enderror

        @if (!empty($value['input_name']))
            @error($value['input_name'])
                <span class="sForm-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
@endforeach
