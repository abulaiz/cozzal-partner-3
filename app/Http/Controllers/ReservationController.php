<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use App\Models\Cash;

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
                            return '<p class="text-primary">Belum Ada</p>';
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
                                            ->where('is_dp', true)
                                            ->sum('nominal');
                        })     
                        ->addColumn('settlement', function($row){
                            return (int)$row->payment()
                                            ->where('is_dp', true)
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

        $cash = Cash::find($request->cash_id);
        $initial_balance = $cash->balance;
        $cash->balance -= (int)$request->fund;
        if($cash->balance < 0)
            return response()->json(['success' => false]);
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
}
