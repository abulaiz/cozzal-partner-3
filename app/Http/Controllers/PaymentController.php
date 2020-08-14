<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Expenditure;
use App\Models\Cash;
use App\Libs\SimpleEnc;
use Auth;

class PaymentController extends Controller
{
	private function history_data($owner_id){
		return $owner_id == 0 ? Payment::all() : Payment::where('owner_id', $owner_id)->get();
	}

	private function paid_unpaid_data($owner_id, $paid){
        if ($owner_id == 0) {
			$expenditures = Expenditure::where('has_paid', $paid)->where('unit_id','!=',null)->get();
			$reservations = Reservation::where('is_confirmed', 1)->where('has_paid', $paid)->get();
        } else {
			$reservations = Reservation::whereHas('unit',function($q) use ($owner_id){
				$q->where('owner_id',$owner_id);
			})->where('is_confirmed', 1)
			  ->where('has_paid', $paid)
			  ->get();
			
			$expenditures = Expenditure::where('has_paid', $paid)->where('unit_id','!=',null)
										->whereHas('unit',function($q) use ($owner_id){
											$q->where('owner_id',$owner_id);
										})->get();
        }
        $col = [];
        foreach ($reservations as $reservation) { $col[] = $reservation; }
        foreach ($expenditures as $expenditure) { $col[] = $expenditure; }

        return collect($col)->sortByDesc('created_at');
	}

    private function make_payment($request, $is_paid = false, $cash_mutation_id = null){
        $res = json_decode($request->reservations);
        $exp = json_decode($request->expenditures);
        foreach ($exp as $item) 
            Expenditure::where('id', $item)->update(['has_paid' => true]); 
        foreach ($res as $item) 
            Reservation::where('id', $item)->update(['has_paid' => true]); 

        return Payment::create([
            "cash_mutation_id" => $cash_mutation_id,
            "owner_id" => $request->owner_id,
            "title" => "Payment ".date("Y-m-d"),
            "description" => $request->description,
            "nominal" => $request->total_earning,
            "nominal_paid" => $request->paid_earning,
            "is_accepted" => $is_paid, // if con action pay, its automaticly accepted
            "is_paid" => $is_paid,
            "expenditures" => $request->expenditures,
            "reservations" => $request->reservations        
        ]);
    }

    public function index(Request $request){
    	$type = $request->get('type');
    	$owner_id = $request->get('owner_id');
    	if($type == 'history'){
    		$table = Datatables::of($this->history_data($owner_id));
    		$table->addColumn('receipt_number', function($item){
    			return 'OPM-'.strtoupper( dechex( $item->id ) );
    		});
    		$table->addColumn('_action', function($item){
    			return View('contents.payment.index_table_action', compact('item'))->render();
    		});
    		$table->addColumn('status', function($item){
    			$status = ($item->is_rejected*4) + ($item->is_accepted*2) + ($item->is_paid*1);
    			if($status == 0) return "Waiting - Unpaid";
    			if($status == 2) return "Accepted - Unpaid";
                if($status == 3) return "Accepted - Paid";
    			if($status == 4) return "Rejected";
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

    public function report(){
        $owner_id = Auth::user()->id;
        $data = Payment::where('owner_id', $owner_id)->where('is_rejected',false)
                                                     ->where('is_paid', false)
                                                     ->get();
        $table = Datatables::of($data);
        $table->addColumn('_action', function($item){
            return View('contents.payment.report_table_action', compact('item'))->render();
        });
        $table->addColumn('transaction_count', function($item){
            $res_count = count( json_decode($item->reservations) );
            $exp_count = count( json_decode($item->expenditures) );
            return $res_count+$exp_count." Transaction";
        });        
        $table->addColumn('status', function($item){
            $status = ($item->is_accepted*2) + ($item->is_paid*1);
            if($status == 0) return "-";
            if($status == 2) return "Accepted - Unpaid";
            if($status == 3) return "Accepted - Paid";
        });
        $table->rawColumns(['_action']);   
        return $table->make(true);     
    }

    public function invoice($id){
        $enc = new SimpleEnc();
        $id = $enc->decrypt($id);
        $data = is_numeric($id) ? Payment::find($id) : null;
        $has_arranged = true;
        $expenditures = [];
        $reservations = []; 
        $owners = [];
        if( $data == null ){
            $has_arranged = false;
            $params = explode(',', $id);
            foreach ($params as $param) {
                $d = $param[0] == 'r' ? Reservation::find(substr($param, 1)) : Expenditure::find(substr($param, 1));
                if($d->has_paid) continue;
                $owners[ $d->unit->owner->id ] = $d->unit->owner;
                $d->unit->apartment;
                if($param[0] == 'r') $reservations[] = $d;
                else $expenditures[] = $d;
            }
        } else {
            // Security Purpose           
            if(Auth::user()->hasRole('owner')){
                $status = ($data->is_rejected*4) + ($data->is_accepted*2) + ($data->is_paid*1);
                // if accessed by another owner
                if(Auth::user()->hasRole('owner') && $data->owner_id != Auth::user()->id)
                    return;
                
                // if( ($status > 0 && $status != 2) || $data->owner_id != Auth::user()->id) return;
            }
            $resv = json_decode($data->reservations);
            foreach ($resv as $item) {
                $d = Reservation::find($item);
                $owners[ $d->unit->owner->id ] = $d->unit->owner;
                $d->unit->apartment;
                $reservations[] = $d;              
            }

            $expd = json_decode($data->expenditures);
            foreach ($expd as $item) {
                $d = Expenditure::find($item);
                $owners[ $d->unit->owner->id ] = $d->unit->owner;
                $d->unit->apartment;
                $expenditures[] = $d;              
            }
        }   

        return response()->json([
            'id' => $data == null ? null : $data->id,
            'cash_mutation_id' => $data == null ? null : $data->cash_mutation_id,
            'role' => Auth::user()->getRoleNames()[0],
            'owners' => $owners,
            'reservations' => $reservations,
            'expenditures' => $expenditures,
            'description' => $has_arranged ? $data->description : '-',
            'paid_earning' => $has_arranged ? $data->nominal_paid : null,
            'has_arranged' => $has_arranged,
            'receipt_number' => $has_arranged ? "OPM-".strtoupper(dechex($data->id)) : '-',
            'date' => $has_arranged ? substr($data->created_at, 0, 10) : date('Y-m-d'),
            'is_paid' => $has_arranged ? $data->is_paid : false,
            'is_accepted' => $has_arranged ? $data->is_accepted : false,
            'is_rejected' => $has_arranged ? $data->is_rejected : false
        ]);
    }


    /*  Request 
        reservations, expenditures // as encoded array
        total_earning, paid_earning, description, owner_id
    */ 
    public function send(Request $request){
        $this->make_payment($request);
        return response()->json(['success' => true]);
    }

    /*  Request 
        reservations, expenditures // as encoded array
        total_earning, paid_earning, description, owner_id, cash_id
    */ 
    public function pay(Request $request){
        $cash = Cash::find($request->cash_id);
        $initial_balance = $cash->balance;
        $cash->balance -= (int)$request->paid_earning;
        if($cash->balance < 0)
            return response()->json(['success' => false]);
        $cash->save();
        $cash_mutation_id = $cash->saveMutation($initial_balance, "4", $request->file('attachment'));
        if($request->id == null || $request->id == 'null'){
            $this->make_payment($request, true, $cash_mutation_id);
        } else {
            Payment::find($request->id)->update([
                "cash_mutation_id" => $cash_mutation_id,
                "is_accepted" => true, // if con action pay, its automaticly accepted
                "is_paid" => true
            ]);
        }
               
        return response()->json(['success' => true]);
    }

    public function confirm(Request $request){
        $enc = new SimpleEnc();
        $id = $enc->decrypt($request->id);
        $data = Payment::find($id);
        $data->is_accepted = true;
        $data->save();
        return response()->json(['success' => true]);
    }

    public function reject(Request $request){
        $enc = new SimpleEnc();
        $id = $enc->decrypt($request->id);
        $data = Payment::find($id);
        $data->is_rejected = true;
        $data->save();
        return response()->json(['success' => true]);
    }

    public function destroy(Request $request){
        $data = Payment::find($request->id);
        $res = json_decode($data->reservations);
        $exp = json_decode($data->expenditures);
        foreach ($exp as $item) 
            Expenditure::where('id', $item)->update(['has_paid' => false]); 
        foreach ($res as $item) 
            Reservation::where('id', $item)->update(['has_paid' => false]); 
        $data->delete();
        return response()->json(['success' => true]);        
    }

    public function owner_paid(){
        $owner_id = Auth::user()->id;
        $data = Payment::where('owner_id', $owner_id)->where('is_paid',true)
                                                     ->get();
        $table = Datatables::of($data);
        $table->addColumn('_action', function($item){
            return View('contents.payment.report_table_action', compact('item'))->render();
        });
        $table->addColumn('transaction_count', function($item){
            $res_count = count( json_decode($item->reservations) );
            $exp_count = count( json_decode($item->expenditures) );
            return $res_count+$exp_count." Transaction";
        });
        $table->rawColumns(['_action']);   
        return $table->make(true);          
    }
}
