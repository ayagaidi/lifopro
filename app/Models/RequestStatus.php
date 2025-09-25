<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function requests() {
        return $this->hasMany(Requests::class);
    }


    public function companies() {
        return $this->belongsTo(Company::class);
    }
   
}
