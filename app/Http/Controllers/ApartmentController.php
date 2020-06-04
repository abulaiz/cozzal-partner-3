<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use Datatables;
use Validator;


class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatables::of(Apartment::all())
                            ->addColumn('_action', function($row){
                                return View('contents.apartment.index_table_action', compact('row'))->render();
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
            'name' => 'required|string|max:255|unique:apartments,name',
            'address' => 'required|string|max:255'
        ]);

        $validator->setAttributeNames([
            'name' => 'Name', 'address' => 'Address',
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);   

        Apartment::create([
            'name' => $request->name, 'address' => $request->address
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
            'address' => 'required|string|max:255',
            'id' => 'required|exists:apartments,id'
        ]);

        $validator->setAttributeNames([
            'name' => 'Name', 'address' => 'Address', 'id' => 'Referenced data'
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);   

        Apartment::find($id)->update([
            'name' => $request->name, 'address' => $request->address
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
        $data = Apartment::find($id);
        if($data == null)
            return response()->json(['success' => false]);

        $data->delete();

        return response()->json(['success' => true]);
    }
}
