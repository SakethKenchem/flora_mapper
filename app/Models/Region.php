<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';
    protected $primaryKey = 'region_id';

    protected $fillable = [
        'region_name',
        'county',
        'ecosystem_type',
        'latitude',
        'longitude',
        'description',
    ];

    public function climateData()
    {
        return $this->hasMany(ClimateData::class, 'region_id', 'region_id');
    }

    public function vegetationData()
    {
        return $this->hasMany(VegetationData::class, 'region_id', 'region_id');
    }

    public function assessments()
    {
        return $this->hasMany(VulnerabilityAssessment::class, 'region_id', 'region_id');
    }
}
