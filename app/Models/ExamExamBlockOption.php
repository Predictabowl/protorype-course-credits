<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamExamBlockOption extends Model
{
    use HasFactory;

    protected $table = "exam_exam_block_option";
    protected $fillable = ["exam_id","exam_block_option_id"];
}
