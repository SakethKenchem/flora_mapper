<?php

namespace Tests\Unit;

use App\Models\Region;
use Tests\TestCase;

class RegionTest extends TestCase
{
    public function test_region_relationships_instantiation(): void
    {
        $region = new Region();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $region->climateData());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $region->vegetationData());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $region->assessments());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $region->floraData());
    }
}
