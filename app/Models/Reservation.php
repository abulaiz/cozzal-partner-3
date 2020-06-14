<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = [''];

    public function tenant(){
    	return $this->belongsTo('App\Models\Tenant');
    }

    public function unit(){
    	return $this->belongsTo('App\Models\Unit');
    }    

    public function booking_via()
    {
        return $this->belongsTo('App\Models\Booking_via');
    }

    public function payment()
    {
        return $this->hasMany('App\Models\ReservationPayment');
    }
}
