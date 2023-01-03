<?php

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Services\Implementations\FrontsSearchManagerImpl;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class FrontsSearchManagerImplTest extends TestCase
{
    private CourseRepository $courseRepo;
    private FrontRepository $frontRepo;
    private FrontsSearchManagerImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->courseRepo = $this->createMock(CourseRepository::class);
        $this->frontRepo = $this->createMock(FrontRepository::class);
        
        $this->sut = new FrontsSearchManagerImpl($this->courseRepo, $this->frontRepo);
    }
    
    public function test_getCurrentCourse_with_no_fitlered_request(){
        $request = new Request(["search" => "test"]);
        $result = $this->sut->getCurrentCourse($request);
        
        $this->assertNull($result);
    }
    
    public function test_getCurrentCourse_with_filtered_request(){
        $course = new Course(["id" => 3]);
        $request = new Request(["course" => 3]);
        
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->willReturn($course);
        
        $result = $this->sut->getCurrentCourse($request);
        
        $this->assertSame($course, $result);
    }
    
    public function test_getFilteredFront(){
        $paginator = $this->createMock(Paginator::class);
        $array = ["search" => "test", "course" => 2];
        $request = new Request($array);

        $this->frontRepo->expects($this->once())
                ->method("getAll")
                ->with($array,10)
                ->willReturn($paginator);
        
        $result = $this->sut->getFilteredFronts($request, 10);
        
        $this->assertSame($paginator, $result);
    }
    
    public function test_getFilteredFront_with_no_valid_filters(){
        $paginator = $this->createMock(Paginator::class);
        $array = ["anotherfilter" => 5];
        $request = new Request();

        $this->frontRepo->expects($this->once())
                ->method("getAll")
                ->with([],12)
                ->willReturn($paginator);
        
        $result = $this->sut->getFilteredFronts($request, 12);
        
        $this->assertSame($paginator, $result);
    }
    
}
