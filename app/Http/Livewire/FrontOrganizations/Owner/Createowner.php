<?php

namespace App\Http\Livewire\FrontOrganizations\Owner;

use App\Models\Owner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Createowner extends Component
{
    use WithFileUploads;
public array $ownerFields = [
    [
        'name' => 'full_name',
        'label' => 'الاسم الكامل',
        'type' => 'text',
        'required' => true,
        'rules' => 'required|string|max:150',
    ],
    [
        'name' => 'phone',
        'label' => 'رقم الجوال',
        'type' => 'text',
        'required' => true,
        'rules' => 'required|string|max:20',
    ],
    [
        'name' => 'email',
        'label' => 'البريد الإلكتروني',
        'type' => 'email',
        'required' => false,
        'rules' => 'nullable|email',
    ],
];
//     // البيانات الأساسية
//     public string $full_name = '';
//     public ?string $nationality = 'سعودي';
//     public string $id_type = 'national_id';
//     public ?string $id_number = null;
//     public $id_image = null;
//     public ?string $id_image_preview = null;

//     // معلومات شخصية
//     public ?string $birth_date = null;
//     public ?string $gender = 'male';
//     public ?string $phone = null;
//     public ?string $phone2 = null;

//     // اتصال
//     public ?string $email = null;
//     public ?string $address = null;
//     public ?string $city = null;
//     public ?string $district = null;
//     public ?string $postal_code = null;

//     // بنكي
//     public ?string $bank_name = null;
//     public ?string $bank_account_name = null;
//     public ?string $bank_account_number = null;
//     public ?string $bank_iban = null;
//     public string $preferred_payment_method = 'bank_transfer';

//     // ملاحظات ومستندات
//     public ?string $notes = null;
//    // ملفات المستندات
// public array $documents = [];

// public array $contract_files = [];

//     // حالة
//     public bool $showSuccess = false;
//     public ?string $successMessage = null;
//     public ?int $createdOwnerId = null;

//     // خيارات
//     public array $nationalities = ['سعودي', 'مصري', 'يمني', 'سوداني', 'أخرى'];
//     public array $cities = ['الرياض', 'جدة', 'مكة', 'المدينة', 'الدمام'];
//     public array $idTypes = [
//         'national_id' => 'هوية وطنية',
//         'iqama' => 'إقامة',
//         'passport' => 'جواز سفر',
//         'commercial_reg' => 'سجل تجاري',
//     ];
//     public array $paymentMethods = [
//         'bank_transfer' => 'تحويل بنكي',
//         'cheque' => 'شيك',
//         'cash' => 'نقداً',
//     ];

//     protected function rules(): array
//     {
//         return [
//             'full_name' => 'required|string|max:255',
//             'nationality' => 'nullable|string|max:50',
//             'id_type' => 'required|in:national_id,iqama,passport,commercial_reg',
//             'id_number' => 'nullable|string|max:50',
//             'id_image' => 'nullable|image|max:5120',
//             'birth_date' => 'nullable|date',
//             'gender' => 'nullable|in:male,female',
//             'phone' => 'required|string|max:50',
//             'phone2' => 'nullable|string|max:50',
//             'email' => 'nullable|email|max:100',
//             'address' => 'nullable|string|max:255',
//             'city' => 'nullable|string|max:100',
//             'district' => 'nullable|string|max:100',
//             'postal_code' => 'nullable|string|max:20',
//             'bank_name' => 'nullable|string|max:100',
//             'bank_account_name' => 'nullable|string|max:255',
//             'bank_account_number' => 'nullable|string|max:50',
//             'bank_iban' => 'nullable|string|max:50',
//             'preferred_payment_method' => 'required|in:bank_transfer,cheque,cash',
//             'notes' => 'nullable|string',
//             'documents.*' => 'nullable|file|max:10240',
//         ];
//     }

//     public function updatedIdImage(): void
//     {
//         $this->id_image_preview = $this->id_image?->temporaryUrl();
//     }

//     public function addDocument(): void
//     {
//         if (count($this->documents) < 4) {
//             $this->documents[] = null;
//         }
//     }

//     public function removeDocument(int $index): void
//     {
//         unset($this->documents[$index]);
//         $this->documents = array_values($this->documents);
//     }

//     public function save(): void
//     {
//         $validated = $this->validate();

//         // رفع الصورة
//         if ($this->id_image) {
//             $validated['id_image'] = $this->id_image->store('owners/ids', 'public');
//         }

//         $owner = Owner::create($validated);

//         $this->createdOwnerId = $owner->owner_id;
//         $this->successMessage = "تم إضافة المالك {$owner->full_name} بنجاح";
//         $this->showSuccess = true;

//         $this->dispatch('owner-created', ownerId: $owner->owner_id);

//         // إعادة تعيين النموذج (اختياري)
//         // $this->resetExcept(['showSuccess', 'successMessage', 'createdOwnerId']);
//     }

//     public function cancel(): void
//     {
//         return; // أو redirect
//     }

    public function render()
{
    return view(
        'front-organizations.livewire.owner.createowner',
        [
            'documents' => $this->documents,
            'contract_files' => $this->contract_files,
        ]
    )->layout('front-organizations.layout.master');
}
}