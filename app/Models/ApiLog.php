<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'operation_type',
        'execution_date',
        'status',
        'sent_data',
        'received_data',
    ];

    protected $casts = [
        'execution_date' => 'datetime',
        'sent_data' => 'array',
        'received_data' => 'array',
    ];
}
