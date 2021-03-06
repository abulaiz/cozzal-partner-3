<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;
use App\Models\CashMutation;
use Datatables;
use Validator;
use App\Libs\CashUtility;
use Storage;

class CashController extends Controller
{

    private $cash_utility;

    public function __construct()
    {
        $this->cash_utility = new CashUtility();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Cash::where('active', true)->get();
        return response()->json($data);
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
            'name' => 'required|string|max:255|unique:cashes,name',
            'balance' => 'required|numeric|min:1',
            'attachment' => 'required'
        ]);

        $validator->setAttributeNames([
            'name' => 'Name', 'balance' => 'Balance', 'attachment' => "Payment Slip"
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);   

        $data = new Cash();
        $data->name = $request->name;
        $data->balance  = $request->balance;
        $data->save();
        $data->saveMutation(0, "2", $request->file('attachment'));

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
        // Update used for add balance

        $validator = Validator::make($request->all(), [
            'fund' => 'required|numeric|min:1',
            'id' => 'required|exists:cashes,id',
            'attachment' => 'required'
        ]);

        $validator->setAttributeNames([
            'id' => 'Referenced Data', 'fund' => 'Fund', 'attachment' => "Payment Slip"
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);   
        
        $data = Cash::find($request->id);

        $old_value = (int)$data->balance;
        $type = '2'; // Source from undefined source

        $data->balance += (int)$request->fund;
        $data->save();
        $data->saveMutation($old_value, $type, $request->file('attachment'));

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
        $data = Cash::find($id);
      
        if($data == null)
            return response()->json(['success' => false, 'errors' => ['Missing referenced data']]);

        if( (int)$data->balance > 0 )
            return response()->json(['errors' => ['Cash not empty, make sure balance is 0'], 'success' => false]);

        $data->active = false;
        $data->name .= " [DELETED]";
        $data->save();

        return response()->json(['success' => true]);   
    }

    public function mutations(){
        $e = $this;
        return Datatables::of(CashMutation::all())
                            ->addColumn('_date', function($row){
                                return $row->created_at;
                            }) 
                            ->addColumn('_executor', function($row){
                                return $row->user->name;
                            })                                  
                            ->addColumn('_cash', function($row){
                                return $row->cash->name;
                            })
                            ->addColumn('_type', function($row){
                                return $row->type_mutation == '1' ? '<span class="text-success">Income</span>' : '<span class="text-danger">Outcome</span>'; 
                            })
                            ->addColumn('_description', function($row) use ($e){
                                return $e->cash_utility->description($row->description);
                            })
                            ->addColumn('_action', function($row){
                                return view('contents.cash.index_mutation_action')->render();
                            })                            
                            ->rawColumns(['_type', '_action']) 
                            ->make(true);        
    }

    public function store_mutation(Request $request){
        $validator = Validator::make($request->all(), [
            'from_cash_id' => 'required|exists:cashes,id',
            'to_cash_id' => 'required|exists:cashes,id',
            'fund' => 'required|numeric|min:1',
            'attachment' => 'required'
        ]);
        $validator->setAttributeNames([
            'from_cash_id' => 'Initial Cash', 'to_cash_id' => 'Destination Cash', 
            'fund' => "Fund", 'attachment' => 'Payment Slip'
        ]);
        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);   

        if( $request->from_cash_id == $request->to_cash_id )
            return response()->json(['errors' => ["Can not using same cash"], 'success' => false]);

        $initial_cash = Cash::find($request->from_cash_id);
        $destination_cash = Cash::find($request->to_cash_id);

        $initial_cash_balance = $initial_cash->balance;
        $destination_cash_balance = $destination_cash->balance;

        $fund = (int)$request->fund;

        $initial_cash->balance -= $fund;
        
        if($initial_cash->balance < 0)
            return response()->json(['errors' => ["Balance not enough"], 'success' => false]);
        
        $initial_cash->save();
        $initial_cash->saveMutation($initial_cash_balance, "1", $request->file('attachment'));

        $destination_cash->balance += $fund;
        $destination_cash->save();
        $destination_cash->saveMutation($destination_cash_balance, "1", $request->file('attachment'), 1);

        return response()->json(['success' => true]);           
    }

    public function payment_slip($cash_mutation_id){
        $data = CashMutation::find($cash_mutation_id);
        ob_end_clean();
        $mime_type = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'bmp' => 'image/bmp',
            'gif' => 'image/gif'
        ];

        $file = $data->attachment;
        $f = explode('.', $file);
        $ext = $f[ count($f)-1 ];

        if(isset($mime_type[$ext]))
            $mime = $mime_type[$ext];
        else
            $mime = 'image/png';

        return response(Storage::get($data->attachment))->header('Content-Type', $mime);    
    }
}
