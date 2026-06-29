    @foreach ($types ?? [] as $key => $value)
        <div class="col-12 col-md-6">
            <label class="form-label">
                <i
                    class="{{ !empty($value['icon']) ? $value['icon'] . ' text-info me-1' : 'fas fa-keyboard text-info me-1' }}"></i>
                {{ $value['label'] }}</label>
            <input id="{{ $value['id'] ?? '' }}" class="form-control  
            @error($value['name']) is-invalid 
             @enderror
             " type="{{ $value['type'] ?? 'text' }}" name="{{ $value['name'] }}"
                placeholder="{{ empty($rows?->{$value['name']}) ? $value['label'] : '' }}"
                value="{{ old($value['name'], $rows->{$value['name']} ?? (($value['type'] ?? '') == 'date' ? date('Y-m-d') : '')) }}"
                step="{{ $value['step'] ?? '' }}"
                {{ $value['multiple'] ?? '' }}
                {{ $value['show'] ?? '' }}>
            @error($value['name'])
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
    @foreach ($selectes ?? [] as $key => $value)
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                <i
                    class="{{ !empty($value['icon']) ? $value['icon'] . ' text-info me-1' : 'fas fa-keyboard text-info me-1' }}"></i>
                {{ $value['label'] }}</label>
            @if (!empty($value['type']) && $value['type'] != 'relation')
                {{-- إذا فيه type نعرض input + select --}}
                <div class="input-group">
                    <input id="{{ $value['type_id'] ?? '' }}" type="{{ $value['type'] }}" name="{{ $value['type_name'] }}" class="form-control shadow-sm"
                        placeholder="مثال: 7"
                        value="{{ old($value['type_name'], $rows?->{$value['type_name']} ?? '') }}">

                    <select id="{{ $value['select_id'] ?? '' }}"  name="{{ $value['select_name'] }}" class="form-select shadow-sm">
                        <option value="">
                            اختر {{ $value['label'] }}
                        </option>
                        @foreach ($value['option'] as $optionKey => $optionLabel)
                            <option value="{{ $optionKey }}"
                                {{ old($value['select_name'], $rows?->{$value['select_name']} ?? '') == $optionKey ? 'selected' : '' }}>
                                {{ $optionLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @error($value['type_name'])
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
                @error($value['select_name'])
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            @elseif(!empty($value['type']) && $value['type'] == 'relation')
                <select  id="{{ $value['id'] ?? '' }}" name="{{ $value['name'] }}" class="form-select">

                    <option value="">
                        اختر {{ $value['label'] }}
                    </option>

                    @foreach ($value['option'] as $option)
                        <option value="{{ $option->{$value['value']} }}" @selected(old($value['name'], $rows->{$value['name']} ?? '') == $option->{$value['value']})
                            
                            @foreach($value['meta'] ?? [] as $meta)

                           data-{{ $meta }}="{{ $option->{$meta} }}"

                              @endforeach
                            
                            >

                            {{ $option->{$value['display']} }}

                        </option>
                    @endforeach

                </select>



                @error($value['name'])
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            @else
                {{-- إذا ما فيه type نعرض select فقط --}}
                <select name="{{ $value['name'] }}" class="form-select shadow-sm">
                    <option value="">
                        اختر {{ $value['label'] }}
                    </option>
                    @foreach ($value['option'] as $optionKey => $optionLabel)
                        <option value="{{ $optionKey }}"
                            {{ old($value['name'], $rows?->{$value['name']} ?? '') == $optionKey ? 'selected' : '' }}>
                            {{ $optionLabel }}
                        </option>
                    @endforeach
                </select>

                @error($value['name'])
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            @endif
        </div>
    @endforeach
    {{-- RELATION FIELD --}}
