<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class priorityrisk extends Model
{
    //
    protected $table = 'tb_priorityrisk';
    protected $primaryKey = 'id';
    protected $fillable = [
        'priority_risk',
    ];
    public $timestamps = false;
}
