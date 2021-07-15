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

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function ssds()
    {
        return $this->belongsToMany(Ssd::class);
    }
}
