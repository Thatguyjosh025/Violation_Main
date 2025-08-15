<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rules extends Model {
    protected $table = 'tb_rules';
    protected $primaryKey = 'rule_id';
    public $timestamps = false;

    protected $fillable = ['rule_name','rule_uid', 'description', 'violation_id', 'severity_id'];

    public function violation() {
        return $this->belongsTo(violation::class, 'violation_id', 'violation_id');
    }

    public function severity() {
        return $this->belongsTo(severity::class, 'severity_id', 'severity_id');
    }
}