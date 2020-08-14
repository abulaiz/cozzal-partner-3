<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\CashMutation;
use Auth;
use Storage;

class Cash extends Model
{
    protected $guarded = [''];

    public function cash_mutations(){
    	return $this->hasMany('App\Models\CashMutation');
    }

    public function saveMutation($old_value, $description, $attachment, $inc = 0){
        $user_id = Auth::user()->id;
        $uploaded = $attachment->store('tmp');
        $ext = pathinfo($uploaded, PATHINFO_EXTENSION); 
        $filename =  "payments/".$user_id.'-'.(time()+$inc).'.'.$ext;
        Storage::move($uploaded, $filename);

    	$fund = $this->balance - $old_value;
    	// $fund < 0 : new balance is lower than old balance which mean that is outcome (2)
    	// $fund > 0 : new balance is higher than old balance which mean that is income (1)
    	$data = CashMutation::create([
    		'cash_id' => $this->id,
    		'fund' => $fund < 0 ? (-1*$fund) : $fund,
    		'type_mutation' => $fund < 0 ? '2' : '1',
    		'description' => $description,
            'user_id' => $user_id,
            'attachment' => $filename
    	]);

        return $data->id;
    }
}
