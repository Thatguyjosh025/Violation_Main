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
    ];
    public $timestamps = false;


}
