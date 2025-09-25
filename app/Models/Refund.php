<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function offices() {
        return $this->belongsTo(Office::class);
    } 

    public function companies() {
        return $this->belongsTo(Company::class);
    }
}
