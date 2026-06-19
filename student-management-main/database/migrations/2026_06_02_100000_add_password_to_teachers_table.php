<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('password')->nullable()->after('subject');
            $table->rememberToken()->after('password');
        });

        // Set default password for existing teachers
        \App\Models\Teacher::whereNull('password')->update([
            'password' => bcrypt('teacher123'),
        ]);
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token']);
        });
    }
};
