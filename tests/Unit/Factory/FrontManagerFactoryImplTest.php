<?php

namespace Tests\Unit\Services;

use App\Factories\Implementations\FrontManagerFactoryImpl;
use App\Services\Implementations\FrontManagerImpl;
use Tests\TestCase;

class FrontManagerFactoryImplTest extends TestCase
{
    private FrontManagerFactoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->sut = new FrontManagerFactoryImpl();
    }

    
    public function test_factory_instance(){
        
        $instance = $this->sut->getFrontManager(3);
        
        $this->assertInstanceOf(FrontManagerImpl::class, $instance);
    }
}