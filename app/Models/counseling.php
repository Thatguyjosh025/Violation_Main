<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class counseling extends Model
{
    //
    protected $table = 'tb_counseling';
    protected $primaryKey = 'id';
    protected $fillable = [
        'student_no',
        'student_name',
        'violation',
        'status',
        'severity',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'session_notes',
        'emotional_state',
        'behavior_observe',
        'plan_goals',
        'school_email',
    ];
    public $timestamps = false;
    public function statusRelation()
    {
        return $this->belongsTo(sessionstatus::class, 'status', 'id');
    }
}
