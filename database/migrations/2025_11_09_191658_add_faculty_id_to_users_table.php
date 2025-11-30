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
        Schema::table('users', function (Blueprint $table) {
            // Add faculty_id column (nullable for super admin)
            $table->foreignId('faculty_id')->nullable()->after('role')->constrained('faculties')->onDelete('cascade');
        });

        // Update existing role enum to include faculty_admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'faculty_admin') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropColumn('faculty_id');
        });

        // Revert role enum back to original
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') DEFAULT 'user'");
    }
};
