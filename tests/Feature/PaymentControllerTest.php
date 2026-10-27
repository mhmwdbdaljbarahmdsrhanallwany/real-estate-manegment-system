<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
{
    parent::setUp();

    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    $this->actingAs(User::factory()->create(), 'web');
}

    public function test_it_records_a_partial_payment_and_updates_invoice_status(): void
    {
        $this->withoutMiddleware(
            \App\Http\Middleware\AccessContextMiddleware::class
        );
        $organizationId = DB::table('organizations')->insertGetId([
            'name' => 'Test Organization',
        ], 'organization_id');

        $packageId = DB::table('packages')->insertGetId([
            'name' => 'Test Package',
            'code' => 'TEST-PACKAGE',
            'level' => 1,
            'base_price' => 100,
            'billing_cycle' => 'monthly',
            'duration_days' => 30,
        ], 'package_id');

        $subscriptionId = DB::table('subscriptions')->insertGetId([
            'organization_id' => $organizationId,
            'package_id' => $packageId,
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-31',
            'status' => 'active',
            'subscription_type' => 'paid',
        ], 'subscription_id');

        $invoiceId = DB::table('invoices')->insertGetId([
            'subscription_id' => $subscriptionId,
            'invoice_number' => 'TEST-INV-001',
            'total_amount' => 100,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'net_amount' => 100,
            'status' => 'pending',
            'issue_date' => '2026-10-01',
        ], 'invoice_id');

        $response = $this->postJson(route('admin.payments.store'), [
            'invoice_id' => $invoiceId,
            'subscription_id' => $subscriptionId,
            'amount' => 40,
            'payment_method' => 'cash',
            'reference_number' => 'TEST-REF-001',
        ]);

        $response->dump();

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('invoice_status', 'partial')
            ->assertJsonPath('paid_amount', 40)
            ->assertJsonPath('remaining_amount', 60);
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoiceId,
            'subscription_id' => $subscriptionId,
            'amount' => 40,
            'payment_method' => 'cash',
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('invoices', [
            'invoice_id' => $invoiceId,
            'status' => 'partial',
        ]);
    }

    public function test_payment_store_route_does_not_accept_get_requests(): void
    {
        $response = $this->get(route('admin.payments.store'));

        $response->assertStatus(405);
    }
    
public function test_it_rejects_payment_greater_than_remaining_amount(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Test Organization',
    ], 'organization_id');

    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Test Package',
        'code' => 'TEST-PACKAGE-OVERPAY',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'TEST-INV-OVERPAY',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'partial',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    // دفعة سابقة بقيمة 70، لذا المتبقي 30.
    DB::table('payments')->insert([
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 70,
        'payment_method' => 'cash',
        'status' => 'success',
        'paid_at' => now(),
    ]);

    $response = $this->postJson(route('admin.payments.store'), [
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 40,
        'payment_method' => 'cash',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['amount']);

    $this->assertDatabaseCount('payments', 1);
}

public function test_it_rejects_a_payment_for_an_already_paid_invoice(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Test Organization',
    ], 'organization_id');

    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Test Package',
        'code' => 'TEST-PACKAGE-PAID',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'TEST-INV-PAID',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'paid',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    $response = $this->postJson(route('admin.payments.store'), [
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 20,
        'payment_method' => 'cash',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['invoice_id']);

    $this->assertDatabaseMissing('payments', [
        'invoice_id' => $invoiceId,
    ]);
}

public function test_it_marks_invoice_as_paid_when_remaining_amount_is_paid(): void
{
    // تجاوز Middleware الخاص بسياق الوصول في هذا الاختبار فقط.
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    // 1. إنشاء المؤسسة
    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Full Payment Test Organization',
    ], 'organization_id');

    // 2. إنشاء الباقة
    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Full Payment Test Package',
        'code' => 'FULL-PAYMENT-TEST',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    // 3. إنشاء الاشتراك
    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    // 4. إنشاء فاتورة بقيمة 100
    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'FULL-PAYMENT-TEST-001',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'partial',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    // 5. تسجيل دفعة سابقة بقيمة 70
    DB::table('payments')->insert([
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 70,
        'payment_method' => 'cash',
        'status' => 'success',
        'paid_at' => now(),
    ]);

    // 6. دفع المبلغ المتبقي: 30
    $response = $this->postJson(route('admin.payments.store'), [
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 30,
        'payment_method' => 'cash',
    ]);

    // 7. التحقق من نجاح العملية
    $response->assertCreated()
        ->assertJsonFragment([
            'success' => true,
            'invoice_status' => 'paid',
            'paid_amount' => 100,
            'remaining_amount' => 0,
        ]);

    // 8. التأكد من تحديث حالة الفاتورة في قاعدة بيانات الاختبار
    $this->assertDatabaseHas('invoices', [
        'invoice_id' => $invoiceId,
        'status' => 'paid',
    ]);

    // 9. التأكد من وجود الدفعتين
    $this->assertSame(
        2,
        DB::table('payments')
            ->where('invoice_id', $invoiceId)
            ->count()
    );
}

public function test_it_rejects_payment_when_subscription_does_not_belong_to_invoice(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    // إنشاء المؤسسة
    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Subscription Ownership Test Organization',
    ], 'organization_id');

    // إنشاء الباقة
    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Subscription Ownership Test Package',
        'code' => 'SUB-OWNERSHIP-TEST',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    // إنشاء الاشتراك الأول الذي ستتبعه الفاتورة
    $firstSubscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    // إنشاء اشتراك ثانٍ مختلف
    $secondSubscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    // الفاتورة تتبع الاشتراك الأول
    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $firstSubscriptionId,
        'invoice_number' => 'SUB-OWNERSHIP-TEST-001',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'pending',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    // نحاول الدفع باستخدام الاشتراك الثاني
    $response = $this->postJson(route('admin.payments.store'), [
        'invoice_id' => $invoiceId,
        'subscription_id' => $secondSubscriptionId,
        'amount' => 30,
        'payment_method' => 'cash',
    ]);

    // يجب رفض الطلب
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['subscription_id']);

    // يجب ألا تُسجّل أي دفعة لهذه الفاتورة
    $this->assertSame(
        0,
        DB::table('payments')
            ->where('invoice_id', $invoiceId)
            ->count()
    );

    // يجب أن تبقى الفاتورة دون تغيير
    $this->assertDatabaseHas('invoices', [
        'invoice_id' => $invoiceId,
        'subscription_id' => $firstSubscriptionId,
        'status' => 'pending',
    ]);
}

public function test_it_rejects_zero_and_negative_payment_amounts(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Invalid Amount Test Organization',
    ], 'organization_id');

    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Invalid Amount Test Package',
        'code' => 'INVALID-AMOUNT-TEST',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'INVALID-AMOUNT-TEST-001',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'pending',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    // تجربة المبلغ صفرًا ثم المبلغ السالب
    foreach ([0, -10] as $amount) {
        $response = $this->postJson(route('admin.payments.store'), [
            'invoice_id' => $invoiceId,
            'subscription_id' => $subscriptionId,
            'amount' => $amount,
            'payment_method' => 'cash',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['amount']);
    }

    // لم تُسجّل أي دفعة غير صالحة
    $this->assertSame(
        0,
        DB::table('payments')
            ->where('invoice_id', $invoiceId)
            ->count()
    );

    // بقيت الفاتورة على حالتها الأصلية
    $this->assertDatabaseHas('invoices', [
        'invoice_id' => $invoiceId,
        'status' => 'pending',
    ]);
}

public function test_it_rejects_payment_amounts_with_more_than_two_decimal_places(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    // إنشاء المؤسسة
    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Decimal Precision Test Organization',
    ], 'organization_id');

    // إنشاء الباقة
    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Decimal Precision Test Package',
        'code' => 'DECIMAL-PRECISION-TEST',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    // إنشاء الاشتراك
    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    // إنشاء الفاتورة
    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'DECIMAL-PRECISION-TEST-001',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'pending',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    // محاولة إرسال مبلغ يتجاوز منزلتين عشريتين
    $response = $this->postJson(route('admin.payments.store'), [
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => '10.999',
        'payment_method' => 'cash',
    ]);

    // يجب رفض المبلغ بسبب دقته العشرية
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['amount']);

    // التأكد من عدم تسجيل دفعة
    $this->assertSame(
        0,
        DB::table('payments')
            ->where('invoice_id', $invoiceId)
            ->count()
    );

    // التأكد من أن حالة الفاتورة لم تتغير
    $this->assertDatabaseHas('invoices', [
        'invoice_id' => $invoiceId,
        'status' => 'pending',
    ]);
}

public function test_it_rejects_unsupported_payment_methods(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    // إنشاء المؤسسة
    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Invalid Payment Method Test Organization',
    ], 'organization_id');

    // إنشاء الباقة
    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Invalid Payment Method Test Package',
        'code' => 'INVALID-PAYMENT-METHOD-TEST',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    // إنشاء الاشتراك
    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    // إنشاء الفاتورة
    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'INVALID-PAYMENT-METHOD-001',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'pending',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    // محاولة استخدام طريقة دفع غير مدعومة
    $response = $this->postJson(route('admin.payments.store'), [
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 20,
        'payment_method' => 'bitcoin',
    ]);

    // يجب رفض الطلب بسبب طريقة الدفع
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['payment_method']);

    // التأكد من عدم تسجيل أي دفعة
    $this->assertSame(
        0,
        DB::table('payments')
            ->where('invoice_id', $invoiceId)
            ->count()
    );

    // التأكد من بقاء الفاتورة دون تغيير
    $this->assertDatabaseHas('invoices', [
        'invoice_id' => $invoiceId,
        'status' => 'pending',
    ]);
}

public function test_it_counts_only_successful_payments_when_calculating_remaining_amount(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Payment Status Test Organization',
    ], 'organization_id');

    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Payment Status Test Package',
        'code' => 'PAYMENT-STATUS-TEST',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'PAYMENT-STATUS-TEST-001',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'partial',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    // دفعة ناجحة: تُحتسب ضمن المدفوع
    DB::table('payments')->insert([
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 40,
        'payment_method' => 'cash',
        'status' => 'success',
        'paid_at' => now(),
    ]);

    // دفعة معلّقة: لا تُحتسب ضمن المدفوع
    DB::table('payments')->insert([
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 30,
        'payment_method' => 'online',
        'status' => 'pending',
        'paid_at' => now(),
    ]);

    // دفعة فاشلة: لا تُحتسب ضمن المدفوع
    DB::table('payments')->insert([
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 20,
        'payment_method' => 'card',
        'status' => 'failed',
        'paid_at' => now(),
    ]);

    // المتبقي الصحيح هو 60، لأن الدفعة الناجحة الوحيدة تساوي 40
    $response = $this->postJson(route('admin.payments.store'), [
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'amount' => 60,
        'payment_method' => 'cash',
    ]);

    $response->assertCreated()
        ->assertJsonFragment([
            'success' => true,
            'invoice_status' => 'paid',
            'paid_amount' => 100,
            'remaining_amount' => 0,
        ]);

    $this->assertDatabaseHas('invoices', [
        'invoice_id' => $invoiceId,
        'status' => 'paid',
    ]);

    // ثلاث دفعات سابقة/جديدة، دون حذف المعلّقة أو الفاشلة
    $this->assertSame(
        4,
        DB::table('payments')
            ->where('invoice_id', $invoiceId)
            ->count()
    );
}
public function test_it_rejects_card_and_online_payments_without_a_gateway(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    // تجهيز اشتراك وفاتورة للاختبار
    $organizationId = DB::table('organizations')->insertGetId([
        'name' => 'Test Organization',
    ], 'organization_id');

    $packageId = DB::table('packages')->insertGetId([
        'name' => 'Test Package',
        'code' => 'GATEWAY-TEST',
        'level' => 1,
        'base_price' => 100,
        'billing_cycle' => 'monthly',
        'duration_days' => 30,
    ], 'package_id');

    $subscriptionId = DB::table('subscriptions')->insertGetId([
        'organization_id' => $organizationId,
        'package_id' => $packageId,
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'status' => 'active',
        'subscription_type' => 'paid',
    ], 'subscription_id');

    $invoiceId = DB::table('invoices')->insertGetId([
        'subscription_id' => $subscriptionId,
        'invoice_number' => 'GATEWAY-TEST-INV',
        'total_amount' => 100,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'net_amount' => 100,
        'status' => 'pending',
        'issue_date' => '2026-10-01',
    ], 'invoice_id');

    foreach (['card', 'online'] as $method) {
        $response = $this->postJson(
            route('admin.payments.store'),
            [
                'invoice_id' => $invoiceId,
                'subscription_id' => $subscriptionId,
                'amount' => 40,
                'payment_method' => $method,
            ]
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['payment_method']);
    }

    // يجب ألا تُسجّل أي دفعة بالطريقتين المرفوضتين
    $this->assertDatabaseCount('payments', 0);

    // يجب أن تبقى الفاتورة دون تغيير
    $this->assertDatabaseHas('invoices', [
        'invoice_id' => $invoiceId,
        'status' => 'pending',
    ]);
}

public function test_guest_cannot_access_payment_store(): void
{
    $this->withoutMiddleware(
        \App\Http\Middleware\AccessContextMiddleware::class
    );

    // إزالة المستخدم المسجل للاختبار الحالي فقط.
    auth('web')->logout();

    $this->assertGuest('web');

    $response = $this->postJson(
        route('admin.payments.store'),
        [
            'invoice_id' => 1,
            'subscription_id' => 1,
            'amount' => 50,
            'payment_method' => 'cash',
        ]
    );

    $response->assertUnauthorized();
}

}
