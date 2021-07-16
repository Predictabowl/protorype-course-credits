<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamBlock extends Model
{
    use HasFactory;

    public function examBlockOptions()
    {
        return $this->hasMany(ExamBlockOption::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
