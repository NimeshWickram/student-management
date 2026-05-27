<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Quiz;

class TeacherSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Campus Tenants
        $tenantMain = Tenant::firstOrCreate(
            ['name' => 'Main Academy']
        );

        $tenantWest = Tenant::firstOrCreate(
            ['name' => 'West Branch']
        );

        // 2. Seed Subjects for both tenants
        $subjectsData = [
            // Main Campus Subjects
            [
                'name' => 'Computer Science', 'code' => 'CS101', 
                'description' => 'Fundamentals of computing, algorithms and programming', 
                'credits' => 4, 'tenant_id' => $tenantMain->id
            ],
            [
                'name' => 'Mathematics', 'code' => 'MATH201', 
                'description' => 'Advanced calculus, linear algebra and statistics', 
                'credits' => 3, 'tenant_id' => $tenantMain->id
            ],
            [
                'name' => 'Physics', 'code' => 'PHY101', 
                'description' => 'Mechanics, thermodynamics and electromagnetism', 
                'credits' => 3, 'tenant_id' => $tenantMain->id
            ],
            [
                'name' => 'English', 'code' => 'ENG101', 
                'description' => 'Academic writing, communication and literature', 
                'credits' => 2, 'tenant_id' => $tenantMain->id
            ],
            // West Campus Subjects
            [
                'name' => 'Software Engineering', 'code' => 'SE201', 
                'description' => 'Software development lifecycle, design patterns and testing', 
                'credits' => 4, 'tenant_id' => $tenantWest->id
            ],
            [
                'name' => 'Information Technology', 'code' => 'IT101', 
                'description' => 'IT infrastructure, databases and web technologies', 
                'credits' => 3, 'tenant_id' => $tenantWest->id
            ],
            [
                'name' => 'Business Administration', 'code' => 'BA101', 
                'description' => 'Management principles, marketing and finance', 
                'credits' => 2, 'tenant_id' => $tenantWest->id
            ],
        ];

        foreach ($subjectsData as $s) {
            Subject::firstOrCreate(
                ['code' => $s['code'], 'tenant_id' => $s['tenant_id']],
                $s
            );
        }

        // Retrieve subjects to associate them
        $subCS = Subject::where('code', 'CS101')->first();
        $subMath = Subject::where('code', 'MATH201')->first();
        $subPhys = Subject::where('code', 'PHY101')->first();
        $subEng = Subject::where('code', 'ENG101')->first();

        $subSE = Subject::where('code', 'SE201')->first();
        $subIT = Subject::where('code', 'IT101')->first();
        $subBA = Subject::where('code', 'BA101')->first();

        // 3. Seed Teachers for Main and West
        $teachersData = [
            // Main Campus Teachers
            [
                'first_name' => 'Kamal', 'last_name' => 'Perera', 
                'email' => 'kamal.perera@school.lk', 'phone_number' => '+94 71 234 5678', 
                'subject' => 'Computer Science', 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Nimali', 'last_name' => 'Fernando', 
                'email' => 'nimali.fernando@school.lk', 'phone_number' => '+94 77 345 6789', 
                'subject' => 'Mathematics', 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Ruwan', 'last_name' => 'Jayawardena', 
                'email' => 'ruwan.jayawardena@school.lk', 'phone_number' => '+94 76 456 7890', 
                'subject' => 'Physics', 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Sanduni', 'last_name' => 'Silva', 
                'email' => 'sanduni.silva@school.lk', 'phone_number' => '+94 75 567 8901', 
                'subject' => 'English', 'tenant_id' => $tenantMain->id
            ],
            // West Campus Teachers
            [
                'first_name' => 'Chaminda', 'last_name' => 'Rathnayake', 
                'email' => 'chaminda.rathnayake@school.lk', 'phone_number' => '+94 78 890 1234', 
                'subject' => 'Software Engineering', 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Iresha', 'last_name' => 'Bandara', 
                'email' => 'iresha.bandara@school.lk', 'phone_number' => '+94 74 901 2345', 
                'subject' => 'Information Technology', 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Dilani', 'last_name' => 'Wickramasinghe', 
                'email' => 'dilani.wickramasinghe@school.lk', 'phone_number' => '+94 72 789 0123', 
                'subject' => 'Business Administration', 'tenant_id' => $tenantWest->id
            ],
        ];

        foreach ($teachersData as $t) {
            Teacher::firstOrCreate(
                ['email' => $t['email']],
                $t
            );
        }

        // Get some Teachers
        $tKamal = Teacher::where('email', 'kamal.perera@school.lk')->first();
        $tNimali = Teacher::where('email', 'nimali.fernando@school.lk')->first();
        $tChaminda = Teacher::where('email', 'chaminda.rathnayake@school.lk')->first();
        $tIresha = Teacher::where('email', 'iresha.bandara@school.lk')->first();

        // 4. Seed Students with assigned grades ("Grade 9" or "Grade 10")
        $studentsData = [
            // Main Academy Students
            [
                'first_name' => 'Tharusha', 'last_name' => 'Dilan', 
                'email' => 'tharusha@main.lk', 'phone_number' => '0771234560', 
                'course' => 'Computer Science', 'grade' => 'Grade 9',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Shenal', 'last_name' => 'Jay', 
                'email' => 'shenal@main.lk', 'phone_number' => '0771234561', 
                'course' => 'Computer Science', 'grade' => 'Grade 10',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Senuri', 'last_name' => 'Perera', 
                'email' => 'senuri@main.lk', 'phone_number' => '0771234562', 
                'course' => 'Information Technology', 'grade' => 'Grade 9',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],
            // West Branch Students
            [
                'first_name' => 'Prabash', 'last_name' => 'Silva', 
                'email' => 'prabash@west.lk', 'phone_number' => '0779876540', 
                'course' => 'Software Engineering', 'grade' => 'Grade 9',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Nadeesha', 'last_name' => 'Rathnayake', 
                'email' => 'nadeesha@west.lk', 'phone_number' => '0779876541', 
                'course' => 'Software Engineering', 'grade' => 'Grade 10',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantWest->id
            ],
        ];

        foreach ($studentsData as $st) {
            Student::firstOrCreate(
                ['email' => $st['email']],
                $st
            );
        }

        // 5. Seed some realistic MCQ Quizzes target scoped to specific campus + grade!
        $mcqContentGrade9 = [
            [
                'question' => 'What does CPU stand for?',
                'options' => ['Central Process Unit', 'Central Processing Unit', 'Computer Personal Unit', 'Central Processor Utility'],
                'correct_option' => 1
            ],
            [
                'question' => 'Which of the following is an input device?',
                'options' => ['Monitor', 'Printer', 'Keyboard', 'Speaker'],
                'correct_option' => 2
            ]
        ];

        $mcqContentGrade10 = [
            [
                'question' => 'What is the speed of light in vacuum?',
                'options' => ['3 x 10^8 m/s', '3 x 10^6 m/s', '1.5 x 10^8 m/s', '300,000 m/s'],
                'correct_option' => 0
            ],
            [
                'question' => 'Which protocol is used to secure web communications?',
                'options' => ['HTTP', 'FTP', 'HTTPS', 'SMTP'],
                'correct_option' => 2
            ]
        ];

        // Quiz 1: Main Academy - Grade 9 - CS
        Quiz::firstOrCreate(
            ['title' => 'Introduction to Computing - Grade 9'],
            [
                'teacher_id' => $tKamal->id,
                'subject_id' => $subCS->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 9',
                'manual_content' => json_encode($mcqContentGrade9),
                'tenant_id' => $tenantMain->id
            ]
        );

        // Quiz 2: Main Academy - Grade 10 - Maths
        Quiz::firstOrCreate(
            ['title' => 'Algebra & Equations - Grade 10'],
            [
                'teacher_id' => $tNimali->id,
                'subject_id' => $subMath->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 10',
                'manual_content' => json_encode($mcqContentGrade10),
                'tenant_id' => $tenantMain->id
            ]
        );

        // Quiz 3: West Branch - Grade 9 - Software Eng
        Quiz::firstOrCreate(
            ['title' => 'Software Patterns Intro - Grade 9'],
            [
                'teacher_id' => $tChaminda->id,
                'subject_id' => $subSE->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 9',
                'manual_content' => json_encode($mcqContentGrade9),
                'tenant_id' => $tenantWest->id
            ]
        );

        // Quiz 4: West Branch - Grade 10 - IT
        Quiz::firstOrCreate(
            ['title' => 'Database Essentials - Grade 10'],
            [
                'teacher_id' => $tIresha->id,
                'subject_id' => $subIT->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 10',
                'manual_content' => json_encode($mcqContentGrade10),
                'tenant_id' => $tenantWest->id
            ]
        );
    }
}
