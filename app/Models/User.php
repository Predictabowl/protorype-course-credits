<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    
    use HasFactory, Notifiable;


//    protected $attributes = [
//        "role" => "user"
//    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function front()
    {
        return $this->hasOne(Front::class);
    }
    
    public function roles(){
        return $this->belongsToMany(Role::class);
    }
    
    
    //---------- Defined Roles
    // 
    
    // And admin can modify everything and change other users roles
    public function isAdmin(): bool{
        return $this->roles()->get()->contains("name",Role::ADMIN);
    }
    
    /*
     *  A supervisor can:
     *  - See all registered users, read-only
     *  - Access all Fronts, read-only
     *  - Edit Courses, r-w
     */
    public function isSupervisor(): bool{
        return $this->roles()->get()->contains("name",Role::SUPERVISOR);
    }
    
    public function isStudent(): bool{
        return $this->roles()->get()->isEmpty();
    }
    
    
    /* $roles can be empty, while $roleNames is never empty.
     * So it makes sense to iterate over $roles to avoid useless cycles.
     */
    public function hasAtLeastOneRole(...$roleNames): bool{
        $roles = $this->roles()->get();
        $names = collect($roleNames);
        foreach ($roles as $role) {
            if ($names->contains($role->name)){
                return true;
            }
        }
        return false;
    }
}
