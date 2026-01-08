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
        if (Schema::hasTable('pengajuan') && !Schema::hasColumn('pengajuan', 'user_id')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                // add nullable user_id and foreign key to users
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengajuan') && Schema::hasColumn('pengajuan', 'user_id')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                // drop constrained foreign id if present
                if (method_exists($table, 'dropConstrainedForeignId')) {
                    $table->dropConstrainedForeignId('user_id');
                } else {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};
