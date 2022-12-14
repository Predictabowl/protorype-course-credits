<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ["name","cfu","ssd_id"];

    public function ssd()
    {
        return $this->belongsTo(Ssd::class);
    }

    public function examBlockOptions()
    {
        return $this->hasMany(ExamBlockOption::class);
    }
}
