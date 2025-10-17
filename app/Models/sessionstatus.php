<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sessionstatus extends Model
{
    //
    protected $table = 'tb_counselingstatus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'session_status',
    ];
    public $timestamps = false;
}
