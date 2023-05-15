<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function examBlocks()
    {
        return $this->hasMany(ExamBlock::class);
    }

    public function fronts(){
        return $this->hasMany(Front::class);
    }

    //------------- Scopes
    public function scopeFilter($query, array $filters) {
        if (isset($filters["search"])){
            $query->where(fn ($query) => $query
                ->where("name", "like", "%".$filters["search"]."%"));
        }
        
        if (isset($filters["active"])){
            $query->where(fn ($query) => $query
                ->where("active", "=", $filters["active"]));
        }
    }
}
