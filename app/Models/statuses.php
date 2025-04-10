<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class statuses extends Model
{
    //
    protected $table = 'tb_status';

    protected $primaryKey = 'status_id';
    protected $fillable = ['status'];
    public $timestamps = false;
}
