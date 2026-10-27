<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->string('event_key', 191)
                ->nullable()
                ->unique('actions_event_key_unique')
                ->comment('مفتاح فريد لمنع تكرار الحدث');
        });
    }

    public function down(): void
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropUnique('actions_event_key_unique');
            $table->dropColumn('event_key');
        });
    }
};
