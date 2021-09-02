<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Models\Role;
use \App\Models\User;
use PHPUnit\Framework\TestCase;
use App\Services\Implementations\UserManagerImpl;
use App\Repositories\Interfaces\UserRepository;

/**
 * Description of UserManagerImplTest
 *
 * @author piero
 */
class UserManagerImplTest extends TestCase{
    
    private $manager;
    private $userRepo;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->userRepo = $this->createMock(UserRepository::class);
        
        app()->instance(UserRepository::class, $this->userRepo);
        $this->manager = new UserManagerImpl();
    }

    
    public function test_add_admin_role() {
        $attributes = [Role::ADMIN => "on"];
        
        $this->userRepo->expects($this->once())
                ->method("addRole")
                ->with(1, Role::ADMIN)
                ->willReturn(true);
        
        $this->userRepo->expects($this->once())
                ->method("removeRole")
                ->with(1, Role::SUPERVISOR)
                ->willReturn(true);
        
        $this->manager->modRole(1, $attributes);
    }
    
    public function test_add_supervisor_role() {
        $attributes = [Role::SUPERVISOR => "on"];
        
        $this->userRepo->expects($this->once())
                ->method("removeRole")
                ->with(1, Role::ADMIN)
                ->willReturn(true);
        
        $this->userRepo->expects($this->once())
                ->method("addRole")
                ->with(1, Role::SUPERVISOR)
                ->willReturn(true);
        
        $this->manager->modRole(1, $attributes);
    }
    
    public function test_remove_both_roles() {
        $attributes = [];
        
        $this->userRepo->expects($this->exactly(2))
                ->method("removeRole")
                ->withConsecutive([1, Role::ADMIN], [1, Role::SUPERVISOR])
                ->willReturn(true);
        
        $this->manager->modRole(1, $attributes);
    }
    
    public function test_add_both_roles() {
        $attributes = [
            Role::ADMIN => "on",
            Role::SUPERVISOR => "on"
        ];
        
        $this->userRepo->expects($this->exactly(2))
                ->method("addRole")
                ->withConsecutive([1, Role::ADMIN], [1, Role::SUPERVISOR])
                ->willReturn(true);
        
        $this->manager->modRole(1, $attributes);
    }
    
    public function test_getAll(){
        $collection = $this->createMock(\Illuminate\Contracts\Pagination\Paginator::class);
        $this->userRepo->expects($this->once())
                ->method("getAll")
                ->with([])
                ->willReturn($collection);
        
        $result = $this->manager->getAll([]);
        
        $this->assertSame($collection, $result);
    }
    
    public function test_setName(){
        $userId = 17;
        $oldName = "Maria";
        $newName = "Mario";
        $user = new User([
            "id" => $userId,
            "name" => $oldName
        ]);
        $changedUser = new User([
            "id" => $userId,
            "name" => $newName
        ]);
        $this->userRepo->expects($this->once())
                ->method("get")
                ->with($userId)
                ->willReturn($user);
        $this->userRepo->expects($this->once())
                ->method("update")
                ->with($changedUser);
        
        $this->manager->setName($userId, $newName);
    }
    
    public function test_deleteUser_failure(){
        $userId = 17;        
        $this->userRepo->expects($this->once())
                ->method("delete")
                ->with($userId)
                ->willReturn(false);
        
        $result = $this->manager->deleteUser($userId);
        
        $this->assertFalse($result);
    }
    
    public function test_deleteUser_success(){
        $userId = 17;        
        $this->userRepo->expects($this->once())
                ->method("delete")
                ->with($userId)
                ->willReturn(true);
        
        $result = $this->manager->deleteUser($userId);
        
        $this->assertTrue($result);
    }
}
