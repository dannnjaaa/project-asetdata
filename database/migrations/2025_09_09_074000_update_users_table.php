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
        // Rename username to name only if username exists
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                // Rename username to name
                $table->renameColumn('username', 'name');
            });
        }

        // Add missing columns safely
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->string('alamat')->nullable();
            }
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable();
            }
        });

        // Add email_verified_at if not exists
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only rename back if 'name' exists and 'username' does not
        if (Schema::hasColumn('users', 'name') && !Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('name', 'username');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'alamat')) {
                $table->dropColumn('alamat');
            }
            if (Schema::hasColumn('users', 'foto')) {
                $table->dropColumn('foto');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
