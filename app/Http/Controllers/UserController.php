<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Interfaces\UserManager;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }

    public function index() {
        $this->authorize("viewAny", auth()->user());
        
        return view("users.index", [
            "users" => $this->getUserManager()->getAll(request(["search"]))
        ]);
    }
    
    public function updateView(User $user) {
        $this->authorize("update", $user);
        
        return view("users.updateView",[
            "user" => $user
        ]);
    }
    
    public function put(User $user) {
        $this->authorize("update", $user);
        
        $attributes = request()->validate([
            "name" => ["required","string","max:255"],
        ]);
        
        $this->getUserManager()->setName($user->id, $attributes["name"]);
        
        return redirect()->route("dashboard");
    }
    
    /*
     * It needs a check to avoid to remove all admins from the system,
     * and it should be the service layer responsability
     * 
     * Thsi method is not tested yet, it's here only for learning
     * purposes and should be properly implemented before opening the 
     * admin dashboard to the public.
     */
    public function putRoles(User $user) {
        $this->authorize("updateRole", $user);
        
        $this->getUserManager()->modRole($user->id, request()->all());
        
        return redirect()->route("userIndex");
    }
    
    public function delete(User $user){
        $this->authorize("delete", $user);
        
        $this->getUserManager()->deleteUser($user->id);
        return back()->with("success", "Eliminato utente: ".$user->name);
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