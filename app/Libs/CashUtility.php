<?php 
namespace App\Libs;

use App\Models\Unit;

class CashUtility
{

  	public function description($description){
    	$base = explode("/",$description);
    	$id = $base[0];
    	// $des = array("1"=>"Dari Kas", "2"=>"Sumber Non Kas", "3"=>"General Expenditure",
    	// 	"4"=>"Owner Payment", "5"=>"Penggajian Karyawan", "6"=>"Transaksi : COZ-",
    	// 	"7"=>"Payment : COZ-", "8"=>"Setlement DP : COZ-", "9"=>"Unit Expenditure"
    	// 	, "10"=>"Unit Expenditure", "11"=>"General Expenditure", "12"=>"Deposite : COZ-",
     //    "13"=>"Setlement Deposite : COZ-"
    	// );

    	$desc = [
    		"1" => "Cash", 
    		"2" => "Unknown / Initial Source" ,
    		"3" => "General Expenditure",
    		"4" => "Owner Payment",
    		"5" => "-",
    		"6" => "Transaction : COZ-",
    		"7" => "Payment : COZ-",
    		"8" => "Setlement DP : COZ-",
    		"9" => "Unit Expenditure",
    		"10" => 'Unit Expenditure',
    		"11" => "General Expenditure",
    		"12" => "Deposite : COZ-",
    		"13" => "Setlement Deposite : COZ-"
    	];

    	$ket = $desc[$id];
    	if($desc[$id][ strlen($desc[$id]) - 1 ] == "-") 
    		$ket .= strtoupper(dechex($base[1]));
    	elseif($id=="9" || $id=="10")
    		$ket .= " ".$this->getUnitNumber($base[1]);
    	return $ket;
  	}

  	private function getUnitNumber($id){
		$unit = Unit::find($id);
		if($unit == null)
			return ' - ';
		return $unit->unit_number;
  	}

}