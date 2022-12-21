<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Implementations\CourseAdminManagerImpl;
use PHPUnit\Framework\TestCase;
use function app;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseAdminManagerImplTest extends TestCase{

    private CourseAdminManagerImpl $sut;
    private $courseRepo;
    
    protected function setUp(): void {
        parent::setUp();
        $this->courseRepo = $this->createMock(CourseRepository::class);
        app()->instance(CourseRepository::class, $this->courseRepo);
        
        $this->sut = new CourseAdminManagerImpl();
    }

    public function test_getAll(){
        $courses = [
            new Course(["id" => 1]),
            new Course(["id" => 2])
        ];
        
        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->willReturn($courses);
        
        $result = $this->sut->getAll();
        
        $this->assertEquals(collect($courses),$result);
    }
    
}
