<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Interfaces\UserManager;
use App\Repositories\Interfaces\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }

    public function index() {
        $this->authorize("viewAny", auth()->user());;
        
        return view("users.index", [
            "users" => $this->getUserManager()->getAll(request(["search"]))
        ]);
    }
    
    /*
     * It needs a check to avoid to remove all admins from the system
     */
    public function put(User $user) {
        $this->authorize("update", $user);
        
        $this->getUserManager()->modRole($user->id, request()->all());
        
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

    
    private function getUserManager(): UserManager{
        return app()->make(UserManager::class);
    }
}