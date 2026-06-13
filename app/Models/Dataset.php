<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;

    protected $table = 'datasets';
    protected $primaryKey = 'dataset_id';

    protected $fillable = [
        'uploaded_by',
        'dataset_name',
        'dataset_type',
        'source_name',
        'file_name',
        'file_path',
        'description',
        'upload_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'user_id');
    }

    public function climateData()
    {
        return $this->hasMany(ClimateData::class, 'dataset_id', 'dataset_id');
    }

    public function vegetationData()
    {
        return $this->hasMany(VegetationData::class, 'dataset_id', 'dataset_id');
    }

    public function floraData()
    {
        return $this->hasMany(Flora::class, 'dataset_id', 'dataset_id');
    }
}
