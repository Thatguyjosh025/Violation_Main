<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class guidanceservice extends Model
{
    //
    protected $table = 'tb_guidanceservice';
    protected $primaryKey = 'id';
    protected $fillable = [
        'guidance_service',
    ];
    public $timestamps = false;
}
