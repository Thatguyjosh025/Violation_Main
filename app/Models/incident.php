<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class incident extends Model
{
    //
    protected $table = 'tb_incidentreport';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_name',
        'student_no',
        'course_section',
        'school_email',
        'faculty_name',
        'faculty_id',
        'violation_type',
        'rule_name',
        'description',
        'severity',
        'remarks',
        'upload_evidence',
        'status',
        'is_visible',
        'Date_Created'
    ];
    public function violation()
    {
        return $this->belongsTo(violation::class, 'violation_type', 'violation_id');
    }


    public $timestamps = false;


}
