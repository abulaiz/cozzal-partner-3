<?php

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Owner;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$owner_price = json_encode([
    		"WD" => 300000,
    		"WE" => 350000,
    		"WK" => 1400000,
    		"MN" => 5500000,
    	]);

    	$rent_price = json_encode([
    		"WD" => 350000,
    		"WE" => 400000,
    		"WK" => 1500000,
    		"MN" => 6000000,
    	]);

        $units = [
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "9BJ" 
        	] ,
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "12BD" 
        	] ,
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "32BF" 
        	] ,
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "8BG" 
        	] ,
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "7BG" 
        	] ,
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "18BF" 
        	] ,
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "16BE" 
        	] ,
        	[ 
        		"apartment_id" => 1, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "12BD" 
        	] ,
        	[ 
        		"apartment_id" => 2, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "A123" 
        	] ,
        	[ 
        		"apartment_id" => 2, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "A133" 
        	] ,
        	[ 
        		"apartment_id" => 2, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "B305" 
        	] ,
        	[ 
        		"apartment_id" => 3, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "SA-6-5" 
        	] ,
        	[ 
        		"apartment_id" => 3, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "EC-6-21" 
        	] ,
        	[ 
        		"apartment_id" => 3, 
        		"owner_id" => Owner::inRandomOrder()->value('id'),
        		"rent_price" => $rent_price,
        		"owner_rent_price" => $owner_price,
        		"charge" => 50000,
        		"unit_number" => "SA-7-9" 
        	]
        ];

        foreach ($units as $key => $unit) {
        	Unit::create($unit);
        }
    }
}
