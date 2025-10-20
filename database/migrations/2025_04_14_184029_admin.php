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
        Schema::create('tb_postviolation', function(Blueprint $table){
            $table->id('id');

            // $table->string('student_no');

            $table->string('student_no');
            $table->foreign('student_no')->references('student_no')->on('tb_users');

            $table->string('student_name');
            // $table->string('course');
            $table->string('school_email');
            $table->unsignedBigInteger('violation_type');
            $table->unsignedBigInteger('penalty_type');
            $table->string('severity_Name');
            $table->unsignedBigInteger('status_name');
            $table->unsignedBigInteger('referal_type');

            $table->foreign('referal_type')->references('referal_id')->on('tb_referals');
            $table->foreign('violation_type')->references('violation_id')->on('tb_violation');
            $table->foreign('penalty_type')->references('penalties_id')->on('tb_penalties');
            $table->foreign('status_name')->references('status_id')->on('tb_status');

            $table->string('rule_Name');
            $table->string('description_Name');
            $table->string('faculty_involvement');
            $table->string('counseling_required');
            $table->string('faculty_name');
            $table->string('Remarks',length:500);
            $table->string('Notes',length:500)->nullable();
            $table->string('appeal');
            $table->json('upload_evidence')->nullable();
            $table->json('appeal_evidence')->nullable();
            $table->dateTime('Date_Created');
            $table->date('Update_at');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_admitted')->default(false);
        });
        
        Schema::create('tb_incidentreport', function (Blueprint $table) {
            $table->id();

            $table->string('student_name');
            // $table->string('student_no');

            $table->string('student_no');   
            $table->foreign('student_no')->references('student_no')->on('tb_users');

            // $table->string('course_section');
            $table->string('school_email');
            $table->string('faculty_name');
            $table->string('faculty_id');

            $table->unsignedBigInteger('violation_type');
            $table->foreign('violation_type')->references('violation_id')->on('tb_violation');
            
            $table->string('rule_name');
            $table->string('description');
            $table->string('severity');
            $table->string('remarks');
            $table->string('status');

            $table->json('upload_evidence')->nullable();
            $table->string('is_visible');
            $table->date('Date_Created');


        });

        Schema::create('tb_counselingstatus', function (Blueprint $table) {
            $table->id();
            $table->string('session_status');
        });

        Schema::create('tb_counseling', function (Blueprint $table) {
            $table->id('id');
            $table->string('student_no');   
            $table->string('student_name');
            $table->string('school_email');   
            $table->string('violation');

            $table->unsignedBigInteger('status');
            $table->string('severity');

            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->string('start_time');
            $table->string('end_time');

            $table->string('session_notes',length:550)->nullable();
            $table->string('emotional_state',length:550)->nullable();
            $table->string('behavior_observe',length:550)->nullable();
            $table->string('plan_goals',length:550)->nullable();

            $table->foreign('school_email')->references('email')->on('tb_users');
            $table->foreign('status')->references('id')->on('tb_counselingstatus');

        });

        DB::table('tb_counselingstatus')->insert([
            ['session_status' => 'Pending Intake'],
            ['session_status' => 'Scheduled'],
            ['session_status' => 'In Session'],
            ['session_status' => 'Follow-Up Needed'],
            ['session_status' => 'Resolved'],
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tb_postviolation');
        Schema::dropIfExists('tb_incidentreport');
        Schema::dropIfExists('tb_counseling');
        Schema::dropIfExists('tb_counseling-status');

    }
};
