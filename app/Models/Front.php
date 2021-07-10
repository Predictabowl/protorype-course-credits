<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Front extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function takenExams()
    {
        return $this->hasMany(TakenExam::class);
    }

    public function recognizedExams()
    {
        return $this->hasMany(Exam::class,"exam_id");
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
