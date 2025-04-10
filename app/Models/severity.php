<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class severity extends Model
{
    //
    protected $table = 'tb_severity';
    protected $primaryKey = 'severity_id';
    protected $fillable = ['severity'];
    public $timestamps = false;
}
