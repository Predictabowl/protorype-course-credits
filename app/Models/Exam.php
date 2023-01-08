<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ["name","cfu","ssd_id","free_choice"];

    public function ssd()
    {
        return $this->belongsTo(Ssd::class);
    }

    public function examBlock()
    {
        return $this->belongsTo(ExamBlock::class);
    }
}
