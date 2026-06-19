<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flora extends Model
{
    use HasFactory;

    protected $table = 'flora';
    protected $primaryKey = 'flora_id';

    protected $fillable = [
        'dataset_id',
        'region_id',
        'scientific_name',
        'common_name',
        'species_type',
        'conservation_status',
        'habitat_type',
        'vulnerability_level',
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