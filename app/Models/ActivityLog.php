<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_type',
        'detailed_description',
        'user_name',
        'performed_by',
        'target_user',
        'company_name',
        'office_name',
        'activity_date',
        'status',
        'reason',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
    ];
}
