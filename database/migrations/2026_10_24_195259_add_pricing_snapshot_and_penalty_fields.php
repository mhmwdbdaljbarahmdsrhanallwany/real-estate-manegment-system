
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // لقطة التسعير المعتمدة عند إنشاء الاشتراك

            $table->decimal('package_base_price', 12, 2)
                ->nullable()
                ->comment('سعر الباقة الأساسي وقت إنشاء الاشتراك');

            $table->enum('discount_type', ['fixed', 'percent'])
                ->nullable()
                ->comment('نوع الخصم المعتمد: مبلغ ثابت أو نسبة مئوية');

            $table->decimal('discount_value', 12, 2)
                ->default(0)
                ->comment('قيمة الخصم المعتمدة وقت إنشاء الاشتراك');

            $table->decimal('discount_amount', 12, 2)
                ->default(0)
                ->comment('مبلغ الخصم المحسوب والمثبت عند إنشاء الاشتراك');

            $table->enum('tax_type', ['fixed', 'percent'])
                ->nullable()
                ->comment('نوع الضريبة المعتمد: مبلغ ثابت أو نسبة مئوية');

            $table->decimal('tax_value', 12, 2)
                ->default(0)
                ->comment('قيمة الضريبة المعتمدة وقت إنشاء الاشتراك');

            $table->decimal('tax_amount', 12, 2)
                ->default(0)
                ->comment('مبلغ الضريبة المحسوب والمثبت عند إنشاء الاشتراك');

            $table->decimal('final_price', 12, 2)
                ->nullable()
                ->comment('السعر النهائي لدورة الفوترة بعد الخصم والضريبة وقبل الغرامات');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('penalty_amount', 12, 2)
                ->default(0)
                ->comment('إجمالي الغرامات المضافة إلى الفاتورة');

            $table->json('calculation_details')
                ->nullable()
                ->comment('تفاصيل الحساب المالي وفترات الغرامات لأغراض المراجعة والتدقيق');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'penalty_amount',
                'calculation_details',
            ]);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'package_base_price',
                'discount_type',
                'discount_value',
                'discount_amount',
                'tax_type',
                'tax_value',
                'tax_amount',
                'final_price',
            ]);
        });
    }
};
