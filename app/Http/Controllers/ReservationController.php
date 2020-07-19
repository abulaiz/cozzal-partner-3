<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use App\Models\Cash;
use App\Models\Payment;
use Auth;

class ReservationController extends Controller
{
    public function confirmed(){
		$data = Reservation::where('deleted_at',null)
							->where('is_confirmed',1)
							->get(['id', 'check_in', 'check_out', 'tenant_id', 
									'unit_id', 'unit_id', 'updated_at']);
        return Datatables::of($data)
                        ->addColumn('tenant', function($row){ 
                            return $row->tenant->name; 
                        })
                        ->addColumn('unit', function($row){ 
                            return $row->unit->unit_number." - ".$row->unit->apartment->name;
                        })
                        ->addColumn('receipt_id', function($row){ 
                            return "COZ-".strtoupper(dechex($row->id));
                        })
                        ->addColumn('_action', function($row){
                            return view('contents.reservation.confirmed_table_action', compact('row'));
                        })
                        ->rawColumns(['_action'])
                        ->make(true);		
    }

    public function canceled(){
        $data = Reservation::where('deleted_at','!=',null)
                            ->get(['id', 'check_in', 'check_out', 'tenant_id', 
                                    'unit_id', 'unit_id', 'deleted_at']);
        return Datatables::of($data)
                        ->addColumn('tenant', function($row){ 
                            return $row->tenant->name; 
                        })
                        ->addColumn('unit', function($row){ 
                            return $row->unit->unit_number." - ".$row->unit->apartment->name;
                        })
                        ->addColumn('dp', function($row){ 
                            return (int)$row->payment()
                                            ->sum('nominal');
                        })     
                        ->addColumn('settlement', function($row){
                            return (int)$row->payment()
                                            ->sum('settlement');                            
                        })                   
                        ->addColumn('receipt_id', function($row){ 
                            return "COZ-".strtoupper(dechex($row->id));
                        })
                        ->addColumn('_action', function($row){
                            return View('contents.reservation.canceled_table_action')->render();
                        })
                        ->rawColumns(['_action'])
                        ->make(true);
    }

    public function settlement(Request $request){
        $id = $request->reservation_id;

        $paid_before = (int)ReservationPayment::where('reservation_id', $id)->sum('nominal');     

        if( (int)$request->fund > $paid_before )
            return response()->json(['success' => false, 'message' => 'Settlement can not exceed payment']);

        $cash = Cash::find($request->cash_id);
        $initial_balance = $cash->balance;
        $cash->balance -= (int)$request->fund;
        if($cash->balance < 0)
            return response()->json(['success' => false, 'message' => 'Balance of source fund not enought']);
        $cash->save();       
        $mutation = $cash->saveMutation($initial_balance, "8/".$id ); 

        ReservationPayment::create([
            "reservation_id"=> $id,
            "is_dp"=> true,
            "is_deposite"=> false,
            "nominal" => 0, 
            "cash_mutation_id" => $mutation,
            "settlement" => $request->fund
        ]);
        return response()->json(['success' => true]);        
    } 

    public function destroy(Request $request){
        $data = Reservation::find($request->reservation_id);
        if($data == null)
            return response()->json(['success' => false]);
        $data->payment()->delete();
        $data->delete();
        return response()->json(['success' => true]);
    }  

    public function report(Request $request){
        $owner_id = Auth::user()->id;
        $data = Reservation::where('deleted_at',null)
                            ->where('is_confirmed', $request->type)
                            ->whereHas('unit', function($query) use ($owner_id){
                                $query->where('owner_id', $owner_id);
                            })
                            ->get();
        $rawcolumns = ['owner_rent_prices'];
        $table = Datatables::of($data);
        $table->addColumn('unit', function($row){ 
            return $row->unit->name;
        });
        $table->addColumn('tenant', function($row){ 
            return $row->tenant->name; 
        });    
        if($request->type == '1'){
            
            $table->addColumn('status', function($row){
                $paid = Payment::where(function($query) use ($row){
                    $query->where('reservations', 'like', '%['.$row->id.'%')
                    ->orWhere('reservations', 'like', '%,'.$row->id.'%')
                    ->orWhere('reservations', 'like', '%'.$row->id.']%');
                })->where('is_paid', true)->exists();

                if($paid) return '<span class="text-success">PAID</span>';
                else return '<span class="text-danger">UNPAID</span>';
            });
            $rawcolumns[] = "status";
        }

        $table->rawColumns($rawcolumns); 
        return $table->make(true);               
    } 

    public function invoice($id){
        $data = Reservation::find($id);
        if($data == null)
            return [];
        // Load Reservation Payment, Tenant, Unit
        $payment = $data->payment()->sum("nominal");
        $new_data = collect(['paid' => $payment]);
        $data->tenant;
        $data->unit;
        $new_data = $new_data->merge($data);
        return $new_data;
    }
}
