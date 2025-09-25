<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function company_users() {
        return $this->belongsTo(CompanyUser::class);
    }

  

    public function offices() {
        return $this->belongsTo(Office::class);
    
    }
}
