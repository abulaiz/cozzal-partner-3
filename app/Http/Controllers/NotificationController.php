<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenditure;
use App\Models\Payment;
use Auth;

class NotificationController extends Controller
{
    public function index(){
    	return response()->json([
    		'expenditure' => $this->expenditure(),
            'payment_report' => $this->payment_report(),
            'owner_payment' => $this->owner_payment()

    	]);
    }

    private function payment_report(){
        $notified = Auth::user()->hasRole('owner');
        $waiting_payment = 0;

        if($notified){
            $waiting_payment = Payment::where('owner_id', Auth::user()->id)
                                        ->where('is_rejected',false)
                                        ->where('is_paid', false)
                                        ->where('is_accepted', false)
                                        ->count();
        }

        return [
            'notified' => $notified,
            'waiting_payment' => $waiting_payment        
        ];         
    }

    private function owner_payment(){
        $notified = Auth::user()->hasRole('manager');
        $accepted_payment = 0;

        if($notified){
            $accepted_payment = Payment::where('is_accepted',true)
                                        ->where('is_paid', false)
                                        ->count();
        }

        return [
            'notified' => $notified,
            'accepted_payment' => $accepted_payment        
        ];             
    }

    private function expenditure(){
        $notified = Auth::user()->hasRole('manager');
        $expired_expenditure = 0;
        $need_to_approve = 0;

        if($notified){
            $now = date("Y-m-d");
            $expired_expenditure = Expenditure::whereDate('due_at','<',$now)
                                              ->where('is_billing', true)
                                              ->count();
            $need_to_approve = Expenditure::where('is_billing', true)
                                            ->where('due_at')
                                            ->count();            
        }
        return [
            'notified' => $notified,
            'expired_expenditure' => $expired_expenditure,
            'need_to_approve' => $need_to_approve            
        ];  
    }
}
