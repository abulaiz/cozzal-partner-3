<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Datatables;
use Validator;
use App\Libs\UnitUtility;

class UnitController extends Controller
{
    private $unit_utility;

    public function __construct()
    {
        $this->middleware('auth');
        $this->unit_utility = new UnitUtility();        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // First check if request unit list by apartment_id
        if( isset($request->apartment_id) ){
            $data = Unit::where('apartment_id', $request->apartment_id)->get();
            return response()->json($data);
        }

        return Datatables::of(Unit::all())
                            ->addColumn('_action', function($row){
                                return View('contents.unit.index_table_action', compact('row'))->render();
                            })
                            ->addColumn('_apartment', function($row){
                                return $row->apartment->name;
                            })
                            ->addColumn('_owner', function($row){
                                return $row->owner->name;
                            }) 
                            ->addColumn('see_more', function($row){
                                return '<a href="javascript:void(0);">See more</a>';
                            })                                                        
                            ->rawColumns(['_action', 'rent_price', 'owner_rent_price', 'see_more'])        
                            ->make(true);        
    }

    public function available_unit(Request $request){
        $apartment_id = $request->apartment_id;
        $check_in = $request->check_in;
        $check_out = $request->check_out;
        return response()->json( $this->unit_utility->available_unit($apartment_id, $check_in, $check_out) );
    } 

    public function prices_mod(Request $request){
        $unit_id = $request->unit_id;
        $check_in = $request->check_in;
        $check_out = $request->check_out;
        return response()->json( $this->unit_utility->mods_price_list($unit_id, $check_in, $check_out) );
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
        //
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
        $data = Unit::find($id);
        return response()->json($data);
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
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|exists:owners,id',
            'apartment_id' => 'required|exists:apartments,id',
            'owner_price_weekday' => 'required|numeric|min:1',
            'owner_price_weekend' => 'required|numeric|min:1',
            'owner_price_weekly' => 'required|numeric|min:1',
            'owner_price_monthly' => 'required|numeric|min:1',

            'rent_price_weekday' => 'required|numeric|min:1',
            'rent_price_weekend' => 'required|numeric|min:1',
            'rent_price_weekly' => 'required|numeric|min:1',
            'rent_price_monthly' => 'required|numeric|min:1',

            'charge' => 'required|numeric|min:1'
        ]);

        $validator->setAttributeNames([
            'owner_id' => 'Owner',
            'apartment_id' => 'Apartment',
            'owner_price_weekday' => 'Owner Price Weekday',
            'owner_price_weekend' => 'Owner Price Weekend',
            'owner_price_weekly' => 'Owner Price Weekly',
            'owner_price_monthly' => 'Owner Price Monthly',

            'rent_price_weekday' => 'Rent Price Weekday',
            'rent_price_weekend' => 'Rent Price Weekend',
            'rent_price_weekly' => 'Rent Price Weekly',
            'rent_price_monthly' => 'Rent Price Monthly',

            'charge' => 'Extra Charge'
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);  

        $data = Unit::find($id);

        if($data == null)
            return response()->json(['errors' => ['Referenced data not found'], 'success' => false]);


        $data->update([
            'owner_id' => $request->owner_id,
            'apartment_id' => $request->apartment_id,
            'unit_number' => $request->unit_number,
            'owner_rent_price' => json_encode([
                'WD' => $request->owner_price_weekday,
                'WE' => $request->owner_price_weekend,
                'WK' => $request->owner_price_weekly,
                'MN' => $request->owner_price_monthly
            ]),
            'rent_price' => json_encode([
                'WD' => $request->rent_price_weekday,
                'WE' => $request->rent_price_weekend,
                'WK' => $request->rent_price_weekly,
                'MN' => $request->rent_price_monthly                
            ]),
            'charge' => $request->charge
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
        $data = Unit::find($id);
        $data->delete();
        return response()->json(['success' => true]);
    }
}
