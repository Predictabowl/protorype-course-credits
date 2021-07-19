<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Models\TakenExam;
use App\Domain\TakenExamDTO;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Services\Implementations\FrontManagerImpl;
use App\Mappers\Interfaces\TakenExamMapper;
use PHPUnit\Framework\TestCase;

/**
 * Description of FrontManagerImplTest
 *
 * @author piero
 */
class FrontManagerImplTest extends TestCase{

    private const FIXTURE_FRONT_ID = 7;    
    
    private $takenRepo;
    private $manager;
    private $mapper;

    
    protected function setUp():void
    {
        parent::setUp();
        $factory = $this->createMock(RepositoriesFactory::class);
        $this->takenRepo = $this->createMock(TakenExamRepository::class);
        $this->mapper = $this->createMock(TakenExamMapper::class);
        
        $factory->method("getTakenExamRepository")
                ->willReturn($this->takenRepo);      
        
        app()->instance(RepositoriesFactory::class,$factory);
        app()->instance(TakenExamMapper::class, $this->mapper);
        $this->manager = new FrontManagerImpl(self::FIXTURE_FRONT_ID);
    }
  
    
    public function test_getTakenExams() {
        $returned= [new TakenExamDTO(1, "name", "ssd1", 6),
                new TakenExamDTO(2, "name 2", "ssd2", 9)];
        
        $exams = collect([$this->makeTakenExam(1),$this->makeTakenExam(2)]);
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$exams[0]],[$exams[1]])
                ->willReturnOnConsecutiveCalls($returned[0],$returned[1]);

        $this->takenRepo->expects($this->once())
                ->method("getFromFront")
                ->willReturn($exams);
        
        $sut = $this->manager->getTakenExams();
        
        $this->assertEquals($returned, $sut->toArray());
        
    }

    public function test_save_takenExam() {
        $dto = new TakenExamDTO(3, "nome", "ssd2", 9);
        $model = $this->makeTakenExam(13);
        $this->mapper->expects($this->once())
                ->method("toModel")
                ->with($dto)
                ->willReturn($model);
        $this->takenRepo->expects($this->once())
                ->method("save")
                ->with($model);
        
        $this->manager->saveTakenExam($dto);
    }

    public function test_delete_takenExam() {
        $this->takenRepo->expects($this->once())
                ->method("delete")
                ->with(1);
        
        $this->manager->deleteTakenExam(1);
    }  
    
     
    
    private function makeTakenExam($id =1): TakenExam{            
        $mock = new TakenExam();
        $mock->id = $id;
        return $mock;
    }

}
