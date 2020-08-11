<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use App\Models\Unit;
use App\Models\Expenditure;
use Auth;

class DashboardController extends Controller
{

    private function sumDays($year){
		if($year % 400 == 0){
			return $sumDays = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
		}else if(($year % 4 == 0) && ($year % 100 != 1) && ($year % 400 != 0)){
			return $sumDays = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
		}else{
			return $sumDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
		}
    }

    private function explodeDateTime($dateTime){
    	$str = explode("-", $dateTime);
    	return (object)[
    		'day' => (int)$str[2],
    		'month' => (int)$str[1],
    		'year' => (int)$str[0],
    	];
    }

    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%a'){
      $datetime1 = date_create($date_1);
      $datetime2 = date_create($date_2);
      $interval = date_diff($datetime1, $datetime2);
      return $interval->format($differenceFormat);
    }

    private function describe_monthly_transaction($datas, $overDays, $sumDays, $i){	
    	$monthDays = 0;
    	$newOverDays = 0;
    	foreach ($datas as $data) {
        	$checkInMonth = $this->explodeDateTime($data->check_in);
        	$checkOutMonth = $this->explodeDateTime($data->check_out);
        	$stayInterval = $this->dateDifference($data->check_in , $data->check_out);

        	if ($checkInMonth->month != $checkOutMonth->month) {
          		$monthDays += $sumDays[$i-1] + 1 - $checkInMonth->day;
          		$newOverDays += $checkOutMonth->day - 1;
            } else {
            	$monthDays += $stayInterval;
            	$newOverDays += 0;
            }
      	}	
		
		$monthDays += $overDays;
		
		return (object)[
			'overDays' => $newOverDays,
			'monthDays' => $monthDays
		];
    }

    public function transaction_statistic($year, $unit_id = null){
    	$reservation = [];
    	$booking = [];
    	$canceled = [];
    	$sumDays = $this->sumDays($year);
    	$overDays = [
    		'reservation' => 0, 'booking' => 0, 'canceled' => 0
    	];

    	for($i = 1; $i <= 12; $i++){
    		$reservations = Reservation::whereYear('check_in', $year)
    									->whereMonth('check_in', $i)
    									->where('is_confirmed', true);
    		$bookings = Reservation::whereYear('check_in', $year)
    									->whereMonth('check_in', $i)
    									->where('deleted_at', null)
    									->where('is_confirmed', false);
    		$cancellations = Reservation::whereYear('check_in', $year)
    									->whereMonth('check_in', $i)
    									->where('deleted_at','!=', null);

            $reservations = $unit_id == null ? $reservations->get() : $reservations->where('unit_id', $unit_id)->get();         
            $bookings = $unit_id == null ? $bookings->get() : $bookings->where('unit_id', $unit_id)->get();         
            $cancellations = $unit_id == null ? $cancellations->get() : $cancellations->where('unit_id', $unit_id)->get();			


    		$dr = $this->describe_monthly_transaction($reservations, $overDays['reservation'], $sumDays, $i);
    		$db = $this->describe_monthly_transaction($bookings, $overDays['booking'], $sumDays, $i);
    		$dc = $this->describe_monthly_transaction($cancellations, $overDays['canceled'], $sumDays, $i);

    		$overDays['reservation'] = $dr->overDays;
    		$overDays['booking'] = $db->overDays;
    		$overDays['canceled'] = $dc->overDays;

    		$reservation[] = $dr->monthDays;
    		$booking[] = $db->monthDays;
    		$canceled[] = $dc->monthDays;
    	}

    	return [
    		'reservation' => $reservation,
    		'booking' => $booking,
    		'canceled' => $canceled
    	];
    }

    public function income_statistic($year){
    	$income = [];
    	$gross_profit = [];

    	for($i = 1; $i <= 12; $i++){
    		$reservation_payments = ReservationPayment::whereYear('created_at', $year)
															->whereMonth('created_at', $i)
															->get();
    		$reservations = Reservation::whereYear('check_in', $year)
    									->whereMonth('check_in', $i)
    									->where('deleted_at', null)
    									->get();

            $income_total = 0;
            foreach ($reservation_payments as $data) {
                $income_total += (int)$data->nominal - (int)$data->settlement;
            }

    		$monthly_owner_payment = 0;
    		foreach ($reservations as $item) {
    			$ownerPrice = (json_decode($item->owner_rent_prices))->TP;
    			$monthly_owner_payment += $ownerPrice;
    		}

    		$income[] = $income_total ;    		$profit = $income_total  - $monthly_owner_payment;
    		$gross_profit[] = $profit < 0 ? 0 : $profit;
    	}
    	return [
    		'income' => $income,
    		'gross_profit' => $gross_profit
    	];
    }

    public function owner_income_statistic($year){
        $units = Unit::where('owner_id', Auth::user()->id)->get();
        $res = [];
        foreach ($units as $unit) {
            $res[$unit->unit_number] = [];
            for($i = 1; $i <= 12; $i++){
                $res[$unit->unit_number][] = 0;
                $data = Reservation::where('unit_id', $unit->id)
                                    ->where('is_confirmed', true)
                                    ->whereYear('check_in', $year)
                                    ->whereMonth('check_in', $i)
                                    ->get();
                foreach ($data as $item) {
                    $res[$unit->unit_number][$i-1] += (int)json_decode($item->owner_rent_prices)->TP;
                }
            }            
        }
        return $res;
    }

    public function owner_outcome_statistic($year){
        $units = Unit::where('owner_id', Auth::user()->id)->get();
        $res = [];
        foreach ($units as $unit) {
            $res[$unit->unit_number] = [];
            for($i = 1; $i <= 12; $i++){
                $res[$unit->unit_number][] = 0;
                $data = Expenditure::where('unit_id', $unit->id)
                                    ->where('is_paid', true)
                                    ->whereYear('updated_at', $year)
                                    ->whereMonth('updated_at', $i)
                                    ->get();
                foreach ($data as $item) {
                    $res[$unit->unit_number][$i-1] += (int)$item->price * (int)$item->qty;
                }
            }            
        }
        return $res;
    }

    public function owner_reservation_statistic($year){
        $units = Unit::where('owner_id', Auth::user()->id)->get();
        $res = [];
        foreach ($units as $unit) {
            $data = $this->transaction_statistic($year, $unit->id);
            $res[$unit->unit_number] = [];
            foreach ($data['reservation'] as $key => $item) {
                $res[$unit->unit_number][] = $data['reservation'][$key] + $data['booking'][$key]; 
            }
        }
        return $res;
    }
}
