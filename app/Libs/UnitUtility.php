<?php 

namespace App\Libs;

use App\Models\CalendarEvent;
use App\Models\ModPrice;
use App\Models\Reservation;
use App\Models\Unit;

class UnitUtility
{
    private function price_query($start, $end, $unit_id, $id = null){
        return ModPrice::where('unit_id',$unit_id)
                                        ->where(function($q) use ($start, $end){
                                            $q->where([['started_at','<=',$start],['ended_at','>=',$end]])
                                            ->orWhere([['started_at','>=',$start],['started_at','<',$end]])
                                            ->orWhere([['ended_at','>',$start],['ended_at','<=',$end]]);
                                        })
                                        ->where('id', '!=', $id);
    }

    // Is unit blocked in Cozzal Calendar System
    // $id is used for validation on update mode
    public function is_blocked($start, $end, $unit_id, $id = null){ 
        return CalendarEvent::where('unit_id',$unit_id)
                                        ->where(function($q) use ($start, $end){
                                            $q->where([['started_at','<=',$start],['ended_at','>=',$end]])
                                            ->orWhere([['started_at','>=',$start],['started_at','<',$end]])
                                            ->orWhere([['ended_at','>',$start],['ended_at','<=',$end]]);
                                        })
                                        ->where('id', '!=', $id)
                                        ->exists();
    }

    public function is_booked($ci, $co, $unit_id){
        $x = Reservation::where([ ['unit_id',$unit_id],['deleted_at',null] ])
                            ->where(function($q) use ($ci, $co){
                                $q->where([['check_in','<=',$ci],['check_out','>=',$co]])
                                ->orWhere([['check_in','>=',$ci],['check_in','<',$co]])
                                ->orWhere([['check_out','>',$ci],['check_out','<=',$co]]);
                            })
                            ->exists();
    }

    public function has_prices($start, $end, $unit_id, $id = null){
        return $this->price_query($start, $end, $unit_id, $id)->exists();
    }

    public function mods_price_list($unit_id, $ci, $co){
        return $this->price_query($ci, $co, $unit_id)->get();
    }

    public function available_unit($apartment_id, $ci, $co){
        $data = Unit::where('apartment_id',$apartment_id)->orderBy('unit_number')->get();
        $res = [];
        foreach ($data as $unit) {
            $val1 = $this->is_booked($ci, $co, $unit->id);
            $val2 = $this->is_blocked($ci, $co, $unit->id);
            if(!$val1 && !$val2)
                $res[] = $unit;
        }

        return $res;        
    }
}