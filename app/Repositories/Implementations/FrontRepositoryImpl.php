<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use App\Exceptions\Custom\CourseNotFoundException;
use App\Exceptions\Custom\UserNotFoundException;
use App\Models\Course;
use App\Models\Front;
use App\Models\User;
use App\Repositories\Interfaces\FrontRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 *
 * @author piero
 */
class FrontRepositoryImpl implements FrontRepository{

    public function delete($id): bool {
        return Front::destroy($id);
    }

    public function save(Front $front): ?Front{
        if (isset($front->id)){
            throw new InvalidArgumentException("The id of a new Front must be null");
        }
        
        $user = User::with("front")->find($front->user_id);
        if (!isset($user)){
            throw new UserNotFoundException("User not found with id: ".$front->user_id);
        }
        
        if(isset($user->front)){
            return null;
        }
        
        $course = Course::find($front->course_id);
        if (!isset($course)){
            throw new CourseNotFoundException(
                    "Could not find Course with id: ".$front->course_id);
        }
        
        
        $front->save();
        return $front;
    }

    public function updateCourse($id, $courseId): ?Front {
        $front = Front::find($id);
        if (!isset($front)){
            return null;
        }
        $front->course_id = $courseId;
        try{
            $front->save();
        } catch (QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return null;
        }
        return $front;
    }

    public function get($id): ?Front {
        return Front::with("course")->find($id);
    }

    public function getFromUser($id): ?Front {
        $user = User::with("front")->find($id);
        if (!isset($user)){
            throw new ModelNotFoundException("Could not find User with id: ".$id);
        }
        return $user->front;
    }
        
    public function getAll(array $filters, int $numInPage = 50): Paginator{
        $query = Front::with("user","course");

        if (isset($filters["search"])){
            $query->whereHas("user",fn($query) => 
                    $query->where("users.name","like","%".$filters["search"]."%")
                     ->orWhere("users.email","like","%".$filters["search"]."%")
                   );
        }
        
        if (isset($filters["course"])){
            $query->whereHas("course",fn($query) => 
                    $query->where("courses.id","=",$filters["course"]));
        }
        
        return $query->paginate($numInPage)->withQueryString();
    }

}
