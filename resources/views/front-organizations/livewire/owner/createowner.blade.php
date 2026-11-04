@livewire(
    \App\Http\Livewire\FrontOrganizations\Shared\UniversalForm::class,
    [
        'fields' => $ownerFields,
        'title' => 'إضافة مالك جديد',
        'mode' => 'create',
        'eventName' => 'owner-form-submitted',
    ]
)