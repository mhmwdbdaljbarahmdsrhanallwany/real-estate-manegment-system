
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_modules', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100)->unique();
            $table->string('display_name', 150);
            $table->text('description')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 20)->default('ACTIVE')->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_modules');
    }
};
