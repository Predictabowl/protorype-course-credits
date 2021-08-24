<?php

namespace App\Services\Interfaces;

use App\Models\Course;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

/**
 * Test interface, not implemented 
 * 
 * @author piero
 */
interface FrontsSearchManager {
    
    public function getFilteredFronts(Request $request, int $pageSize): Paginator;
    
    public function getCourses(): Collection;
    
    public function getCurrentCourse(Request $request): ?Course;
    
}
