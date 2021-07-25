<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }

    public function index() {
        $this->authorize("viewAny", auth()->user());
        return view("users.index", [
            "users" => User::with("roles")->filter(request(["search"]))->get()
        ]);
    }
    
    /*
     * It needs a check to avoid to remove all admins from the system
     */
    public function put(User $user) {
        $this->authorize("update", $user);
        // temp code, to move in a proper service layer
        $attributes = request()->all();
        
        // check if it already have the role
        $role = Role::where("name",Role::ADMIN)->first();
        if (isset($attributes["admin"])){
            RoleUser::firstOrCreate([
                "user_id" => $user->id,
                "role_id" => $role->id
            ]);
        } else {
            $user->roles()->detach($role);
        }
        
        $role = Role::where("name",Role::SUPERVISOR)->first();
        if (isset($attributes["supervisor"])){
            RoleUser::firstOrCreate([
                "user_id" => $user->id,
                "role_id" => $role->id
            ]);
        } else {
            $user->roles()->detach($role);
        }        

        return redirect()->route("userIndex");
    }
    
    public function delete(User $user){
        $this->authorize("delete", $user);
        
        return "Trying to delete user: ".$user->name.", with id: ".$user->id."<br>This function has not been implemented yet.";
    }
    
    public function show(User $user){
        $this->authorize("view", $user);
        
        return view("users.show",[
            "user" => $user
        ]);
    }

}