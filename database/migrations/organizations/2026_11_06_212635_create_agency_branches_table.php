
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agency_branches', function (Blueprint $table) {

            $table->increments('branch_id')
                ->comment('معرف الفرع');

            $table->unsignedBigInteger('organization_id')
                ->comment('معرف المؤسسة الأم');

            $table->string('branch_name', 255)
                ->comment('اسم الفرع');

            $table->string('branch_code', 50)
                ->nullable()
                ->unique()
                ->comment('كود الفرع (فريد)');

            $table->enum('branch_type', ['main', 'sub'])
                ->default('sub')
                ->comment('نوع الفرع: رئيسي أو فرعي');

            $table->text('address')
                ->nullable()
                ->comment('عنوان الفرع');

            $table->string('city', 100)->nullable()
                ->comment('المدينة');

            $table->string('district', 100)->nullable()
                ->comment('الحي');

            $table->string('phone', 50)->nullable()
                ->comment('هاتف الفرع');

            $table->string('mobile', 50)->nullable()
                ->comment('جوال الفرع');

            $table->string('email', 100)->nullable()
                ->comment('بريد الفرع الإلكتروني');

            $table->unsignedBigInteger('manager_id')->nullable()
                ->comment('معرف مدير الفرع');

            $table->enum('status', ['active', 'inactive', 'closed'])
                ->default('active')
                ->comment('حالة الفرع');

            $table->json('opening_hours')->nullable()
                ->comment('ساعات العمل');

            $table->decimal('latitude', 10, 7)->nullable()
                ->comment('خط العرض');

            $table->decimal('longitude', 10, 7)->nullable()
                ->comment('خط الطول');

            $table->dateTime('created_at')->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            $table->dateTime('deleted_at')->nullable()
                ->comment('تاريخ الحذف المنطقي');

            $table->index('organization_id', 'idx_agency');
            $table->index('status', 'idx_status');
            $table->index('deleted_at', 'idx_deleted_at');

            // Foreign Key يُضاف بعد التأكد من جدول المؤسسات
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_branches');
    }
};
