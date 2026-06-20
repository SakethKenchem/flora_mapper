<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationReport extends Model
{
    use HasFactory;

    protected $table = 'observation_reports';
    protected $primaryKey = 'observation_id';

    protected $fillable = [
        'public_id',
        'researcher_id',
        'flora_id',
        'flora_name',
        'location',
        'description',
        'image_path',
        'csv_path',
        'temperature_celsius',
        'rainfall_mm',
        'humidity_percent',
        'drought_index',
        'ndvi_value',
        'vegetation_cover_percent',
        'vegetation_condition',
        'date_observed',
        'submission_date',
        'status',
        'review_comment',
    ];

    protected $casts = [
        'date_observed' => 'date',
        'submission_date' => 'datetime',
    ];

    public function observer()
    {
        return $this->belongsTo(User::class, 'public_id', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'researcher_id', 'user_id');
    }

    public function flora()
    {
        return $this->belongsTo(Flora::class, 'flora_id', 'flora_id');
    }
}
