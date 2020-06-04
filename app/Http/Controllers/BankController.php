<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Datatables;
use Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatables::of(Bank::all())
                            ->addColumn('_action', function($row){
                                return View('contents.bank.index_table_action', compact('row'))->render();
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:banks,name',
            'code' => 'required|string|max:255'
        ]);

        $validator->setAttributeNames([
            'name' => 'Name', 'code' => 'Code',
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);   

        Bank::create([
            'name' => $request->name, 'bank_code' => $request->code
        ]);     

        return response()->json(['success' => true]);
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
        $request->merge(['id' => $id]);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'id' => 'required|exists:banks,id'
        ]);

        $validator->setAttributeNames([
            'name' => 'Name', 'code' => 'Code', 'id' => 'Referenced data'
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);   

        Bank::find($id)->update([
            'name' => $request->name, 'bank_code' => $request->code
        ]);     

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
        $data = Bank::find($id);
        if($data == null)
            return response()->json(['success' => false]);

        $data->delete();

        return response()->json(['success' => true]);
    }
}
