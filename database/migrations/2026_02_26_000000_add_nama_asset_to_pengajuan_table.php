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
        if (Schema::hasTable('pengajuan') && ! Schema::hasColumn('pengajuan', 'nama_asset')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                $table->string('nama_asset')->nullable()->after('asset_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengajuan') && Schema::hasColumn('pengajuan', 'nama_asset')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                $table->dropColumn('nama_asset');
            });
        }
    }
};
