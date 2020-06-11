<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Basic Resource Routes
|--------------------------------------------------------------------------
*/

Route::resource('banks', 'BankController', ['as' => 'api']);

Route::resource('booking_vias', 'BookingViaController', ['as' => 'api']);

Route::resource('apartments', 'ApartmentController', ['as' => 'api']);

Route::resource('units', 'UnitController', ['as' => 'api']);

Route::resource('owners', 'OwnerController', ['as' => 'api']);

Route::resource('tenants', 'TenantController', ['as' => 'api']);

/*
|--------------------------------------------------------------------------
| Calendar Event & Mod Price Routes (Used on Unit Calendar Page)
|--------------------------------------------------------------------------
*/

// Unit Calendar API
Route::get('calendar/{id}', 'UnitCalendarController@index')->name('api.calendar');

// Store calendar event (availability)
Route::post('calendar/availability/store', 'UnitCalendarController@store_unit_availability')
	  ->name('api.calendar.availability.store');
// Update calendar event (availability)
Route::post('calendar/availability/update', 'UnitCalendarController@update_unit_availability')
	  ->name('api.calendar.availability.update');	
// Delete calendar event (availability)
Route::post('calendar/availability/delete', 'UnitCalendarController@delete_unit_availability')
	  ->name('api.calendar.availability.delete');

// Store calendar event (price)
Route::post('calendar/price/store', 'UnitCalendarController@store_unit_price')
	  ->name('api.calendar.price.store');
// Update calendar event (price)
Route::post('calendar/price/update', 'UnitCalendarController@update_unit_price')
	  ->name('api.calendar.price.update');	
// Delete calendar event (price)
Route::post('calendar/price/delete', 'UnitCalendarController@delete_unit_price')
	  ->name('api.calendar.price.delete');		    

/*
|--------------------------------------------------------------------------
| Cash and Cash Mutation Routes (Used on Unit Cash Page)
|--------------------------------------------------------------------------
*/

// Basic resouces of cash
Route::resource('cashes', 'CashController', ['as' => 'api'])->except([
	'create', 'show', 'edit'
]);
// Cash Mutation List
Route::get('cash_mutations', 'CashController@mutations')->name('api.cash.mutations');
// Store Cash Mutation
Route::post('cash_mutation/store', 'CashController@store_mutation')->name('api.cash.mutation.store');

/*
|--------------------------------------------------------------------------
| Expenditure Routes
|--------------------------------------------------------------------------
*/

// Basic resouces of expenditure
Route::resource('expenditures', 'ExpenditureController', ['as' => 'api'])->except([
	'create', 'show', 'edit', 'index'
]);
// Custom Expenditure Index Routes
Route::get('expenditures/{type}', 'ExpenditureController@index')->name('api.expenditures.index');

/*
|--------------------------------------------------------------------------
| Unit Utility for Create Booking Data
|--------------------------------------------------------------------------
*/

// Availabe Unit List (By Apartment Id)
Route::post('unit/availability', 'UnitController@available_unit')->name('api.unit.availability');
// Unit Price Mods
Route::post('unit/mod_prices', 'UnitController@prices_mod')->name('api.unit.mod_prices');