<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add student_id column
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_id', 20)->nullable()->unique()->after('id');
        });

        // Add teacher_id column
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('teacher_id', 20)->nullable()->unique()->after('id');
        });

        // Add subject_id column
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('subject_id', 20)->nullable()->unique()->after('id');
        });

        // Backfill existing students
        $students = DB::table('students')->orderBy('id')->get();
        foreach ($students as $i => $student) {
            DB::table('students')->where('id', $student->id)
                ->update(['student_id' => 'STU-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)]);
        }

        // Backfill existing teachers
        $teachers = DB::table('teachers')->orderBy('id')->get();
        foreach ($teachers as $i => $teacher) {
            DB::table('teachers')->where('id', $teacher->id)
                ->update(['teacher_id' => 'TCH-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)]);
        }

        // Backfill existing subjects
        $subjects = DB::table('subjects')->orderBy('id')->get();
        foreach ($subjects as $i => $subject) {
            DB::table('subjects')->where('id', $subject->id)
                ->update(['subject_id' => 'SUB-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('student_id');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('teacher_id');
        });
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('subject_id');
        });
    }
};
