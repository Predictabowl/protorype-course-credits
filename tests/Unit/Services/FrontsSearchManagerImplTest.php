<?php

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Services\Implementations\FrontsSearchManagerImpl;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\CourseRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

use PHPUnit\Framework\TestCase;

class FrontsSearchManagerImplTest extends TestCase
{
    private $courseRepo;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->courseRepo = $this->createMock(CourseRepository::class);
        app()->instance(CourseRepository::class, $this->courseRepo);

    }
    
    public function test_getCurrentCourse_with_no_fitlered_request(){
        $request = new Request(["search" => "test"]);
        $manager = new FrontsSearchManagerImpl();
        $result = $manager->getCurrentCourse($request);
        
        $this->assertNull($result);
    }
    
    public function test_getCurrentCourse_with_filtered_request(){
        $course = new Course(["id" => 3]);
        $request = new Request(["course" => 3]);
        
        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->willReturn(collect([$course]));
        
        $manager = new FrontsSearchManagerImpl();
        $result = $manager->getCurrentCourse($request);
        
        $this->assertSame($course, $result);
    }
    
    public function test_getFilteredFront(){
        $paginator = $this->createMock(Paginator::class);
        $frontRepo = $this->createMock(FrontRepository::class);
        $array = ["search" => "test", "course" => 2];
        app()->instance(FrontRepository::class, $frontRepo);        
        $request = new Request($array);

        $frontRepo->expects($this->once())
                ->method("getAll")
                ->with($array,10)
                ->willReturn($paginator);
        
        $manager = new FrontsSearchManagerImpl();
        $result = $manager->getFilteredFronts($request, 10);
        
        $this->assertSame($paginator, $result);
    }
    
    public function test_getFilteredFront_with_no_valid_filters(){
        $paginator = $this->createMock(Paginator::class);
        $frontRepo = $this->createMock(FrontRepository::class);
        $array = ["anotherfilter" => 5];
        app()->instance(FrontRepository::class, $frontRepo);        
        $request = new Request();

        $frontRepo->expects($this->once())
                ->method("getAll")
                ->with([],12)
                ->willReturn($paginator);
        
        $manager = new FrontsSearchManagerImpl();
        $result = $manager->getFilteredFronts($request, 12);
        
        $this->assertSame($paginator, $result);
    }
    
}
