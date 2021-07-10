<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;


    public function ssd()
    {
        return $this->belongsTo(Ssd::class);
    }

    public function examApprovations()
    {
        return $this->hasMany(ExamBlockOption::class,"exam_block_option_id");
    }

    public function examBlockOptions()
    {
        return $this->belongsToMany(ExamBlockOption::class);
    }

    public function fronts(){
        return $this->belongsToMany(Front::class);
    }
}
