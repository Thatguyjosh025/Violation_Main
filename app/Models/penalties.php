<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penalties extends Model
{
    //
    use HasFactory;
    protected $table = 'tb_penalties';
    protected $primaryKey = 'penalties_id';
    protected $fillable = ['penalties','penalties_uid','is_visible'];
    public $timestamps = false;
    public function violation()
    {
        return $this->belongsTo(Violation::class, 'violation_id');
    }
    
}
