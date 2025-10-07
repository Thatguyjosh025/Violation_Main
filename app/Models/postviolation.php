<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class postviolation extends Model
{
    protected $table = 'tb_postviolation';
    protected $primaryKey = 'id';
    protected $fillable = [
        'student_no',
        'student_name',
        // 'course',
        'school_email',
        'violation_type',
        'penalty_type',
        'severity_Name',
        'status_name',
        'rule_Name',
        'description_Name',
        'faculty_involvement',
        'faculty_name',
        'counseling_required',
        'referal_type',
        'Remarks',
        'Notes',
        'upload_evidence',
        'appeal_evidence',
        'appeal',
        'Date_Created',
        'Time_Created',
        'Update_at'
    ];

    public $timestamps = false;

    public function referal()
    {
        return $this->belongsTo(referals::class, 'referal_type', 'referal_id');
    }

    public function violation()
    {
        return $this->belongsTo(violation::class, 'violation_type', 'violation_id');
    }

    public function penalty()
    {
        return $this->belongsTo(penalties::class, 'penalty_type', 'penalties_id');
    }

    public function status()
    {
        return $this->belongsTo(statuses::class, 'status_name', 'status_id');
    }
}
