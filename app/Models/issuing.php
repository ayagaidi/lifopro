<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class issuing extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function vehicle_nationalities() {
        return $this->belongsTo(VehicleNationality::class);
    }

    public function companies() {
        return $this->belongsTo(Company::class);
    }

 
    
    public function company_users() {
        return $this->belongsTo(CompanyUser::class);
    }
    
    public function users() {
        return $this->belongsTo(User::class);
    
    }
    public function office_users() {
        return $this->belongsTo(OfficeUser::class);
    
    }

    public function offices() {
        return $this->belongsTo(Office::class);
    
    }
    public function cars() {
        return $this->belongsTo(car::class);
    }

    public function countries() {
        return $this->belongsTo(Country::class);
    }

    public function cards() {
        return $this->belongsTo(Card::class);
    }
}