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
            $table -> string('violations', length:255);
        });

        Schema::create('tb_penalties', function(Blueprint $table){
            $table -> id('penalties_id');
            $table -> string('penalties', length:255);
        });

        Schema::create('tb_status',function(Blueprint $table){
            $table ->id('status_id');
            $table->string('status');
        });


        Schema::create('tb_severity',function(Blueprint $table){
            $table->id('severity_id');
            $table->string('severity');    
        });

        
        Schema::create('tb_rules',function(Blueprint $table){
            $table -> id('rule_id');
            $table -> unsignedBigInteger('violation_id');
            $table -> unsignedBigInteger('severity_id');
            $table -> string('rule_name');
            $table -> string('description', length:500);
            
            $table->foreign('severity_id')->references('severity_id')->on('tb_severity');
            $table -> foreign('violation_id')->references('violation_id')->on('tb_violation');
        });

        Schema::create('tb_referals', function(Blueprint $table){
            $table -> id('referal_id');
            $table -> string('referals');
        });


        DB::table('tb_violation')->insert([
            ['violations' => 'Bullying'],
            ['violations' => 'Discourtesy or Disrespect'],
            ['violations' => 'Improper Use of School Facilities/Equipment']
            // ['violations' => 'Cheating'],
            // ['violations' => 'Stealing/Tampering or Forgery of school records'],
            // ['violations' => 'Improper Use of School Facilities/Equipment'],
            // ['violations' => 'Discourtesy or Disrespect'],
            // ['violations' => 'Usage of Electronic Mobile Phone during class'],
            // ['violations' => 'Gambling'],
            // ['violations' => 'Bullying'],
            // ['violations' => 'Bringing/Usage of prohibited items inside the campus'],
            // ['violations' => 'Public Display of Affection/Lascivious acts'],
        ]);

        DB::table('tb_penalties')->insert([
            ['penalties' => 'Verbal / Oral Warning'],
            ['penalties' => 'Written Apology'],
            ['penalties' => 'Written Reprimand'],
            ['penalties' => 'Corrective Reinforcement'],
            ['penalties' => 'Conference with the Dicipline Committee'],
            ['penalties' => 'Suspension(a.Suspension from class | b. Preventive Suspension)'],
            ['penalties' => 'Non-readmission'],
            ['penalties' => 'Exlcusion'],
            ['penalties' => 'Expulsion'],
        ]);
        
        DB::table('tb_severity')->insert([
            ['severity' => 'Minor'],
            ['severity' => 'Major A'],
            ['severity' => 'Major B'],
            ['severity' => 'Major C'],
            ['severity' => 'Major D'],

        ]);
        
        DB::table('tb_referals')->insert([
            ['referals' => 'Verbal Reprimand'],
            ['referals' => 'Held conference with the student'],
            ['referals' => 'Consulted DO/GA'],
            ['referals' => 'Contacted Parents'],
            ['referals' => 'Held Conference with the Parent'],
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
                'violation_id' => 1,
                'severity_id' => 4, 
                'rule_name' => 'Anti-Bullying',
                'description' => 'STI is committed to providing a healthy learning environment where students support and respect each other. Thus, within the school, it is made clear that bullying will not be tolerated'
            ],
            [
                'violation_id' => 2,
                'severity_id' => 4,
                'rule_name' => 'Anti-Sexual Harassment Policy',
                'description' => 'STI is committed to creating and maintaining an environment where all members of the STI community are free to study without fear of harassment of a sexual nature.'
            ],
            [
                'violation_id' => 3,
                'severity_id' => 1,
                'rule_name' => 'Use of School Facilities',
                'description' => 'In any incident of destruction, damaging, tampering, or losing of school property, the school reserves the right to charge to the concerned student/s the cost of damage, including labor or repair.'
            ],
            
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
    }
};
