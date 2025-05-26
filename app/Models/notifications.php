<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    //

    protected $table = 'tb_notifications';
    protected $primaryKey = 'id';
    protected $fillable = 
    ['title',
    'message',
    'student_no',
    'role',
    'type',
    'is_read',
    'url',
    'date_created',
    'created_time'];
    
    public $timestamps = false;
}
