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
        //
        Schema::create('tb_counselingstatus', function (Blueprint $table) {
            $table->id('id');
            $table->string('session_status');
        });

        Schema::create('tb_guidanceservice', function (Blueprint $table) {
            $table->id('id');
            $table->string('guidance_service');
        });

        Schema::create('tb_priorityrisk', function (Blueprint $table) {
            $table->id('id');
            $table->string('priority_risk');
        });

        Schema::create('tb_counseling', function (Blueprint $table) {
            $table->id('id');
            $table->string('student_no');   
            $table->string('student_name');
            $table->string('school_email');
            $table->string('year_level')->nullable();
            $table->string('program')->nullable();   
            $table->string('violation')->nullable();

            $table->string('parent_uid')->nullable(); 
            $table->string('parent_session_id')->nullable();

            $table->unsignedBigInteger('guidance_service');
            $table->unsignedBigInteger('priority_risk');
            $table->unsignedBigInteger('status');
            $table->string('severity')->nullable();
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->string('start_time');
            $table->string('end_time');

            $table->string('session_notes', 550)->nullable();
            $table->string('emotional_state', 550)->nullable();
            $table->string('plan_goals', 550)->nullable();

            $table->foreign('school_email')->references('email')->on('tb_users');
            $table->foreign('status')->references('id')->on('tb_counselingstatus');
            $table->foreign('guidance_service')->references('id')->on('tb_guidanceservice');
            $table->foreign('priority_risk')->references('id')->on('tb_priorityrisk');
        });

        DB::table('tb_counselingstatus')->insert([
            ['session_status' => 'Pending Intake'],
            ['session_status' => 'Scheduled'],
            ['session_status' => 'In Session'],
            ['session_status' => 'Follow-Up Needed'],
            ['session_status' => 'Resolved'],
        ]);
        
        DB::table('tb_guidanceservice')->insert([
            ['guidance_service' => 'Career guidance service'],
            ['guidance_service' => 'Counseling'],
            ['guidance_service' => 'Consultation'],
            ['guidance_service' => 'Information Service'],
            ['guidance_service' => 'Referral service'],
        ]);

        DB::table('tb_priorityrisk')->insert([
            ['priority_risk' => 'Low risk'],
            ['priority_risk' => 'Moderate risk'],
            ['priority_risk' => 'High risk'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tb_counseling');
        Schema::dropIfExists('tb_counselingstatus');
        Schema::dropIfExists('tb_guidanceservice');
        Schema::dropIfExists('tb_priorityrisk');
    }
};