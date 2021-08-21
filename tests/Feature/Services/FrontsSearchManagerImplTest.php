<?php

namespace Tests\Feature\Services;

use App\Models\Course;
use App\Services\Implementations\FrontsSearchManagerImpl;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\CourseRepository;
use Illuminate\Contracts\Pagination\Paginator;

use Tests\TestCase;

class FrontsSearchManagerImplTest extends TestCase
{
    private $courseRepo;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->courseRepo = $this->createMock(CourseRepository::class);
        app()->instance(CourseRepository::class, $this->courseRepo);

    }
    
    public function test_getCurrentCourse_with_no_request(){
        $manager = new FrontsSearchManagerImpl();
        $result = $manager->getCurrentCourse();
        
        $this->assertNull($result);
    }
    
    public function test_getCurrentCourse_with_request(){
        $course = new Course(["id" => 3]);
        request()->replace(["course" => 3]);
        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->willReturn(collect([$course]));
        
        $manager = new FrontsSearchManagerImpl();
        $result = $manager->getCurrentCourse();
        
        $this->assertSame($course, $result);
    }
    
    public function test_getFilteredFront(){
        $paginator = $this->createMock(Paginator::class);
        $frontRepo = $this->createMock(FrontRepository::class);
        $array = ["search" => "test", "course" => 2];
        app()->instance(FrontRepository::class, $frontRepo);        
        request()->merge($array);

        $frontRepo->expects($this->once())
                ->method("getAll")
                ->with($array,10)
                ->willReturn($paginator);
        
        $manager = new FrontsSearchManagerImpl();
        $result = $manager->getFilteredFronts(10);
        
        $this->assertSame($paginator, $result);
    }
    
}
