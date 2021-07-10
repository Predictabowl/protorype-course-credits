<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamFront extends Model
{
    use HasFactory;

    protected $table = "exam_front";
    protected $fillable = ["exam_id","front_id"];
}
