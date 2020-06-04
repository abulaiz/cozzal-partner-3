<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\CalendarEvent;
use App\Models\ModPrice;
use Auth;
use App\User;
use App\Libs\UnitUtility;

class UnitCalendarController extends Controller
{
	private $unit_utility;

	public function __construct()
	{
		$this->middleware('auth');
		$this->unit_utility = new UnitUtility();		
	}

	private function isCalendarEditable($author_id){
		if($author_id == Auth::user()->id)
			return true;

		$author = User::find($author_id);
		if( $author == null ) return false;

		if($author->hasRole('manager') || $author->hasRole('superadmin')){
			return true;
		}

		return false;
	}


	public function index($id){
		$ce = CalendarEvent::where('unit_id', $id)->get();
		$mp = ModPrice::where('unit_id', $id)->get();

		// Types : Maintenance, Blocked By Admin, Blocked by Owner, Reservation, Mod Price
		$res = [];
		$res['mn'] = []; // Maintenance 
		$res['bba'] = []; // Blocked By Admin
		$res['bbo'] = []; // Blocked by Owner
		$res['resv'] = []; // Reservation
		$res['mp'] = []; // Mod Price

		foreach ($ce as $item) {
			$title_types = ['Maintenance', 'Blocked By Admin', 'Blocked By Owner'];
			$res[  $item->type == '1' ? 'mn' : ($item->type == '2' ? 'bba' : 'bbo') ][] = [
				'title' => $title_types[ (int)$item->type - 1 ],
				'description' => $item->note,
				'start' => $item->started_at, 'end' => $item->ended_at,
				'id' => $item->id, 'type' => $item->type,
				'editable' => $this->isCalendarEditable($item->user_id)
			];
		}

        foreach ($mp as $item) {
            $res['mp'][] = [
                'title' => $item->note == null ? 'Price Override' : $item->note,
                'description' => $item->note,
                'start' => $item->started_at, 'end' => $item->ended_at,
                'price' => $item->price, 'owner_price' => $item->owner_price,
                'id' => $item->id, 'type' => '5',
                'editable' => $this->isCalendarEditable($item->user_id)             
            ];            
        }

		return response()->json($res);
	}

    public function store_unit_availability(Request $request){
        $validator = Validator::make($request->all(), [
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
            'unit_id' => 'required|exists:units,id'
        ]);

        $validator->setAttributeNames([
            'started_at' => 'Started Date', 'ended_at' => 'Ended Date',
            'unit_id' => 'Referenced Data'
        ]);

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);  

        if(strtotime($request->ended_at) <= strtotime($request->started_at)){
        	return response()->json(['success' => false, 'errors' => ['Range of date not valid']]);
        }

        // Check if date has used on another event
        if( $this->unit_utility->is_blocked($request->started_at, $request->ended_at, $request->unit_id) ){
        	return response()->json(['success' => false, 'errors' => ['Range of date has used']]);	
        }

        // Type explanation (1 : Maintenance, 2 : Blocked by admin, 3 : Blocked by Owner)
        $type = $request->maintenance ? '1' : ( Auth::user()->hasRole('owner') ? '3' : '2' );

        CalendarEvent::create([
        	'started_at' => $request->started_at,
        	'ended_at' => $request->ended_at,
        	'unit_id' => $request->unit_id,
        	'type' => $type,
        	'user_id' => Auth::user()->id,
        	'note' => $request->note
        ]);

        return response()->json(['success' => true]);
    }

    public function update_unit_availability(Request $request){
        $validator = Validator::make($request->all(), [
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
            'id' => 'required|exists:calendar_events,id'
        ]);

        $validator->setAttributeNames([
            'started_at' => 'Started Date', 'ended_at' => 'Ended Date',
            'id' => 'Referenced Data'
        ]);        

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);

        $data = CalendarEvent::find($request->id);
        
        // Check if editable
        if(!$this->isCalendarEditable($data->user_id))
     		return response()->json(['errors' => ['Unathorized Access'], 'success' => false]);

        // Check if date has used on another event
        if( $this->unit_utility->is_blocked($request->started_at, $request->ended_at, $request->unit_id, $data->id) ){
        	return response()->json(['success' => false, 'errors' => ['Range of date has used']]);	
        }

        // Type explanation (1 : Maintenance, 2 : Blocked by admin, 3 : Blocked by Owner)
        $type = $request->maintenance ? '1' : ( Auth::user()->hasRole('owner') ? '3' : '2' );
     		
        $data->update([
        	'started_at' => $request->started_at,
        	'ended_at' => $request->ended_at,
        	'type' => $type,
        	'user_id' => Auth::user()->id,
        	'note' => $request->note
        ]);

        return response()->json(['success' => true]);
    }

    public function delete_unit_availability(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:calendar_events,id'
        ]);

        $validator->setAttributeNames([
            'id' => 'Referenced Data'
        ]);        

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);

        $data = CalendarEvent::find($request->id);

        // Check if editable
        if(!$this->isCalendarEditable($data->user_id))
            return response()->json(['errors' => ['Unathorized Access'], 'success' => false]);
        
        $data->delete();
        return response()->json(['success' => true]);      
    }

    public function store_unit_price(Request $request){
        // Input validations
        $validator = Validator::make($request->all(), [
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
            'rent_price' => 'required|numeric',
            'owner_price' => 'required|numeric',
            'unit_id' => 'required|exists:units,id'
        ]);
        $validator->setAttributeNames([
            'started_at' => 'Started Date', 'ended_at' => 'Ended Date',
            'unit_id' => 'Referenced Data', 'rent_price' => 'Rental Price',
            'owner_price' => 'Owner Price'
        ]);
        if(strtotime($request->ended_at) <= strtotime($request->started_at)){
            return response()->json(['success' => false, 'errors' => ['Range of date not valid']]);
        }
        // Check if date has used on another event
        if( $this->unit_utility->has_prices($request->started_at, $request->ended_at, $request->unit_id) ){
            return response()->json(['success' => false, 'errors' => ['Range of date has used']]);  
        }

        // Store Data
        ModPrice::create([
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
            'unit_id' => $request->unit_id,
            'price' => $request->rent_price,
            'owner_price' => $request->owner_price,
            'user_id' => Auth::user()->id,
            'note' => $request->note
        ]);

        // Output Response
        return response()->json(['success' => true]);
    }

    public function update_unit_price(Request $request){
        $validator = Validator::make($request->all(), [
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
            'rent_price' => 'required|numeric',
            'owner_price' => 'required|numeric',
            'id' => 'required|exists:mod_prices,id'
        ]);
        $validator->setAttributeNames([
            'started_at' => 'Started Date', 'ended_at' => 'Ended Date',
            'id' => 'Referenced Data', 'rent_price' => 'Rental Price',
            'owner_price' => 'Owner Price'
        ]);        
        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);

        $data = ModPrice::find($request->id);
        
        // Check if editable
        if(!$this->isCalendarEditable($data->user_id))
            return response()->json(['errors' => ['Unathorized Access'], 'success' => false]);

        // Check if date has used on another event
        if( $this->unit_utility->has_prices($request->started_at, $request->ended_at, $request->unit_id, $data->id) ){
            return response()->json(['success' => false, 'errors' => ['Range of date has used']]);  
        } 

        $data->update([
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
            'price' => $request->rent_price,
            'owner_price' => $request->owner_price,
            'user_id' => Auth::user()->id,
            'note' => $request->note
        ]);

        return response()->json(['success' => true]);               
    }

    public function delete_unit_price(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:mod_prices,id'
        ]);

        $validator->setAttributeNames([
            'id' => 'Referenced Data'
        ]);        

        if( $validator->fails() )
            return response()->json(['errors' => $validator->errors()->all(), 'success' => false]);

        $data = ModPrice::find($request->id);

        // Check if editable
        if(!$this->isCalendarEditable($data->user_id))
            return response()->json(['errors' => ['Unathorized Access'], 'success' => false]);
        
        $data->delete();
        return response()->json(['success' => true]);           
    }        
}
