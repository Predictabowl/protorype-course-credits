<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\FrontRepository;
use App\Models\Front;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 *
 * @author piero
 */
class FrontRepositoryImpl implements FrontRepository{

    public function delete($id): int {
        return Front::destroy($id);
    }

    public function save(Front $front): ?Front {
        if (isset($front->id)){
            throw new \InvalidArgumentException("The id of a new Front must be null");
        }
        
        $found = Front::where("user_id", $front->user_id)->first();
        
        if (isset($found)){
            return null;
        }
        
        return $front->save() ? $front : null;
    }

    public function updateCourse($id, $courseId): ?Front {
        $front = Front::find($id);
        if (!isset($front)){
            return null;
        }
        $course = Course::find($courseId);
        if (!isset($course)){
            throw new ModelNotFoundException("Could not find a Course with id: ".$courseId);
        }
        $front->course()->associate($course);
        return $front;
    }

    public function get($id): ?Front {
        return Front::find($id);
    }

    public function getFromUser($id): ?Front {
        $user = User::with("front")->find($id);
        if (!isset($user)){
            throw new ModelNotFoundException("Could not find User with id: ".$id);
        }
        return $user->front;
    }

}
