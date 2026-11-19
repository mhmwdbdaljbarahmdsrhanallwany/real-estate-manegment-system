
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('status_before_cancellation', 30)
                ->nullable()
                ->after('status');

            $table->timestamp('cancelled_at')
                ->nullable()
                ->after('status_before_cancellation');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'status_before_cancellation',
                'cancelled_at',
            ]);
        });
    }
};
