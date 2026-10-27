<?php

namespace App\Http\Controllers\Admin\Invoices;
use App\Models\Admin\Packages\Package;
use App\Models\Admin\Subscriptions\Subscription;
use App\Services\Invoices\InvoiceService;
use App\Http\Controllers\Controller;
use App\Models\Admin\Invoices\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class InvoiceController extends Controller
{

    



    /**
 * عرض صفحة إدارة الفواتير.
 */
public function index()
{
    $invoices = Invoice::with([
        'subscription.organization',
        'subscription.package',
    ])
        ->orderByDesc('invoice_id')
        ->paginate(10);

    return view('admin.invoices.index', compact('invoices'));
}
    /**
 * البحث والتصفية في الفواتير عبر AJAX.
 */
public function ajaxSearch(Request $request)
{
    $query = Invoice::with([
        'subscription.organization',
        'subscription.package',
    ]);

    // 1. البحث برقم الفاتورة أو اسم المؤسسة
    if ($request->filled('searchInvoice')) {
        $search = trim($request->input('searchInvoice'));

        $query->where(function ($q) use ($search) {
            $q->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas(
                    'subscription.organization',
                    function ($organizationQuery) use ($search) {
                        $organizationQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
        });
    }

    // 2. التصفية حسب حالة الفاتورة
    $allowedStatuses = [
        'pending',
        'paid',
        'partial',
        'overdue',
    ];

    $status = $request->input('statusFilter');

    if (
        filled($status)
        && $status !== 'all'
        && in_array($status, $allowedStatuses, true)
    ) {
        $query->where('status', $status);
    }

    // 3. الترتيب حسب العمود
    $allowedSorts = [
        'invoice_id',
        'invoice_number',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'net_amount',
        'issue_date',
        'due_date',
        'status',
    ];

    $sortBy = $request->input('sortBy', 'invoice_id');

    if (!in_array($sortBy, $allowedSorts, true)) {
        $sortBy = 'invoice_id';
    }

    // 4. اتجاه الترتيب
    $sort = $request->input('sort', 'desc');

    if (!in_array($sort, ['asc', 'desc'], true)) {
        $sort = 'desc';
    }

    // 5. جلب النتائج مع الترقيم
    $invoices = $query
        ->orderBy($sortBy, $sort)
        ->orderBy('invoice_id', 'desc')
        ->paginate(10)
        ->withQueryString();

    // 6. إرجاع جزء جدول النتائج
    return view(
        'admin.invoices.ajax_search_invoice',
        compact('invoices')
    );
}

    /**
     * عرض تفاصيل فاتورة محددة.
     *
     * يجلب الفاتورة مع الاشتراك والمؤسسة والباقة المرتبطة بها،
     * ثم يرسل البيانات إلى صفحة عرض الفاتورة.
     */
    public function show($id)
    {
        $invoice = Invoice::with([
            'subscription.organization',
            'subscription.package',
        ])->findOrFail($id);

        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * عرض الفاتورة في وضع الطباعة.
     *
     * يستخدم نفس بيانات الفاتورة، لكن مع تفعيل وضع الطباعة
     * داخل صفحة العرض.
     */
    public function print($id)
    {
        $invoice = Invoice::with([
            'subscription.organization',
            'subscription.package',
        ])->findOrFail($id);

        return view('admin.invoices.show', [
            'invoice' => $invoice,
            'printMode' => true,
        ]);
    }
}
