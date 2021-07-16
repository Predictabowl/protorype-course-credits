<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ssd extends Model
{
    use HasFactory;

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function takenExams()
    {
        return $this->hasMany(TakenExam::class);
    }
    
    public function ExamBlockOptions(){
        return $this->belongsToMany(ExamBlockOption::class);
    }
}
