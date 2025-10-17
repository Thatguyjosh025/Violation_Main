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
        Schema::create('tb_violation', function(Blueprint $table){
            $table -> id('violation_id');
            $table -> string('violation_uid')->unique();
            $table -> string('violations', length:255);
            $table->enum('is_visible',['active','inactive']);
        });

        Schema::create('tb_penalties', function(Blueprint $table){
            $table -> id('penalties_id');
            $table -> string('penalties_uid')->unique();
            $table -> string('penalties', length:255);
            $table->enum('is_visible',['active','inactive']);
        });

        Schema::create('tb_status',function(Blueprint $table){
            $table ->id('status_id');
            $table->string('status');
        });


        Schema::create('tb_severity',function(Blueprint $table){
            $table->id('severity_id');
            $table->string('severity');    
        });

        
        Schema::create('tb_rules', function(Blueprint $table){
            $table->id('rule_id');
            $table -> string('rule_uid')->unique();
            $table->unsignedBigInteger('violation_id');
            $table->unsignedBigInteger('severity_id');
            $table->string('rule_name');
            $table->string('description', 500);
            $table->foreign('violation_id')->references('violation_id')->on('tb_violation');
            $table->foreign('severity_id')->references('severity_id')->on('tb_severity');
        });

        Schema::create('tb_referals', callback: function(Blueprint $table){
            $table -> id('referal_id');
            $table -> string("referal_uid")->unique();
            $table -> string('referals');
            $table->enum('is_visible',['active','inactive']);
        });

        Schema::create('tb_audit', function (Blueprint $table) {
            $table->id();
            $table->timestamp('changed_at');
            $table->string('changed_by');

            // $table->string('changed_by_email', 255);
            // $table->foreign('changed_by_email')->references('email')->on('tb_users');

            $table->string('event_type');
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
        });

        Schema::create('tb_sections', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->text('description');    
            $table->timestamps();
        });


        DB::table('tb_violation')->insert([
            ['violation_uid' => 'VO001','violations' => 'Bullying'],
            // ['violation_uid' => 'VO002','violations' => 'Discourtesy or Disrespect'],

            
        ]);

        DB::table('tb_penalties')->insert([
            ['penalties_uid' => 'PE001','penalties' => 'Verbal / Oral Warning'],
            // ['penalties_uid' => 'PE002','penalties' => 'Written Warning'],
            // ['penalties_uid' => 'PE003','penalties' => 'Counseling Session'],
        ]);
        
        DB::table('tb_severity')->insert([
            ['severity' => 'Minor'],
            ['severity' => 'Major A'],
            ['severity' => 'Major B'],
            ['severity' => 'Major C'],
            ['severity' => 'Major D'],
        ]);
        
        DB::table('tb_referals')->insert([
            ['referal_uid' => 'RE001','referals' => 'Verbal Reprimand'],
            // ['referal_uid' => 'RE002','referals' => 'Written Reprimand'],
            // ['referal_uid' => 'RE003','referals' => 'Counseling Session'],
        ]);

        DB::table('tb_status')->insert([
            ['status' => 'Pending'],
            ['status' => 'Under Review'],
            ['status' => 'Confirmed'],
            ['status' => 'Appealed'],
            ['status' => 'Appeal Under Review'],    
            ['status' => 'Appeal Approved'],
            ['status' => 'Appeal Denied'],
            ['status' => 'Resolved'],
        ]);

        DB::table('tb_rules')->insert([
            [
                'rule_uid' => 'RL001',
                'violation_id' => 1,
                'severity_id' => 4, 
                'rule_name' => 'Anti-Bullying',
                'description' => 'STI is committed to providing a healthy learning environment where students support and respect each other. Thus, within the school, it is made clear that bullying will not be tolerated'
            ]
            // [
            //     'violation_id' => 2,
            //     'severity_id' => 4,
            //     'rule_name' => 'Anti-Sexual Harassment Policy',
            //     'description' => 'STI is committed to creating and maintaining an environment where all members of the STI community are free to study without fear of harassment of a sexual nature.'
            // ],
            // [
            //     'violation_id' => 3,
            //     'severity_id' => 1,
            //     'rule_name' => 'Use of School Facilities',
            //     'description' => 'In any incident of destruction, damaging, tampering, or losing of school property, the school reserves the right to charge to the concerned student/s the cost of damage, including labor or repair.'
            // ],
            
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tb_violation');
        Schema::dropIfExists('tb_penalties');
        Schema::dropIfExists('tb_status');
        Schema::dropIfExists('tb_rules');
        Schema::dropIfExists('tb_severity'); 
        Schema::dropIfExists('tb_referals');
        Schema::dropIfExists('tb_audit');
        Schema::dropIfExists('tb_sections');
    }
};
