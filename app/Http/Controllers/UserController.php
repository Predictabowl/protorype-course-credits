<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Interfaces\UserManager;

class UserController extends Controller
{
    private UserManager $userManager;
    
    public function __construct(UserManager $userManager) {
        $this->middleware(["auth","verified"]);
        $this->userManager = $userManager;
    }

    public function index() {
        $this->authorize("viewAny", auth()->user());
        
        return view("users.index", [
            "users" => $this->userManager->getAll(request(["search"]))
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
        
        $this->userManager->setName($user->id, $attributes["name"]);
        
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
        
        $this->userManager->modRole($user->id, request()->all());
        
        return redirect()->route("userIndex");
    }
    
    public function delete(User $user){
        $this->authorize("delete", $user);
        
        $this->userManager->deleteUser($user->id);
        return back()->with("success", "Eliminato utente: ".$user->name);
    }
    
    public function show(User $user){
        $this->authorize("view", $user);
        
        return view("users.show",[
            "user" => $user
        ]);
    }
}