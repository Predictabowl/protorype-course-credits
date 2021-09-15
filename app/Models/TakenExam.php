<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakenExam extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function front()
    {
        return $this->belongsTo(Front::class);
    }

    public function ssd()
    {
        return $this->belongsTo(Ssd::class);
    }
}
