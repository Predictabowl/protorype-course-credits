<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamBlock extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function ssds()
    {
        return $this->belongsToMany(Ssd::class);
    }
}
