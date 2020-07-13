<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Datatables;
use App\Models\Expenditure;
use App\Models\Cash;
use App\Models\Payment;
use Auth;

class ExpenditureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        if($type == '1') {
            $data = Expenditure::where('is_paid', true);
        } elseif($type == '2') {
            $data = Expenditure::where('is_billing', true)
                                ->where('due_at', '!=', null);
        } elseif($type == '3') {
            $data = Expenditure::where('is_billing', true)
                                ->where('due_at', null);
        }

        if(Auth::user()->hasRole('owner')){
            $data->whereHas('unit', function($query){
                    $query->where('owner_id', Auth::user()->id );
            });
        }
        
        $table = Datatables::of($data->get());
        
        $table->addColumn('_total', function($row){ return $row->qty*$row->price; });
        
        if(Auth::user()->hasRole('owner')){
            
            $table->addColumn('_status', function($row){
                $paid = Payment::where(function($query) use ($row){
                    $query->where('expenditures', 'like', '%['.$row->id.'%')
                    ->orWhere('expenditures', 'like', '%,'.$row->id.'%')
                    ->orWhere('expenditures', 'like', '%'.$row->id.']%');
                })->where('is_paid', true)->exists();

                if($paid) return '<span class="text-success">PAID</span>';
                else return '<span class="text-danger">UNPAID</span>';
            });

            $table->addColumn('_unit', function($row){
                return $row->unit->name;
            }); 
            $table->rawColumns(['_status']);            
        } else {
            $table->addColumn('_necessary', function($row){
                return $row->unit_id == null ? "General" : $row->unit->unit_number." - ".$row->unit->apartment->name;
            });          
            $table->addColumn('_action', function() use ($type){
                return View('contents.expenditure.table_action', compact('type'))->render();
            });
            if( $type == '1' ){
                $table->addColumn('_cash', function($row){
                    return $row->cash->name;
                });               
            }
            $table->rawColumns(['_action']);
        }

        return $table->make(true);
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
    public function store(Request $request)
    {
        $v_rules = ['price' => 'required|numeric|min:1'];
        $v_name = ['price' => 'Price'];
        $v_rules['qty'] = 'required|numeric|min:1';
        $v_name['qty'] = 'Quantity';
        $v_rules['description'] ='required|string|max:255';
        $v_name['description'] = 'Description';        

        if($request->expenditure_type == '1'){
            $v_rules['cash_id'] = 'required|exists:cashes,id';
            $v_name['cash_id'] = "Source Fund";
        } elseif( $request->expenditure_type == '2' ){
            $v_rules['due_at'] = 'required|date';
            $v_name['due_at'] = "Due At";
        }

        if($request->expenditure_necessary == '2'){
            $v_rules['unit_id'] = 'required|exists:units,id';
            $v_name['unit_id'] = "Unit";            
        }

        $validator = Validator::make($request->all(), $v_rules);
        $validator->setAttributeNames($v_name);
        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);

        if($request->expenditure_type == '1'){
            $cash = Cash::find($request->cash_id);
            if( $cash->balance < $request->price*$request->qty ){
                return response()->json(['success' => false, 'errors' => ['Balance of source fund is not enough']]);
            }
        }

        $data = new Expenditure();
        $data->price = $request->price;
        $data->qty = $request->qty;
        $data->description = $request->description;
        $data->unit_id = $request->unit_id;
        $data->due_at = $request->due_at;
        $data->setType($request->expenditure_type);
        $data->setCash($request->cash_id);
        $data->save();

        $type = $request->expenditure_type;

        return response()->json([
            'success' => true,
            'direct_route' => route( $type == '1' ? 'expenditure' : 'expenditure.approval'),
            'direct_path' => $type == '1' ? 'message.expenditure.index' : 'message.expenditure.approval',
            'message' => $type == '1' ? "Expenditure successfuly added" : "Approval expenditure successfuly added"
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

    public function approve(Request $request){
        $id = $request->id;
        $data = Expenditure::find($id);
        if($data == null) 
            return response()->json(['success' => false, 'message' => 'Something wrong, please refresh page']);
        // type of approval (1 : Direct approve, 2 : Billing Approve)
        $type = $request->type;
        if( $type == '1' ){
            if($request->cash_id == null)
                return response()->json(['success' => false, 'message' => 'Source fund required !']);
            $cash = Cash::find($request->cash_id);
            if($cash->balance < ($data->price * $data->qty))
                return response()->json(['success' => false, 'message' => 'Balance of source fund is not enough']);    
            $data->setType("1");
            $data->setCash($request->cash_id);           
        } elseif($type == '2'){
            if($request->due_at == null)
                return response()->json(['success' => false, 'message' => 'Due date is required !']);
            $data->due_at = $request->due_at;            
        }
        $data->save();
        return response()->json(['success' => true]);
    }

    public function pay(Request $request){
        $id = $request->id;
        $data = Expenditure::find($id);
        if($data == null) 
            return response()->json(['success' => false, 'message' => 'Something wrong, please refresh page']);
        if($request->cash_id == null)
            return response()->json(['success' => false, 'message' => 'Source fund required !']);
        $cash = Cash::find($request->cash_id);
        if($cash->balance < ($data->price * $data->qty))
            return response()->json(['success' => false, 'message' => 'Balance of source fund is not enough']);    
        $data->setType("1");
        $data->setCash($request->cash_id);       
        $data->save();
        return response()->json(['success' => true]);                              
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Expenditure::find($id);
        if($data == null) return response()->json(['success' => false]);
        $cash_mutation = $data->cash_mutation;
        if($cash_mutation != null){
            $amount = $data->qty * $data->price;
            $cash = Cash::find($cash_mutation->cash_id);
            $cash->balance += $amount;
            $cash->save();
            $data->cash_mutation()->delete();
        }
        $data->delete();
        return response()->json(['success' => true]);
    }
}
