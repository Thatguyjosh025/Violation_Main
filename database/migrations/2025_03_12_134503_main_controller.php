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
    public function up(): void
    {
        Schema::create('tb_users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('student_no');
            $table->string('course_and_section')->nullable();
            $table->string('password');
            $table->enum('role',['admin','student','super']);
            $table->enum('status',['active','archive']);
        });


        DB::table('tb_users')->insert([
            ['firstname' => 'Josh',
             'lastname' => 'Calinog',
             'email' => 'student@gmail.com',
             'student_no' => '02000782190',
             'course_and_section' => 'BSIT 611',
             'password' => bcrypt('123456789'),
             'role' => 'student',
             'status' => 'active'
            ],

            ['firstname' => 'Jeff',
             'lastname' => 'Caber',
             'email' => 'admin@gmail.com',
             'student_no' => '02000782191',
             'course_and_section' => 'Faculty',
             'password' => bcrypt('123456789'),
             'role' => 'admin',
             'status' => 'active'
        ],
        ['firstname' => 'Jeff',
        'lastname' => 'Caber',
        'email' => 'admins@gmail.com',
        'student_no' => '02000782196',
        'course_and_section' => 'Faculty',
        'password' => bcrypt('123456789'),
        'role' => 'super',
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
