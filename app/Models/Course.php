<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function examBlocks()
    {
        return $this->hasMany(ExamBlock::class)->withTimestamps();
    }

    public function fronts(){
        return $this->hasMany(Front::class);
    }
}
