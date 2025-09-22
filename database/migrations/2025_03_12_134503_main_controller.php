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
            $table->string('student_no')->unique()->nullable();
            // $table->string('course_and_section')->nullable();
            $table->string('password')->nullable();
            $table->enum('role',['student','counselor','discipline','faculty','registar','super'])->nullable();
            $table->enum('status',['active','inactive']);
        });

        Schema::create('tb_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('message');
            $table->string('role');
            $table->string('type');
            $table->string('student_no')->nullable();

            $table->string('student_no')->nullable();
            $table->foreign('student_no')->references('student_no')->on('tb_users');

            $table->boolean('is_read')->default(false);
            $table->string('url')->nullable();
            $table->date('date_created');
            $table->string('created_time');
        });


        DB::table('tb_users')->insert([
            ['firstname' => 'John',
             'lastname' => 'Baybay',
             'email' => 'super@gmail.com',
             'student_no' => 'ALA0158F',
            //  'course_and_section' => 'Faculty',
             'password' => bcrypt('123456789'),
             'role' => 'super',
             'status' => 'active'
            ]
        ]);
         DB::table('tb_users')->insert([
            ['firstname' => 'Josh',
             'lastname' => 'Calinog',
             'email' => 'admin@gmail.com',
             'student_no' => 'ALA0157F',
             'course_and_section' => 'Faculty',
             'password' => bcrypt('123456789'),
             'role' => 'discipline',
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
