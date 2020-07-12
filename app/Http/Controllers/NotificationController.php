<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenditure;
use Auth;

class NotificationController extends Controller
{
    public function index(){
    	if(!Auth::user()->hasRole('manager'))
    		return response()->json(['notified' => false]);


    	$now = date('Y-m-d');
    	$expired_expenditure = Expenditure::whereDate('due_at','<',$now)
    									  ->where('is_billing', true)
    									  ->count();
    	$need_to_approve = Expenditure::where('is_billing', true)
    									->where('due_at')
    									->count();
    	return response()->json([
    		'notified' => true,
    		'expired_expenditure' => $expired_expenditure,
    		'need_to_approve' => $need_to_approve
    	]);
    }
}
