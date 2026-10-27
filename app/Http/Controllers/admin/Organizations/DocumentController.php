<?php

namespace App\Http\Controllers\Admin\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organizations\Organization;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
   
    public function index()
    {
        $organizationDocuments = Organization::select(
            'organization_id',
            'name',
            'commercial_registration',
            'tax_number',
            'municipality_license',
            'real_estate_broker_license',
            'status'
        )
        ->whereIn('status', [
            'pending_approval',
            'active',
            'suspended',
            'blocked',
            'expired',
        ])
        ->paginate(10);

        return view('admin.organizations.documents', [
            'organizationDocuments' => $organizationDocuments,
        ]);
    }

    public function ajax_organization_documents(Request $request)
    {
        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'organization_id';

        $allowedSorts = [
            'organization_id',
            'name',
            'commercial_registration',
            'tax_number',
            'status',
        ];

        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'organization_id';
        }

        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query = Organization::select(
            'organization_id',
            'name',
            'commercial_registration',
            'tax_number',
            'municipality_license',
            'real_estate_broker_license',
            'status'
        )
        ->whereIn('status', [
            'pending_approval',
            'active',
            'suspended',
            'blocked',
            'expired',
        ])
        ->orderBy($sortBy, $sortOrder);

        // البحث
        if ($request->filled('searchName')) {
            $query->where('name', 'like', '%' . $request->searchName . '%');
        }

        // فلترة الحالة
        if ($request->filled('statusFilter') && $request->statusFilter != 'all') {
            $query->where('status', $request->statusFilter);
        }

        $organizationDocuments = $query->paginate(10);

        $organizationDocuments->appends($request->all());

        return view(
            'admin.organizations.ajax_search_documents',
            compact('organizationDocuments')
        );
    }
    public function changeStatus(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,organization_id',
            'status' => 'required|in:active,pending_approval,suspended,blocked,expired',
        ]);
    
        $organization = Organization::findOrFail($request->organization_id);
    
        /*
        |--------------------------------------------------------------------------
        | التحقق من اكتمال المستندات قبل تفعيل المؤسسة
        |--------------------------------------------------------------------------
        */
    
        if ($request->status === 'active') {
    
            $missingDocuments = [];
    
            if (empty($organization->municipality_license)) {
                $missingDocuments[] = 'رخصة البلدية';
            }
    
            if (empty($organization->real_estate_broker_license)) {
                $missingDocuments[] = 'رخصة الوساطة العقارية';
            }
    
            // إذا كان هناك أي مستند ناقص
            if (!empty($missingDocuments)) {
    
                return response()->json([
                    'status' => false,
                    'message' => 'لا يمكن تفعيل المؤسسة لأن المستندات المطلوبة غير مكتملة.',
                    'missing_documents' => $missingDocuments,
                ], 422);
            }
        }
    
        /*
        |--------------------------------------------------------------------------
        | تحديث حالة المؤسسة
        |--------------------------------------------------------------------------
        */
    
        $organization->update([
            'status' => $request->status,
        ]);
    
        /*
        |--------------------------------------------------------------------------
        | رسائل الحالات
        |--------------------------------------------------------------------------
        */
    
        $messages = [
            'active' => 'تم اعتماد المؤسسة بنجاح.',
            'pending_approval' => 'تمت إعادة المؤسسة إلى حالة المراجعة.',
            'suspended' => 'تم تعليق المؤسسة بنجاح.',
            'blocked' => 'تم حظر المؤسسة بنجاح.',
            'expired' => 'تم إنهاء المؤسسة.',
        ];
    
        return response()->json([
            'status' => true,
            'message' => $messages[$request->status] ?? 'تم تحديث حالة المؤسسة.',
        ]);
    }
}
