<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cash;

class Expenditure extends Model
{
	public $cash_id, $attachment;

    public static function boot()
    {
        parent::boot();

        self::saving(function($model){
        	if( $model->is_paid && $model->cash_id != null){
	            $cash = Cash::find($model->cash_id);
	            $old_balance = $cash->balance;
	            $cash->balance -= $model->price*$model->qty;
	            $cash->save();
	            $description = $model->unit_id == null ? "11" : "10/".$model->unit_id;
	            $model->cash_mutation_id = $cash->saveMutation($old_balance, $description, $model->attachment);
        	}
        });        

    }

    protected $guarded = [''];

    public function cash_mutation(){
        return $this->belongsTo('App\Models\CashMutation');
    }

    public function unit(){
        return $this->belongsTo('App\Models\Unit');
    }

    public function getCashAttribute(){
        return $this->cash_mutation->cash;
    }

    public function setType($type){
		$this->is_billing = $type != '1';
		$this->is_paid = $type == '1';
    }

    public function setCash($cash_id, $attachment){
    	$this->cash_id = $cash_id;
        $this->attachment = $attachment;
    }
}
