<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeUserRole extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function office_users() {
        return $this->belongsTo(OfficeUser::class);
    }
    public function office_user_permissions() {
        return $this->belongsTo(OfficeUserPermissions::class);
    }
}
