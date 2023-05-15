<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Front extends Model
{
    use HasFactory;
    
    protected $attributes = ["course_id" => null];
    
    protected $fillable = ["id", "user_id", "course_id"];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function takenExams()
    {
        return $this->hasMany(TakenExam::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
