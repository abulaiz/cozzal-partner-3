<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Datatables;
use App\Models\Expenditure;
use App\Models\Cash;

class ExpenditureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        if($type == '1')
            $data = Expenditure::where('is_billing', false)->get();
        elseif($type == '2')
            $data = Expenditure::where('is_billing', true)
                                ->where('due_at', '!=', null)
                                ->get();
        elseif($type == '3')
            $data = Expenditure::where('is_billing', true)
                                ->where('due_at', null)
                                ->get();
        return Datatables::of($data)
                        ->addColumn('_cash', function($row){
                            return $row->is_paid ? $row->cash->name : '';
                        })
                        ->addColumn('_necessary', function($row){
                            return $row->unit_id == null ? "General" : $row->unit->unit_number." - ".$row->unit->apartment->name;
                        })
                        ->addColumn('_total', function($row){ return $row->qty*$row->price; })
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
