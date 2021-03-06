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
// Approvel approval expenditure
Route::post('expenditures/approve', 'ExpenditureController@approve')->name('api.expenditures.approve');
// Pay Approval (Billing) Expenditure
Route::post('expenditures/pay', 'ExpenditureController@pay')->name('api.expenditures.pay');

/*
|--------------------------------------------------------------------------
| Unit Utility for Create Booking Data
|--------------------------------------------------------------------------
*/

// Availabe Unit List (By Apartment Id)
Route::post('unit/availability', 'UnitController@available_unit')->name('api.unit.availability');
// Unit Price Mods
Route::post('unit/mod_prices', 'UnitController@prices_mod')->name('api.unit.mod_prices');

/*
|--------------------------------------------------------------------------
| Booking and Reservation Routes
|--------------------------------------------------------------------------
*/

// Booking Payment Info
Route::get('booking/payment/{id}', 'BookingController@payment_info')->name('api.booking.payment');
// Make Booking Payment
Route::post('booking/payment', 'BookingController@store_payment')->name('api.booking.payment.store');
// Setllement Deposit
Route::post('booking/settlementDeposit', 'BookingController@settlementDeposit')
		->name('api.booking.settlement.deposit');
// Setllement Deposit
Route::post('booking/settlementDp', 'BookingController@settlementDp')
		->name('api.booking.settlement.dp');		
// Confirm Booking
Route::post('booking/confirm', 'BookingController@confirm')->name('api.booking.confirm');
// Basic Booking Resource
Route::resource('booking', 'BookingController', ['as' => 'api']);
// Confirmed reservation
Route::get('reservation/confirmed', 'ReservationController@confirmed')->name('api.reservation.confirmed');
// Canceled reservation
Route::get('reservation/canceled', 'ReservationController@canceled')->name('api.reservation.canceled');
// Settlement DP
Route::post('reservation/settlement', 'ReservationController@settlement')->name('api.reservation.settlement');
// Delete Canceled Reservation
Route::post('reservation/destroy', 'ReservationController@destroy')->name('api.reservation.destroy');
// Owner Reservation Report
Route::get('reservations/report/{type}', 'ReservationController@report')->name('api.reservation.report');
// Reservation / Booking Invoice
Route::get('reservation/invoice/{id}', 'ReservationController@invoice')->name('api.reservation.invoice');

/*
|--------------------------------------------------------------------------
| Owner Payment Routes
|--------------------------------------------------------------------------
*/

// List history, paid, and unpaid owner payment
Route::get('payment', 'PaymentController@index')->name('api.payment');
// API Detail Data on Invoice Page
Route::get('payment/invoice/{id}', 'PaymentController@invoice')->name('api.payment.invoice');
// API Send Owner Payment
Route::post('payment/send', 'PaymentController@send')->name('api.payment.send');
// API Pay Owne Payment
Route::post('payment/pay', 'PaymentController@pay')->name('api.payment.pay');
// API Confirm Payment by Owner
Route::post('payment/confirm', 'PaymentController@confirm')->name('api.payment.confirm');
// API Refuce / Reject Payment by Owner
Route::post('payment/reject', 'PaymentController@reject')->name('api.payment.reject');
// API Cancel / Delete
Route::post('payment/destroy', 'PaymentController@destroy')->name('api.payment.destroy');
// Payment Report For Owner
Route::get('payment/report', 'PaymentController@report')->name('api.payment.report');
// Paid Owner Payment
Route::get('payment/paid', 'PaymentController@owner_paid')->name('api.payment.paid');

/*
|--------------------------------------------------------------------------
| Dashboard Statistic Routes
|--------------------------------------------------------------------------
*/

// General Transactions
Route::get('transactions/{year}', 'DashboardController@transaction_statistic')->name('api.transactions.statistic');
// General Incomes
Route::get('incomes/{year}', 'DashboardController@income_statistic')->name('api.incomes.statistic');

// Owner Incomes
Route::get('incomes_report/{year}', 'DashboardController@owner_income_statistic')->name('api.incomes_report.statistic');
// Owner Outcomes
Route::get('outcomes_report/{year}', 'DashboardController@owner_outcome_statistic')->name('api.outcomes_report.statistic');
// Owner Reservations
Route::get('reservations_report/{year}', 'DashboardController@owner_reservation_statistic')->name('api.reservations_report.statistic');

// Expenditure Notification
Route::get('notification', 'NotificationController@index')->name('api.notification');