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
        //
        Schema::create('tb_postviolation', function(Blueprint $table){
            $table->id('id');

            $table->string('student_no');
            $table->string('student_name');
            $table->string('course');
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
            //$table->foreign('severity')->references('severity_id')->on('tb_severity');

            $table->string('rule_Name');
            $table->string('description_Name');
            $table->string('faculty_involvement');
            $table->string('counseling_required');
            $table->string('faculty_name');
            $table->string('Remarks',length:500);
            $table->string('upload_evidence');
            $table->date('Date_Created');
        });

        Schema::create('tb_incidentreport', function (Blueprint $table) {
            $table->id();

            $table->string('student_name');
            $table->string('student_no');
            $table->string('course_section');
            $table->string('school_email');
            $table->string('faculty_name');

            $table->unsignedBigInteger('violation_type');
            $table->foreign('violation_type')->references('violation_id')->on('tb_violation');
            
            $table->string('rule_name');
            $table->string('description');
            $table->string('severity');
            $table->string('remarks');

            $table->string('upload_evidence')->nullable();
            $table->date('Date_Created');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tb_postviolation');
        Schema::dropIfExists('tb_incidentreport');

    }
};
