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

    protected $fillable = ['violations'];
    public $timestamps = false;

//     public function incidents(){
//     return $this->hasMany(postviolation::class, 'violation_id');
// }
}
