<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //roles = faculty,counselor,dicipline,registar,student  
    public function up(): void
    {
        Schema::create('tb_users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename')->nullable();
            $table->string('email')->unique();
            $table->string('student_no');
            $table->string('course_and_section')->nullable();
            $table->string('password');
            $table->enum('role',['student','counselor','discipline','faculty','registar','super']);
            $table->enum('status',['active','archive']);
        });

        Schema::create('tb_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('message');
            $table->string('role');
            $table->string('type');
            $table->string('student_no')->nullable();
            $table->boolean('is_read')->default(false);
            $table->date('date_created');
            $table->string('created_time');
        });


        DB::table('tb_users')->insert([
            ['firstname' => 'Josh',
             'lastname' => 'Calinog',
             'middlename' => 'Artamia',
             'email' => 'student@gmail.com',
             'student_no' => '02000782190',
             'course_and_section' => 'BSIT 611',
             'password' => bcrypt('123456789'),
             'role' => 'student',
             'status' => 'active'
            ],
            ['firstname' => 'Vince',
             'lastname' => 'Mangampo',
             'middlename' => 'Nolasco',
             'email' => 'vince@gmail.com',
             'student_no' => '02000782193',
             'course_and_section' => 'BSIT 611',
             'password' => bcrypt('123456789'),
             'role' => 'student',
             'status' => 'active'
            ],

            ['firstname' => 'Jeff',
             'lastname' => 'Caber',
             'middlename' => 'Queen',
             'email' => 'admin@gmail.com',
             'student_no' => '02000782191',
             'course_and_section' => 'Faculty',
             'password' => bcrypt('123456789'),
             'role' => 'discipline',
             'status' => 'active'
            ],
            ['firstname' => 'John',
             'lastname' => 'Baybay',
             'middlename' => 'Renaund',
             'email' => 'super@gmail.com',
             'student_no' => '02000782196',
             'course_and_section' => 'Faculty',
             'password' => bcrypt('123456789'),
             'role' => 'super',
             'status' => 'active'
            ],
            ['firstname' => 'Rod',
             'lastname' => 'Rufino',
             'middlename' => 'Mark',
             'email' => 'faculty@gmail.com',
             'student_no' => '02000782192',
             'course_and_section' => 'Faculty',
             'password' => bcrypt('123456789'),
             'role' => 'faculty',
             'status' => 'active'
            ]
        ]);

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_users');


    }
};
