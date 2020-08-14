<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashMutation extends Model
{
    protected $guarded = [''];

    public function cash(){
    	return $this->belongsTo('App\Models\Cash');
    }

    public function user(){
    	return $this->belongsTo('App\User');
    }
}
