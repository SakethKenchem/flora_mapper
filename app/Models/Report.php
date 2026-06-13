<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'generated_by',
        'report_title',
        'report_type',
        'content',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'generated_by', 'user_id');
    }
}
