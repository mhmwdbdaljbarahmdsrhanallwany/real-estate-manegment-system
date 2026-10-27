<div class="action-group">
    {{-- زر العرض --}}
    @if ($showRoute ?? false)
        <a href="{{ $showRoute }}">
            <button class="btn btn-sm btn-info text-white">
                <i class="fas fa-eye"></i>
            </button>
        </a>
    @endif

    {{-- زر التعديل --}}
    @if ($editRoute ?? false)
        <a href="{{ $editRoute }}">
            <button class="btn btn-sm btn-warning text-dark">
                <i class="fas fa-edit"></i>
            </button>
        </a>
    @endif

  {{-- زر الحذف --}}
@if ($deleteRoute ?? false)
<a href="javascript:void(0);"
   class="btn btn-sm btn-danger text-dark deleteBtn"
   data-id="{{ $deletedId }}"
   data-url="{{ $deleteRoute }}"
   data-entity="{{ $entity ?? 'السجل' }}"
   data-entity-plural="{{ $entityPlural ?? 'السجلات' }}"
   title="حذف {{ $entity ?? 'السجل' }}">

    <i class="fas fa-trash"></i>
</a>
@endif
    {{-- زر الاستعادة --}}
    @if ($restoreRoute ?? false)
    <a href="javascript:void(0);"
       class="btn btn-sm btn-success text-white restoreBtn"
       data-id="{{ $deletedId }}"
       data-url="{{ $restoreRoute }}"
       data-entity="{{ $entity }}"
       data-entity-plural="{{ $entityPlural }}">
        <i class="fas fa-undo"></i>
    </a>
@endif

{{-- زر الحذف النهائي --}}
@if ($forceDeleteRoute ?? false)
    <a href="javascript:void(0);"
       class="btn btn-sm btn-danger text-white forceDeleteBtn"
       data-id="{{ $deletedId }}"
       data-url="{{ $forceDeleteRoute }}"
       data-entity="{{ $entity ?? 'السجل' }}"
       data-entity-plural="{{ $entityPlural ?? 'السجلات' }}"
       title="حذف نهائي">

        <i class="fas fa-trash-alt"></i>
    </a>
@endif

    {{-- زر الإضافة --}}
    @if ($create ?? false)
        <button type="button" class="btn btn-success px-4 py-2 rounded-pill">
            <i class="fas fa-plus"></i> إضافة اشتراك جديد
        </button>
    @endif

    {{-- زر التحديث/الحفظ --}}
    @if ($save ?? false)
        <button type="submit" class="btn btn-primary px-4 ">
            <i class="fas fa-save me-1"></i> {{ $text }}
        </button>
    @endif
    {{-- ازرار التحكم --}}
    @if(!empty($check) && is_array($check))
        
            <button class="action-icon {{ $check['class'] }} change-status" data-id="{{ $check['id'] }}"
                data-status="{{ $check['status'] }}" title="{{ $check['title'] }}">

                <i class="{{ $icon }}"></i>
            </button>
        
    @endif

</div>
