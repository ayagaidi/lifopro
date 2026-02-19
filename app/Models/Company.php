<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['pob'];

    public function cities() {
        return $this->belongsTo(City::class);
    }

    public function regions() {
        return $this->belongsTo(Region::class);
    }
}
