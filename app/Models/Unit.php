<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [''];

    protected $appends = array('name');

    public function apartment(){
    	return $this->belongsTo('App\Models\Apartment');
    }

    public function owner(){
    	return $this->belongsTo('App\Models\Owner');
    } 

    public function getNameAttribute(){
    	return $this->unit_number." - ".$this->apartment->name;
    }
}
