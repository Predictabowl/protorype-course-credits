<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Implementations\CourseAdminManagerImpl;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;
use function app;
use function collect;

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

        $this->sut = new CourseAdminManagerImpl($this->courseRepo);
    }

    public function test_getAll_shouldSortByName(){
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

        $result = $this->sut->getAll();

        // var_dump($result);

        $this->assertEquals(collect([$course2, $course1]),$result);
    }

}
