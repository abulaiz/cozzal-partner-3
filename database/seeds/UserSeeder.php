<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\User;
use App\Models\Owner;
use App\Models\Bank;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
            	'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            [
            	'name' => 'Superadmin',
                'email' => 'superadmin@gmail.com',
                'password' => 'superadmin123',
                'role' => 'superadmin'
            ],
            [
            	'name' => 'Manager',
                'email' => 'manager@gmail.com',
                'password' => 'manager123',
                'role' => 'manager'
            ], 
            [
            	'name' => 'Owner1',
                'email' => 'owner1@gmail.com',
                'password' => '1owner123',
                'role' => 'owner',
                'phone' => '089012412412',
                'gender' => 'Laki-laki',
                'account_number' => '1240987102142',
                'account_name' => 'Owner1',
                'bank_id' => Bank::inRandomOrder()->value('id')
            ],  
            [
            	'name' => 'Owner2',
                'email' => 'owner2@gmail.com',
                'password' => '2owner123',
                'role' => 'owner',
                'phone' => '089012412222',
                'gender' => 'Laki-laki',
                'account_number' => '1250987102142',
                'account_name' => 'Owner2',
                'bank_id' => Bank::inRandomOrder()->value('id')
            ],  
            [
            	'name' => 'Owner3',
                'email' => 'owner3@gmail.com',
                'password' => '3owner123',
                'role' => 'owner',
                'phone' => '083012412412',
                'gender' => 'Perempuan',
                'account_number' => '1210987102142',
                'account_name' => 'Owner3',
                'bank_id' => Bank::inRandomOrder()->value('id')
            ]                                                                     
        ];   

        foreach ($users as $user) {
            if(User::where('email', $user['email'])->exists())
                continue;

            $u = new User;
			$u->name = $user['name'];
			$u->email = $user['email'];
			$u->password = $user['password'];
            $u->save();
            $u->assignRole($user['role']);

            if( $user['role'] == 'owner' ){
            	Owner::create(
                     Arr::add(Arr::except($user, ['role', 'password']), 'id', $u->id)
                );
            }
        }     			        
    }
}
