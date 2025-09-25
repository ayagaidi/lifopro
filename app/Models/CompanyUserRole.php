<?php

namespace App\Models;

use Database\Seeders\Company_user_permissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUserRole extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function company_users() {
        return $this->belongsTo(CompanyUser::class);
    }
    public function company_user_permissions() {
        return $this->belongsTo(CompanyUserPermissions::class);
    }
}
