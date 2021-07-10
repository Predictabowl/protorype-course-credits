<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamBlockOption extends Model
{
    use HasFactory;

    public function examBlock()
    {
        return $this->belongsTo(ExamBlock::class);
    }

    public function examApproved()
    {
        return $this->belongsTo(Exam::class,"exam_id");
    }

    public function eligibleExams()
    {
        return $this->belongsToMany(Exam::class,"exam_id");
    }
}
