<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apiuser extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function companies() {
        return $this->belongsTo(Company::class);
    }
}
