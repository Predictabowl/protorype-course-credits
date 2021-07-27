<?php

namespace App\Services\Interfaces;

use App\Models\Front;
use Illuminate\Support\Collection;

/**
 * Test interface, not implemented 
 * 
 * @author piero
 */
interface FrontsManager {
    
    public function getAllFronts($filter): Collection;
    
    public function getActiveFront(): Front;
    
}
