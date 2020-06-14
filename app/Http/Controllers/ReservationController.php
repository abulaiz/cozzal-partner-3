<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Models\Reservation;

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

    }
}
