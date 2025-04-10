<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class referals extends Model
{
    //
    
    protected $table = 'tb_referals';
    protected $primaryKey = 'referal_id';
    protected $fillable = ['referals'];

    public $timestamps = false;

}
