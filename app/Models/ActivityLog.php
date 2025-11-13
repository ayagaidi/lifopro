<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_type',
        'user_name',
        'activity_date',
        'status',
        'reason',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
    ];
}
