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
        'school_email',
        'year_level',
        'program',
        'violation',
        'guidance_service',
        'priority_risk',
        'status',
        'severity',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'session_notes',
        'emotional_state',
        'plan_goals',
        'parent_session_id',
        'parent_uid'
    ];
    public $timestamps = false;
    public function statusRelation()
    {
        return $this->belongsTo(sessionstatus::class, 'status', 'id');
    }
    public function guidanceServiceRelation()
    {
        return $this->belongsTo(guidanceservice::class, 'guidance_service', 'id');
    }
    public function priorityRiskRelation(){
        return $this->belongsTo(priorityrisk::class, 'priority_risk', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_session_id', 'parent_uid');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_session_id', 'parent_uid');
    }
}