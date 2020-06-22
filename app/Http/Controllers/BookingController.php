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

    /* Defined type can be ... 
        'dp', 'deposit', 'payment' (normal payment / cicilan)
    */
    private function make_payment($type, $reservation_id, $cash_id, $nominal){
        $cash = Cash::find($cash_id);
        $initial_balance = $cash->balance;
        $cash->balance += (int)$nominal;
        $cash->save();
        $prefix_status = [
            'dp' => '6', 'deposit' => '12', 'payment' => '6'
        ];
        $mutation = $cash->saveMutation($initial_balance, $prefix_status[$type]."/".$reservation_id );        
        
        $is_dp = $type == 'dp'; 
        $is_deposit = $type == 'deposit';

        ReservationPayment::create([
            "reservation_id"=> $reservation_id,
            "is_dp"=> $is_dp,
            "is_deposite"=> $is_deposit,
            "nominal" => $nominal, 
            "cash_mutation_id" => $mutation
        ]);
    }

    public function index(){
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

    public function create(){}

    public function store(Request $request){
        $amount_bill = $request->amount_bill - $request->deposite;
        $dp = $request->dp - $request->deposite;
        $discount = ($request->normal_amount_bill - $request->deposite) - $amount_bill;
        $discount = ($discount<0 ? 0 : $discount);

        $data = Reservation::create([
            "tenant_id" => $request->tenant_id, 
            "unit_id" => $request->unit_id,
            "booking_via_id" => $request->booking_via_id,
            "check_in" => $request->check_in,
            "check_out" => $request->check_out,
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
            "is_confirmed" => false
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

    public function payment_info($id){
        $data = Reservation::find($id);

        $a = ReservationPayment::where([['reservation_id',$id],['settlement',null],['is_deposite',true]])->get();
        $deposit = (int)(count($a)>0 ? $a[0]->nominal : 0);

        $a = ReservationPayment::where([['reservation_id',$id],['settlement',null],['is_dp',true]])->get();
        $dp = (int)(count($a)>0 ? $a[0]->nominal : 0);

        $total_amount = $data->amount_bill;
        $has_pay = (int)ReservationPayment::where([ ['reservation_id',$id],['settlement',null] ])->sum('nominal');
        $remaining_payment = (int)$total_amount - ((int)$has_pay - $deposit);
        $deposit_settled = count( $data->payment->where('is_deposite', true)->where('settlement','!=',null) );

        return response()->json([
            "total_amount" => $total_amount, "has_pay" => (int)$has_pay, 
            "status" => ($has_pay >= $total_amount ? "Settled" : "Unsettled"),
            "deposit" => $deposit, "dp" => $dp, 'remaining_payment' => $remaining_payment,
            "deposit_status" => ($deposit_settled == 0 ? "Unsettled" : "Settled")
        ]);        
    }

    public function store_payment(Request $request){
        $this->make_payment('payment', $request->reservation_id, $request->cash_id, $request->fund);
        return response()->json(['success' => true]);
    }

    public function settlementDeposit(Request $request){
        $id = $request->reservation_id;
        $a = ReservationPayment::where([['reservation_id',$id],['settlement',null],['is_deposite',true]])->get();
        $deposit = (count($a)>0 ? $a[0]->nominal : 0);

        $cash = Cash::find($request->cash_id);
        $initial_balance = $cash->balance;
        $cash->balance -= (int)$deposit;
        if($cash->balance < 0)
            return response()->json(['success' => false]);
        $cash->save();       
        $mutation = $cash->saveMutation($initial_balance, "13/".$id ); 

        ReservationPayment::create([
            "reservation_id"=> $id,
            "is_dp"=> false,
            "is_deposite"=> true,
            "nominal" => 0, 
            "cash_mutation_id" => $mutation,
            "settlement" => $deposit
        ]);
        return response()->json(['success' => true]);        
    }

    public function confirm(Request $request){
        $id = $request->reservation_id;
        $res = Reservation::find($id);
        $has_pay = (int)ReservationPayment::where([ ['reservation_id',$id],['settlement',null] ])->sum('nominal');

        $pay_deposit = ReservationPayment::where([['reservation_id',$id],['is_deposite',true], ['settlement','!=',null]])->exists();
        $deposit = ReservationPayment::where([['reservation_id',$id],['is_deposite',true], ['settlement',null]]);

        $have_deposit = ($deposit->count() == 0 || $deposit->first()->nominal == 0 ? false : true );

        if((int)$res->amount_bill <=$has_pay && ($pay_deposit || !$have_deposit ) ){
            $res->is_confirmed = true;
            $res->save();
            return response()->json([
                'success' => true, 
                'direct_path' => 'message.reservation.confirmed',
                'message' => 'Successfuly confirm reservation COZ-'. strtoupper( dechex( $id ) ),
                'direct_route' => route('reservation.confirmed')
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function show($id){}

    public function edit($id){}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){}

    public function destroy($id){
        $pay_deposit = ReservationPayment::where([['reservation_id',$id],['is_deposite',true], ['settlement','!=',null]])->exists();
        $deposit = ReservationPayment::where([['reservation_id',$id],['is_deposite',true], ['settlement',null]]);
        $have_deposit = ($deposit->count() == 0 || $deposit->first()->nominal == 0 ? false : true );
        if( $have_deposit && !$pay_deposit )
            return response()->json([ 'success' => false ]);

        $res = Reservation::find($id);
        if($res == null) return response()->json([ 'success' => false ]);
        $res->deleted_at = date("Y-m-d H:i:s");
        $res->save();
        return response()->json([ 
            'success' => true,
            'direct_path' => 'message.reservation.canceled',
            'message' => 'COZ-'. strtoupper( dechex( $id ) )." has been canceled",
            'direct_route' => route('reservation.canceled')            
        ]);     
    }
}
