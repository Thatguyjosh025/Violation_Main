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
            $table->unsignedBigInteger('changed_by');
            $table->foreign('changed_by')->references('id')->on('tb_users')->onDelete('cascade');
            // $table->string('changed_by_email', 255);
            // $table->foreign('changed_by_email')->references('email')->on('tb_users');
            // $table->string('changed_by');
            
            $table->string('event_type');
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('old_value_text')->nullable();
            $table->text('new_value_text')->nullable();
        });

        Schema::create('tb_sections', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->text('description');    
            $table->timestamps();
        });


        DB::table('tb_violation')->insert([
            ['violation_uid' => 'VO001','violations' => 'Loitering and disruption of classses'],
            ['violation_uid' => 'VO002','violations' => 'Improper Uniform'],
            ['violation_uid' => 'VO003','violations' => 'Excesssive Tardiness'],
            ['violation_uid' => 'VO004','violations' => 'Stealing/Tampering or Forgery of school records'],
            ['violation_uid' => 'VO005','violations' => 'Discourtesy or Disrespect '],
            ['violation_uid' => 'VO006','violations' => 'Gambling'],
            ['violation_uid' => 'VO007','violations' => 'Bullying '],
            
        ]);

        DB::table('tb_penalties')->insert([
            ['penalties_uid' => 'PE001','penalties' => 'Verbal/Oral Warning'],
            ['penalties_uid' => 'PE002','penalties' => 'Written Apology'],
            ['penalties_uid' => 'PE003','penalties' => 'Written Reprimand'],
            ['penalties_uid' => 'PE004','penalties' => 'Corrective Reinforcement'],
            ['penalties_uid' => 'PE005','penalties' => 'Suspension'],
            ['penalties_uid' => 'PE006','penalties' => 'Non-readmission'],
            ['penalties_uid' => 'PE007','penalties' => 'Exclusion'],
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
            ['referal_uid' => 'RE002','referals' => 'Held conference with the student'],
            ['referal_uid' => 'RE003','referals' => 'Consulted DO/GA'],
            ['referal_uid' => 'RE004','referals' => 'Contacted Parents'],
            ['referal_uid' => 'RE005','referals' => 'Held Conference with the Parent'],
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
                'violation_id' => 1, // Loitering and disruption of classes
                'severity_id' => 2, // Major A
                'rule_name' => 'No Loitering',
                'description' => 'Students must not loiter or disrupt classes to maintain a focused learning environment.'
            ],
            [
                'rule_uid' => 'RL002',
                'violation_id' => 2, // Improper Uniform
                'severity_id' => 1, // Minor
                'rule_name' => 'Proper Attire',
                'description' => 'Students are required to wear the correct school uniform at all times.'
            ],
            [
                'rule_uid' => 'RL003',
                'violation_id' => 3, // Excessive Tardiness
                'severity_id' => 2, // Major A
                'rule_name' => 'Punctuality',
                'description' => 'Arriving late repeatedly disrupts the class. Students must be punctual.'
            ],
            [
                'rule_uid' => 'RL004',
                'violation_id' => 4, // Stealing/Tampering or Forgery of records
                'severity_id' => 5, // Major D
                'rule_name' => 'No Tampering',
                'description' => 'Stealing or tampering with school records is strictly forbidden.'
            ],
            [
                'rule_uid' => 'RL005',
                'violation_id' => 5, // Discourtesy or Disrespect
                'severity_id' => 3, // Major B
                'rule_name' => 'Respect Others',
                'description' => 'Students must demonstrate courtesy and respect toward all staff and peers.'
            ],
            [
                'rule_uid' => 'RL006',
                'violation_id' => 6, // Gambling
                'severity_id' => 4, // Major C
                'rule_name' => 'No Gambling',
                'description' => 'Gambling in any form on school grounds is prohibited.'
            ],
            [
                'rule_uid' => 'RL007',
                'violation_id' => 7, // Bullying
                'severity_id' => 4, // Major C
                'rule_name' => 'Anti-Bullying',
                'description' => 'Bullying, harassment, or intimidation of students is strictly not tolerated.'
            ]
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
