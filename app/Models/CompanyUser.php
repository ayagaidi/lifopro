<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CompanyUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    protected $fillable = [
        'fullname',
        'username', 
        'email',
        'password',
        'active',
        'companies_id',
        'user_type_id',
        'remember_token',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at'
    ];

    public function userType() {
        return $this->belongsTo(UserType::class);
    }

    public function companies() {
        return $this->belongsTo(Company::class);
    }
    
}
