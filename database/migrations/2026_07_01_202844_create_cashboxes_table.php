<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cashboxes', function (Blueprint $table) {
            $table->id('cashboxe_id')
                ->comment('المعرف الأساسي للصندوق المالي');
        
            $table->string('name')
                ->comment('اسم الصندوق (مثال: الصندوق الرئيسي، صندوق فرع صنعاء)');
            
            $table->boolean('is_main')->default(false)
                ->comment('هل هو الصندوق الرئيسي للنظام');
        
            $table->string('code')->unique()
                ->comment('كود فريد لتعريف الصندوق داخلياً');
        
            $table->decimal('opening_balance', 12, 2)->default(0)
                ->comment('الرصيد الافتتاحي عند إنشاء الصندوق');
        
            $table->decimal('current_balance', 12, 2)->default(0)
                ->comment('الرصيد الحالي المتغير حسب العمليات المالية');
        
            $table->string('currency', 10)->default('USD')
                ->comment('عملة الصندوق (USD, YER, SAR...)');
        
            $table->enum('status', ['active', 'inactive'])->default('active')
                ->comment('حالة الصندوق: نشط أو غير نشط');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashboxes');
    }
};
