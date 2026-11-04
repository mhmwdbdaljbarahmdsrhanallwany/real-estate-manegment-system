
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
        Schema::table('owners', function (Blueprint $table) {

            // هل المالك موجود في القائمة السوداء؟
            // false = غير محظور
            // true = محظور
            $table->boolean('is_blacklisted')
                ->default(false)
                ->after('is_active')
                ->comment('هل المالك في القائمة السوداء؟');

            // سبب الحظر
            $table->text('blacklist_reason')
                ->nullable()
                ->comment('سبب حظر المالك وإضافته إلى القائمة السوداء');

            // تاريخ ووقت الحظر
            $table->timestamp('blacklisted_at')
                ->nullable()
                ->comment('تاريخ ووقت تنفيذ عملية الحظر');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {

            $table->dropColumn([
                'is_blacklisted',
                'blacklist_reason',
                'blacklisted_at',
            ]);

        });
    }
};
