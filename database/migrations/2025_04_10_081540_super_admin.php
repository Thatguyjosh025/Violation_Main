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
        
        DB::table('tb_severity')->insert([
            ['severity' => 'Minor A'],
            ['severity' => 'Minor B'],
            ['severity' => 'Minor C'],
            ['severity' => 'Major A'],
            ['severity' => 'Major B'],
            ['severity' => 'Major C'],
        ]);
        
        DB::table('tb_referals')->insert([
            ['referals' => 'Verbal Reprimand']
        ]);

        DB::table('tb_status')->insert([
            ['status' => 'Draft'],
            ['status' => 'Pending'],
            ['status' => 'Under Review'],
            ['status' => 'Confirmed'],
            ['status' => 'Appealed'],
            ['status' => 'Appeal Under Review'],
            ['status' => 'Appeal Approved'],
            ['status' => 'Appeal Denied'],
            ['status' => 'Resolved'],
            ['status' => 'Archived'],
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
