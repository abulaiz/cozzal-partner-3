<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use App\Models\Cash;
use App\Models\Tenant;
use Datatables;

class BookingController extends Controller
{
    private $default_check_in = "12:00:00";
    private $default_check_out = "12:00:00";

    private function make_payment($type, $reservation_id, $cash_id, $nominal){
        $cash = Cash::find($cash_id);
        $initial_balance = $cash->balance;
        $cash->balance += (int)$nominal;
        $cash->save();
        $mutation = $cash->saveMutation($initial_balance, ($type=="dp" ? "6":"12")."/".$reservation_id );        
        
        $is_dp = true; $is_deposit = true;

        if($type=='dp') 
            $is_deposit = false; 
        else 
            $is_dp = false;

        ReservationPayment::create([
            "reservation_id"=> $reservation_id,
            "is_dp"=> $is_dp,
            "is_deposite"=> $is_deposit,
            "nominal" => $nominal, 
            "cash_mutation_id" => $mutation
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Reservation::where('deleted_at', null)
                             ->where('is_confirmed', false)
                             ->orderByDesc('created_at')
                             ->get(['id', 'check_in', 'check_out', 'tenant_id', 'unit_id', 'unit_id']);
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
                            return View('contents.booking.index_table_action', compact('row'))->render();
                        })
                        ->rawColumns(['_action'])
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $amount_bill = $request->amount_bill - $request->deposite;
        $dp = $request->dp - $request->deposite;
        $discount = ($request->normal_amount_bill - $request->deposite) - $amount_bill;
        $discount = ($discount<0 ? 0 : $discount);

        $data = Reservation::create([
            "tenant_id" => $request->tenant_id, 
            "unit_id" => $request->unit_id,
            "booking_via_id" => $request->booking_via_id,
            "check_in" => $request->check_in." ".$this->default_check_in,
            "check_out" => $request->check_out." ".$this->default_check_out,
            "guest" => $request->guest, 
            "notes" => $request->note,
            "owner_rent_prices" => json_encode([
                "WD" => $request->owner_weekday_price,
                "WE" => $request->owner_weekend_price,
                "WK" => $request->owner_weekly_price,
                "MN" => $request->owner_monthly_price,
                "TP" => $request->owner_price_total
            ]),
            "rent_prices" => json_encode([
                "WD" => $request->rent_weekday_price,
                "WE" => $request->rent_weekend_price,
                "WK" => $request->rent_weekly_price,
                "MN" => $request->rent_monthly_price,
                "TP" => $request->rent_price_total
            ]),            
            "charge" => $request->charge, 
            "discount" => $discount, 
            "amount_bill" => $amount_bill,
            "is_confirmed" => false, 
            "is_paid" => false
        ]);

        if($request->dp > 0) {
            if($request->deposite > 0) 
                $this->make_payment('deposit', $data->id, $request->cash_id, $request->deposite);
            if($dp > 0) 
                $this->make_payment('dp', $data->id, $request->cash_id, $dp);
        }

        return response()->json([
            'success' => true, 
            'message' => "Successfuly add booking data", 
            'direct_path' => 'message.booking.index',
            'direct_route' => route('booking')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
