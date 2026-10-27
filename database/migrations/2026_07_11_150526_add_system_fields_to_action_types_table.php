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
        Schema::table('action_types', function (Blueprint $table) {
    
            if (!Schema::hasColumn('action_types', 'is_system')) {
    
                $table->boolean('is_system')
                    ->default(false)
                    ->comment('إجراء تابع للنظام لا يمكن تعديله أو حذفه');
    
            }
    
        });
    }
    
    
    public function down(): void
    {
        Schema::table('action_types', function (Blueprint $table) {
    
            if (Schema::hasColumn('action_types', 'is_system')) {
    
                $table->dropColumn('is_system');
    
            }
    
        });
    }
};
