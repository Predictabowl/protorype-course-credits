<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ["id", "name","cfu"];
    
    public function examBlocks()
    {
        return $this->hasMany(ExamBlock::class);
    }

    public function fronts(){
        return $this->hasMany(Front::class);
    }
}
