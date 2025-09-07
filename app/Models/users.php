<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class users extends Authenticatable
{
    //

    protected $table = 'tb_users';
    protected $primaryKey = 'id';
    protected $fillable = ['firstname', 'lastname', 'email', 'password','role','student_no','course_and_section','status'];
    public $timestamps = false;

    use \Illuminate\Auth\Authenticatable;
}
