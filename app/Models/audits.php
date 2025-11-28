<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class audits extends Model
{
    //
    protected $table = 'tb_audit';

    protected $fillable = [
        'changed_at',
        'changed_by',
        // 'changed_by_email',
        'event_type',
        'field_name',
        'old_value',
        'new_value',
        'old_value_text',
        'new_value_text'
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(users::class, 'changed_by', 'id');
    }
    public function newviolation()
    {
        return $this->belongsTo(violation::class, 'new_value', 'violation_id');
    }

    public function newpenalty()
    {
        return $this->belongsTo(penalties::class, 'new_value', 'penalties_id');
    }

    public function newreferral()
    {
        return $this->belongsTo(referals::class, 'new_value', 'referal_id');
    }
    
    public function oldviolation()
    {
        return $this->belongsTo(violation::class, 'old_value', 'violation_id');
    }

    public function oldpenalty()
    {
        return $this->belongsTo(penalties::class, 'old_value', 'penalties_id');
    }

    public function oldreferral()
    {
        return $this->belongsTo(referals::class, 'old_value', 'referal_id');
    }
    
 

}
