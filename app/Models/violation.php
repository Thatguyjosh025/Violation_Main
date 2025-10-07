<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class violation extends Model
{
    //
    use HasFactory;
    protected $table = 'tb_violation';
    protected $primaryKey = 'violation_id';

    protected $fillable = ['violations','violation_uid','is_visible'];
    public $timestamps = false;

    
}
