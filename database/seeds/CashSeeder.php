<?php

use Illuminate\Database\Seeder;
use App\Models\Cash;

class CashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Cash();
        $data->name = "Danamon";
        $data->balance  = 100000;
        $data->save();
        $data->saveMutation(0, "2");

        $data2 = new Cash();
        $data2->name = "BCA";
        $data2->balance  = 100000;
        $data2->save();
        $data2->saveMutation(0, "2");        
    }
}
