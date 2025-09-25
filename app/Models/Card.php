<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function companies() {
        return $this->belongsTo(Company::class);
    }

  

    public function offices() {
        return $this->belongsTo(Office::class);
    
    }
    public function requests() {
        return $this->belongsTo(Requests::class);
    }

    public function cardstautes() {
        return $this->belongsTo(Cardstautes::class);
    }

public function issuing()
{
    return $this->hasOne(issuing::class, 'cards_id');
}
 public function issuings()
{
    return $this->hasOne(issuing::class, 'cards_id');
}

public function request_statuses()
{
    return $this->belongsTo(RequestStatus::class, 'request_statuses_id');
}



public function company_users()
{
    return $this->belongsTo(User::class, 'company_users_id');
}

}
