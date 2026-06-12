<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VegetationData extends Model
{
    use HasFactory;

    protected $table = 'vegetation_data';
    protected $primaryKey = 'vegetation_data_id';

    protected $fillable = [
        'dataset_id',
        'region_id',
        'record_date',
        'ndvi_value',
        'vegetation_cover_percent',
        'vegetation_condition',
        'data_source',
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
