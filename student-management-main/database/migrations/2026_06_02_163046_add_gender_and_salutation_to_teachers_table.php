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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('last_name');
            $table->string('salutation')->nullable()->after('first_name'); // e.g. "Dr", "Professor", "Mr", "Miss", "Mrs"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['gender', 'salutation']);
        });
    }
};
