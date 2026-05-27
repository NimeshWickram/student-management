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

        // 2. Seed Sri Lankan School Subjects (Grade 1-11 related)
        $subjectsData = [
            // Main Campus Subjects
            [
                'name' => 'Sinhala Language', 'code' => 'SIN101', 
                'description' => 'Sri Lankan national language, grammar, reading and literature', 
                'credits' => 3, 'tenant_id' => $tenantMain->id
            ],
            [
                'name' => 'Mathematics', 'code' => 'MATH101', 
                'description' => 'Core school mathematics including arithmetic, geometry, and algebra', 
                'credits' => 4, 'tenant_id' => $tenantMain->id
            ],
            [
                'name' => 'Science', 'code' => 'SCI101', 
                'description' => 'General school science covering chemistry, biology, and physics fundamentals', 
                'credits' => 4, 'tenant_id' => $tenantMain->id
            ],
            [
                'name' => 'English', 'code' => 'ENG101', 
                'description' => 'Second language reading, writing, spelling, and spoken grammar', 
                'credits' => 3, 'tenant_id' => $tenantMain->id
            ],
            [
                'name' => 'History', 'code' => 'HIST101', 
                'description' => 'Sri Lankan heritage, culture, historical dynasties, and world history', 
                'credits' => 2, 'tenant_id' => $tenantMain->id
            ],

            // West Campus Subjects
            [
                'name' => 'Sinhala Language', 'code' => 'SIN201', 
                'description' => 'Sri Lankan national language, grammar, reading and literature', 
                'credits' => 3, 'tenant_id' => $tenantWest->id
            ],
            [
                'name' => 'Mathematics', 'code' => 'MATH201', 
                'description' => 'Core school mathematics including arithmetic, geometry, and algebra', 
                'credits' => 4, 'tenant_id' => $tenantWest->id
            ],
            [
                'name' => 'Science', 'code' => 'SCI201', 
                'description' => 'General school science covering chemistry, biology, and physics fundamentals', 
                'credits' => 4, 'tenant_id' => $tenantWest->id
            ],
            [
                'name' => 'English', 'code' => 'ENG201', 
                'description' => 'Second language reading, writing, spelling, and spoken grammar', 
                'credits' => 3, 'tenant_id' => $tenantWest->id
            ],
            [
                'name' => 'Information & Communication Technology', 'code' => 'ICT201', 
                'description' => 'Basic computer literacy, software applications, and internet safety', 
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
        $subSinMain = Subject::where('code', 'SIN101')->first();
        $subMathMain = Subject::where('code', 'MATH101')->first();
        $subSciMain = Subject::where('code', 'SCI101')->first();
        $subEngMain = Subject::where('code', 'ENG101')->first();
        $subHistMain = Subject::where('code', 'HIST101')->first();

        $subSinWest = Subject::where('code', 'SIN201')->first();
        $subMathWest = Subject::where('code', 'MATH201')->first();
        $subSciWest = Subject::where('code', 'SCI201')->first();
        $subEngWest = Subject::where('code', 'ENG201')->first();
        $subIctWest = Subject::where('code', 'ICT201')->first();

        // 3. Seed Teachers (Sri Lankan school educators)
        $teachersData = [
            // Main Campus Teachers
            [
                'first_name' => 'Anula', 'last_name' => 'Senanayake', 
                'email' => 'anula.senanayake@school.lk', 'phone_number' => '+94 71 111 2222', 
                'subject' => 'Sinhala Language', 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Bandara', 'last_name' => 'Karunaratne', 
                'email' => 'bandara.k@school.lk', 'phone_number' => '+94 77 222 3333', 
                'subject' => 'Science', 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Nimali', 'last_name' => 'Cooray', 
                'email' => 'nimali.cooray@school.lk', 'phone_number' => '+94 76 333 4444', 
                'subject' => 'Mathematics', 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Dilani', 'last_name' => 'Perera', 
                'email' => 'dilani.perera@school.lk', 'phone_number' => '+94 75 444 5555', 
                'subject' => 'English', 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Kusum', 'last_name' => 'Ranasinghe', 
                'email' => 'kusum.r@school.lk', 'phone_number' => '+94 70 555 6666', 
                'subject' => 'History', 'tenant_id' => $tenantMain->id
            ],

            // West Campus Teachers
            [
                'first_name' => 'Kanthi', 'last_name' => 'Jayasekara', 
                'email' => 'kanthi.j@school.lk', 'phone_number' => '+94 78 666 7777', 
                'subject' => 'Sinhala Language', 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Ruwan', 'last_name' => 'Wijewardene', 
                'email' => 'ruwan.w@school.lk', 'phone_number' => '+94 74 777 8888', 
                'subject' => 'Science', 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Priyantha', 'last_name' => 'Fernando', 
                'email' => 'priyantha.f@school.lk', 'phone_number' => '+94 72 888 9999', 
                'subject' => 'Mathematics', 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Sanduni', 'last_name' => 'de Silva', 
                'email' => 'sanduni.desilva@school.lk', 'phone_number' => '+94 71 999 0000', 
                'subject' => 'English', 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Chaminda', 'last_name' => 'Alwis', 
                'email' => 'chaminda.alwis@school.lk', 'phone_number' => '+94 77 123 9876', 
                'subject' => 'Information & Communication Technology', 'tenant_id' => $tenantWest->id
            ],
        ];

        foreach ($teachersData as $t) {
            Teacher::firstOrCreate(
                ['email' => $t['email']],
                $t
            );
        }

        // Get Teachers to associate with Quizzes
        $tAnula = Teacher::where('email', 'anula.senanayake@school.lk')->first();
        $tBandara = Teacher::where('email', 'bandara.k@school.lk')->first();
        $tNimali = Teacher::where('email', 'nimali.cooray@school.lk')->first();
        $tDilani = Teacher::where('email', 'dilani.perera@school.lk')->first();

        $tRuwan = Teacher::where('email', 'ruwan.w@school.lk')->first();
        $tChaminda = Teacher::where('email', 'chaminda.alwis@school.lk')->first();

        // 4. Seed Sri Lankan Students across Grades 1-11
        $studentsData = [
            // Main Academy Students
            [
                'first_name' => 'Tharusha', 'last_name' => 'Dilan', 
                'email' => 'tharusha@main.lk', 'phone_number' => '0771234560', 
                'course' => 'Junior Secondary', 'grade' => 'Grade 9',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Shenal', 'last_name' => 'Jay', 
                'email' => 'shenal@main.lk', 'phone_number' => '0771234561', 
                'course' => 'Ordinary Level', 'grade' => 'Grade 10',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Senuri', 'last_name' => 'Perera', 
                'email' => 'senuri@main.lk', 'phone_number' => '0771234562', 
                'course' => 'Ordinary Level', 'grade' => 'Grade 11',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Minura', 'last_name' => 'Silva', 
                'email' => 'minura@main.lk', 'phone_number' => '0771234563', 
                'course' => 'Primary Education', 'grade' => 'Grade 5',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],
            [
                'first_name' => 'Kavindi', 'last_name' => 'Wickramasinghe', 
                'email' => 'kavindi@main.lk', 'phone_number' => '0771234564', 
                'course' => 'Primary Education', 'grade' => 'Grade 1',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantMain->id
            ],

            // West Branch Students
            [
                'first_name' => 'Prabash', 'last_name' => 'Silva', 
                'email' => 'prabash@west.lk', 'phone_number' => '0779876540', 
                'course' => 'Junior Secondary', 'grade' => 'Grade 9',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Nadeesha', 'last_name' => 'Rathnayake', 
                'email' => 'nadeesha@west.lk', 'phone_number' => '0779876541', 
                'course' => 'Ordinary Level', 'grade' => 'Grade 10',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Sajith', 'last_name' => 'de Silva', 
                'email' => 'sajith@west.lk', 'phone_number' => '0779876542', 
                'course' => 'Ordinary Level', 'grade' => 'Grade 11',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantWest->id
            ],
            [
                'first_name' => 'Hansini', 'last_name' => 'Fernando', 
                'email' => 'hansini@west.lk', 'phone_number' => '0779876543', 
                'course' => 'Primary Education', 'grade' => 'Grade 3',
                'password' => bcrypt('student123'), 'tenant_id' => $tenantWest->id
            ],
        ];

        foreach ($studentsData as $st) {
            Student::firstOrCreate(
                ['email' => $st['email']],
                $st
            );
        }

        // 5. Seed localized school quizzes targeted at specific grades
        // Quiz 1 (Main Academy): Sinhala Grammar - Grade 1
        $mcqSinhalaG1 = [
            [
                'question' => 'සිංහල හෝඩියේ මුල්ම අකුර කුමක්ද?',
                'options' => ['ආ', 'අ', 'ඇ', 'ඉ'],
                'correct_option' => 1
            ],
            [
                'question' => 'මල් වලට එන සතා කවුද?',
                'options' => ['බල්ලා', 'පූසා', 'සමනලයා', 'සිංහයා'],
                'correct_option' => 2
            ]
        ];
        Quiz::firstOrCreate(
            ['title' => 'Sinhala Language Basics - Grade 1'],
            [
                'teacher_id' => $tAnula->id,
                'subject_id' => $subSinMain->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 1',
                'manual_content' => json_encode($mcqSinhalaG1),
                'tenant_id' => $tenantMain->id
            ]
        );

        // Quiz 2 (Main Academy): Science - Grade 5
        $mcqSciG5 = [
            [
                'question' => 'Which part of the plant makes food?',
                'options' => ['Root', 'Stem', 'Leaf', 'Flower'],
                'correct_option' => 2
            ],
            [
                'question' => 'What is the main source of light on Earth?',
                'options' => ['Moon', 'Stars', 'Sun', 'Electric Bulb'],
                'correct_option' => 2
            ]
        ];
        Quiz::firstOrCreate(
            ['title' => 'Science & Our Environment - Grade 5'],
            [
                'teacher_id' => $tBandara->id,
                'subject_id' => $subSciMain->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 5',
                'manual_content' => json_encode($mcqSciG5),
                'tenant_id' => $tenantMain->id
            ]
        );

        // Quiz 3 (Main Academy): Mathematics - Grade 9
        $mcqMathG9 = [
            [
                'question' => 'What is the value of 5x + 3 = 18?',
                'options' => ['x = 2', 'x = 3', 'x = 4', 'x = 5'],
                'correct_option' => 1
            ],
            [
                'question' => 'What is the perimeter of a square with side length 6cm?',
                'options' => ['12cm', '18cm', '24cm', '36cm'],
                'correct_option' => 2
            ]
        ];
        Quiz::firstOrCreate(
            ['title' => 'Algebra & Equations - Grade 9'],
            [
                'teacher_id' => $tNimali->id,
                'subject_id' => $subMathMain->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 9',
                'manual_content' => json_encode($mcqMathG9),
                'tenant_id' => $tenantMain->id
            ]
        );

        // Quiz 4 (Main Academy): English - Grade 11
        $mcqEngG11 = [
            [
                'question' => 'Choose the correctly spelled word:',
                'options' => ['Successfully', 'Sucessfully', 'Succesfully', 'Succesfulli'],
                'correct_option' => 0
            ],
            [
                'question' => 'Which is the correct passive voice of: "The boy broke the glass"?',
                'options' => ['The glass is broken by the boy.', 'The glass was broken by the boy.', 'The glass was break by the boy.', 'The glass had broken by the boy.'],
                'correct_option' => 1
            ]
        ];
        Quiz::firstOrCreate(
            ['title' => 'English Grammar & Vocabulary - Grade 11'],
            [
                'teacher_id' => $tDilani->id,
                'subject_id' => $subEngMain->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 11',
                'manual_content' => json_encode($mcqEngG11),
                'tenant_id' => $tenantMain->id
            ]
        );

        // Quiz 5 (West Branch): Science - Grade 9
        Quiz::firstOrCreate(
            ['title' => 'Science Matters - Grade 9'],
            [
                'teacher_id' => $tRuwan->id,
                'subject_id' => $subSciWest->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 9',
                'manual_content' => json_encode($mcqSciG5),
                'tenant_id' => $tenantWest->id
            ]
        );

        // Quiz 6 (West Branch): ICT - Grade 9
        $mcqIctG9 = [
            [
                'question' => 'Which of the following is the brain of a computer?',
                'options' => ['RAM', 'Hard Disk', 'CPU', 'Monitor'],
                'correct_option' => 2
            ],
            [
                'question' => 'What does RAM stand for?',
                'options' => ['Read Access Memory', 'Random Access Memory', 'Rapid Active Memory', 'Read Active Memory'],
                'correct_option' => 1
            ]
        ];
        Quiz::firstOrCreate(
            ['title' => 'Computer Hardware Essentials - Grade 9'],
            [
                'teacher_id' => $tChaminda->id,
                'subject_id' => $subIctWest->id,
                'quiz_type' => 'manual_mcq',
                'grade' => 'Grade 9',
                'manual_content' => json_encode($mcqIctG9),
                'tenant_id' => $tenantWest->id
            ]
        );
    }
}
