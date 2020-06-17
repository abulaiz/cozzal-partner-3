<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Models\PaymentGroup;
use App\Models\Reservation;
use App\Models\Expenditure;

class PaymentController extends Controller
{
	private function history_data($owner_id){
		return $owner_id == 0 ? PaymentGroup::all() : PaymentGroup::where('owner_id', $owner_id)->get();
	}

	private function paid_unpaid_data($owner_id, $paid){
        if ($owner_id == 0) {
			$expenditures = Expenditure::where('is_paid', $paid)->where('unit_id','!=',null)->get();
			$reservations = Reservation::where('is_confirmed', 1)->where('is_paid', $paid)->get();
        } else {
			$reservations = Reservation::whereHas('unit',function($q) use ($owner_id){
				$q->where('owner_id',$owner_id);
			})->where('is_confirmed', 1)
			  ->where('is_paid', $paid)
			  ->get();
			
			$expenditures = Expenditure::where('is_paid', $paid)->where('unit_id','!=',null)
										->whereHas('unit',function($q) use ($owner_id){
											$q->where('owner_id',$owner_id);
										})->get();
        }
        return $reservations->merge($expenditures)->sortByDesc('created_at');
	}

    public function index(Request $request){
    	$type = $request->get('type');
    	$owner_id = $request->get('owner_id');
    	if($type == 'history'){
    		$table = Datatables::of($this->history_data($owner_id));
    		$table->addColumn('receipt_number', function($item){
    			return 'OPM-'.strtoupper( dechex( $item->id ) );
    		});
    		$table->addColumn('_action', function(){
    			return View('contents.payment.index_table_action')->render();
    		});
    		$table->addColumn('status', function(){
    			$status = ($group_payment->is_accepted*2) + ($group_payment->is_paid*1);
    			if($status == 0) return "Waiting - Unpaid";
    			if($status == 2) return "Accepted - Unpaid";
    			if($status == 3) return "Accepted - Paid";
    		});
    		$table->rawColumns(['_action']);
    	} else {
    		$table = Datatables::of($this->paid_unpaid_data($owner_id, $type == 'paid'));
    		$table->addColumn('type', function($item){
    			return $item->check_in == null ? 'Expenditure' : 'Reservation';
    		});
    		$table->addColumn('receipt_number', function($item){
    			$prefix = $item->check_in == null ? 'EXP-' : 'COZ-';
    			return $prefix.strtoupper( dechex( $item->id ) );
    		});
    		$table->addColumn('unit', function($item){
    			return $item->unit->unit_number."-".$item->unit->apartment->name;
    		});
    		$table->addColumn('owner', function($item){
    			return $item->unit->owner->name;
    		});    		
    		$table->addColumn('date', function($item){
    			return $item->check_in == null ? '-' : substr($item->check_in, 0, 10)." - ".substr($item->check_out, 0, 10);
    		});    

    		if($type == 'unpaid'){
	    		$table->addColumn('checkbox', function($item){
	    			return View('contents.payment.index_table_checkbox', compact('item'))->render();
	    		}); 		
	    		$table->rawColumns(['checkbox']);  
    		}  		
    	}
    	
    	return $table->make(true);
    }
}
