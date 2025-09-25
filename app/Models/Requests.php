<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function companies() {
        return $this->belongsTo(Company::class);
    }


    public function company_users() {
        return $this->belongsTo(CompanyUser::class);
    }

    public function users() {
        return $this->belongsTo(User::class);
    }
    public function request_statuses() {
        return $this->belongsTo(RequestStatus::class);
    }

}
