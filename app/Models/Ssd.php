<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ssd extends Model
{
    use HasFactory;
    
    //protected $fillable = ["code"];
    protected $guarded = [];

    
    //---------- Relationships 
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function takenExams()
    {
        return $this->hasMany(TakenExam::class);
    }
    
    public function examBlocks(){
        return $this->belongsToMany(ExamBlock::class);
    }
    
    //---------- Mutators
    public function setCode($value){
        $this->attributes["code"] = strtoupper("value");
    }
}
