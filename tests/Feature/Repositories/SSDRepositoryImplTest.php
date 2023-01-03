<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Feature\Repositories;

use App\Models\Ssd;
use App\Repositories\Implementations\SSDRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Description of SSDRepositoryImplTest
 *
 * @author piero
 */
class SSDRepositoryImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private SSDRepositoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->sut = new SSDRepositoryImpl();
    }

    
    public function test_getSSD_whenMissing(){
        $notFound = $this->sut->getSsdFromCode("SSD/01");
        
        $this->assertNull($notFound);
    }
    
    public function test_getSSD_success() {
        Ssd::factory()->create();
        $ssd = Ssd::all()->first();
        
        $result = $this->sut->getSsdFromCode($ssd->code);
        
        $this->assertEquals($ssd, $result);
    }
}
