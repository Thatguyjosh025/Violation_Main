<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    //
    protected $table = 'tb_sections';
    protected $primaryKey = 'id';
    protected $fillable = ['header', 'description'];
}
