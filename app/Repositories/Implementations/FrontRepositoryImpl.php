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
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

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
            throw new \InvalidArgumentException("The id of a new Front must be null");
        }
        
        try {
            return $front->save() ? $front : null;
        } catch(QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return null;
        }
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

    public function getAll() {
        return Front::all();
    }

}
