<?php 

namespace App\Libs;

use App\Models\CalendarEvent;
use App\Models\ModPrice;

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

    public function has_prices($start, $end, $unit_id, $id = null){
        return $this->price_query($start, $end, $unit_id, $id)->exists();
    }

}