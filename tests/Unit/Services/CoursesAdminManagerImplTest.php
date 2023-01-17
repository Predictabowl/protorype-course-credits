<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Exceptions\Custom\CourseNameAlreadyExistsException;
use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Implementations\CoursesAdminManagerImpl;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use Tests\TestCase;
use function collect;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CoursesAdminManagerImplTest extends TestCase{

    private CoursesAdminManagerImpl $sut;
    private CourseRepository $courseRepo;

    protected function setUp(): void {
        parent::setUp();
        $this->courseRepo = $this->createMock(CourseRepository::class);

        $this->sut = new CoursesAdminManagerImpl($this->courseRepo);
    }

    public function test_getAllCourses_shouldSortByName(){
        $course1 = new Course([
            "id" => 1,
            "name" => "Zorro"
        ]);
        $course2 = new Course([
            "id" => 2,
            "name" => "Abele"
        ]);

        $courses = new Collection([$course1, $course2]);

        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->willReturn($courses);

        $result = $this->sut->getAllCourses();

        $this->assertEquals(collect([$course2, $course1]),$result);
    }
    
    public function test_getAllCourses_withFilters(){
        $course1 = new Course([
            "id" => 1,
            "name" => "Zorro"
        ]);
        $course2 = new Course([
            "id" => 2,
            "name" => "Abele"
        ]);

        $courses = new Collection([$course1, $course2]);

        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->with(["search" => "Zorro"])
                ->willReturn($courses);

        $result = $this->sut->getAllCourses(["search" => "Zorro"]);

        $this->assertEquals(collect([$course2, $course1]),$result);
    }
    
        public function test_getAllCourses_withNullFilters(){
        $course1 = new Course([
            "id" => 1,
            "name" => "Zorro"
        ]);
        
        $courses = new Collection([$course1]);

        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->with([])
                ->willReturn($courses);

        $result = $this->sut->getAllCourses(null);

        $this->assertEquals(collect([$course1]),$result);
    }
    
    public function test_addCourse_withDuplicateName_shouldThrow(){
        $newCourse = new Course(["name" => "test"]);
        $course = new Course();
        
        $this->courseRepo->expects($this->once())
                ->method("getFromName")
                ->with("test")
                ->willReturn($course);
        
        $this->expectException(CourseNameAlreadyExistsException::class);
        $this->sut->addCourse($newCourse);
    }
    
    public function test_addCourse_success(){
        $courseInfo = new Course(["id" => 2, "name" => "test"]);
        $modCourse = new Course(["name" => "test"]);
        $this->courseRepo->expects($this->once())
                ->method("getFromName")
                ->with("test")
                ->willReturn(null);
        
        $this->courseRepo->expects($this->once())
                ->method("save")
                ->with($modCourse)
                ->willReturn(true);
        
        $this->sut->addCourse($courseInfo);
    }
    
    public function test_removeCourse_success(){
        $this->courseRepo->expects($this->once())
                ->method("delete")
                ->with(2)
                ->willReturn(true);
        
        $result = $this->sut->removeCourse(2);
        
        $this->assertTrue($result);
    }
    
    public function test_updateCourse_whenIdMissing_shouldThrow(){
        $this->courseRepo->expects($this->never())
                ->method("get");
        
        $this->expectException(InvalidArgumentException::class);
        $this->sut->updateCourse(new Course());
    }
    
    public function test_updateCourse_withDuplicateName_shouldThrow(){
        $updatedCourse = new Course(["id" => 2, "name" => "test"]);
        $course = new Course(["id" => 5, "name" => "test"]);
        
        $this->courseRepo->expects($this->once())
                ->method("getFromName")
                ->with("test")
                ->willReturn($course);
        $this->courseRepo->expects($this->never())
                ->method("update");
        
        $this->expectException(CourseNameAlreadyExistsException::class);
        $this->sut->updateCourse($updatedCourse);
    }
    
    public function test_updateCourse_shouldIgnoreCurrentEntity_whenCheckingDuplication(){
        $course = new Course(["id" => 2, "name" => "test", "cfu" => 18]);
        $updatedCourse = new Course(["id" => 2, "name" => "test", "cfu" => 180]);
        
        $this->courseRepo->expects($this->once())
                ->method("getFromName")
                ->with("test")
                ->willReturn($course);
        $this->courseRepo->expects($this->once())
                ->method("update")
                ->with($updatedCourse);
        
        $this->sut->updateCourse($updatedCourse);
    }
    
    public function test_updateCourse_success(){
        $courseInfo = new Course([
            "id" => 3,
            "name" => "new name"
        ]);
        $this->courseRepo->expects($this->once())
                ->method("update")
                ->with($courseInfo);
        
        $this->sut->updateCourse($courseInfo);
    }
    
}
