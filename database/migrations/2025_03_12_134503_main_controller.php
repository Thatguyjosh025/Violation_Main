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
            $table->string('email')->unique();
            $table->string(column: 'student_no')->unique()->nullable();
            // $table->string('course_and_section')->nullable();
            $table->string('password')->nullable();
            $table->enum('role',['student','counselor','discipline','faculty','head','super'])->nullable();
            $table->enum('status',['active','inactive']);
        });

        Schema::create('tb_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('message');
            $table->string('role');
            $table->string('type');

            $table->string('student_no')->nullable();
            $table->foreign('student_no')->references('student_no')->on('tb_users');

            $table->string('school_email');
            $table->foreign('school_email')->references('email')->on('tb_users');

            $table->boolean('is_read')->default(false);
            $table->string('url')->nullable();
            $table->date('date_created');
            $table->string('created_time');
        });
        

       DB::table('tb_users')->insert([
            [
                'firstname' => env('SUPERADMIN_FIRSTNAME'),
                'lastname' => env('SUPERADMIN_LASTNAME'),
                'email' => env('SUPERADMIN_EMAIL'),
                'student_no' => env('SUPERADMIN_STUDENT_NO'),
                // 'course_and_section' => 'Faculty',
                'password' => bcrypt(env('SUPERADMIN_PASSWORD')),
                'role' => env('SUPERADMIN_ROLE'),
                'status' => env('SUPERADMIN_STATUS')
            ],
            // [
            //     'firstname' => 'Josh',
            //     'lastname' => 'Calinog',
            //     'email' => 'discipline@alabang.sti.edu.ph',
            //     'student_no' => 'ALA0157F',
            //     // 'course_and_section' => 'Faculty',
            //     'password' => bcrypt('123456789'),
            //     'role' => 'discipline',
            //     'status' => 'active'
            // ],
            // [
            //     'firstname' => 'Arvin',
            //     'lastname' => 'Marlin',
            //     'email' => 'faculty@alabang.sti.edu.ph',
            //     'student_no' => 'ALA0153F',
            //     // 'course_and_section' => 'Faculty',
            //     'password' => bcrypt('123456789'),
            //     'role' => 'faculty',
            //     'status' => 'active'
            // ],
            // [
            //     'firstname' => 'Mark Jecil',
            //     'lastname' => 'Bausa',
            //     'email' => 'counselor@alabang.sti.edu.ph',
            //     'student_no' => 'ALA0159F',
            //     // 'course_and_section' => 'Faculty',
            //     'password' => bcrypt('123456789'),
            //     'role' => 'counselor',
            //     'status' => 'active'
            // ],
            // [
            //     'firstname' => 'Ricson',
            //     'lastname' => 'Ricardo',
            //     'email' => 'academichead@alabang.sti.edu.ph',
            //     'student_no' => 'ALA0151F',
            //     // 'course_and_section' => 'Faculty',
            //     'password' => bcrypt('123456789'),
            //     'role' => 'head',
            //     'status' => 'active'
            // ],
            // [
            //     'firstname' => 'Vince Ivan',
            //     'lastname' => 'Mangampo',
            //     'email' => 'mangampo.311699@alabang.sti.edu.ph',
            //     'student_no' => '200311699',
            //     // 'course_and_section' => 'Faculty',
            //     'password' => bcrypt('123456789'),
            //     'role' => 'student',
            //     'status' => 'active'
            // ]
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
