
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')
                ->nullable()
                ->after('module');

            $table->foreign('module_id')
                ->references('id')
                ->on('permission_modules')
                ->restrictOnDelete();
        });

        $modules = DB::table('permission_modules')
            ->pluck('id', 'name');

        $permissions = DB::table('permissions')
            ->get(['id', 'module']);

        foreach ($permissions as $permission) {
            $moduleName = $permission->module;

            if (!$moduleName || !$modules->has($moduleName)) {
                throw new RuntimeException(
                    "الوحدة [{$moduleName}] غير موجودة في جدول permission_modules."
                );
            }

            DB::table('permissions')
                ->where('id', $permission->id)
                ->update([
                    'module_id' => $modules->get($moduleName),
                ]);
        }

        if (
            DB::table('permissions')
                ->whereNull('module_id')
                ->exists()
        ) {
            throw new RuntimeException(
                'توجد صلاحيات لم ترتبط بوحدة؛ أُوقف حذف العمود القديم.'
            );
        }

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('module');
        });
// // هنا التعديل
// Schema::table('permissions', function (Blueprint $table) {
//     $table->dropIndex('permissions_module_index');
//     $table->dropColumn('module');
// });

        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')
                ->nullable(false)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module', 100)->nullable();
        });

        $modules = DB::table('permission_modules')
            ->pluck('name', 'id');

        foreach (DB::table('permissions')->get(['id', 'module_id']) as $permission) {
            DB::table('permissions')
                ->where('id', $permission->id)
                ->update([
                    'module' => $modules->get($permission->module_id),
                ]);
        }

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn('module_id');
        });
    }
};
