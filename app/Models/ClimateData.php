<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClimateData extends Model
{
    use HasFactory;

    protected $table = 'climate_data';
    protected $primaryKey = 'climate_data_id';

    protected $fillable = [
        'dataset_id',
        'region_id',
        'record_date',
        'temperature_celsius',
        'rainfall_mm',
        'humidity_percent',
        'drought_index',
        'flood_risk_level',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'dataset_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }
}
