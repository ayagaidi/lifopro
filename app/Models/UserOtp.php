<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'user_type', // 'user' or 'company_user'
        'type', // 'login', 'company_login', 'reset_password', etc.
        'otp_code',
        'expires_at',
        'is_used',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime:Y-m-d H:i:s',
        'is_used' => 'boolean',
    ];

    public function user()
    {
        if ($this->user_type === 'company_user') {
            return $this->belongsTo(CompanyUser::class, 'user_id');
        }
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isValid()
    {
        return !$this->is_used && !$this->isExpired();
    }
}