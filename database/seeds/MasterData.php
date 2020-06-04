<?php

use Illuminate\Database\Seeder;
use App\Models\Bank;
use App\Models\BookingVia;
use App\Models\Apartment;

class MasterData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		$banks = [
 			['name' => 'BRI', 'bank_code' => '002'],
 			['name' => 'BNI', 'bank_code' => '009'],
 			['name' => 'BCA', 'bank_code' => '014'],
 			['name' => 'Mandiri', 'bank_code' => '008'],
 			['name' => 'Mega', 'bank_code' => '426'],
 			['name' => 'BTN', 'bank_code' => '200'],
 			['name' => 'Muamalat', 'bank_code' => '147'],
 			['name' => 'Bukopin', 'bank_code' => '441'],
 			['name' => 'Danamond', 'bank_code' => '011']
 		];
 		foreach ($banks as $bank) {
 			Bank::create($bank);
 		}

    	$booking_vias = ["Offline", "Air Bnb"];
 		foreach ($booking_vias as $booking_via) {
 			BookingVia::create(['name' => $booking_via]);
 		}

    	$apartments = [
        	["name" => "Newton Hybird Residence" , "address" => "Buah Batu"],
        	["name" => "The Jardin" , "address" => "Cihampelas"],
        	["name" => "Gateway Ahmad Yani" , "address" => "Cicadas"],
        	["name" => "Metro The Suit" , "address" => "Soekarno Hatta"]
    	];
 		foreach ($apartments as $apartment) {
 			Apartment::create($apartment);
 		} 				
    }
}
