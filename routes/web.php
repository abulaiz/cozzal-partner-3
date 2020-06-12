<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Default auth routes
Auth::routes();

// Root URL (Login Page)
Route::get('/', function () { return view('auth.login2'); })->middleware('guest');

// This route need authenticated first
Route::group(['middleware' => ['auth']], function () {

	// Dashbord Page
	Route::get('/dashboard', function () { return view('contents.dashboard.index'); })->name('dashboard');

	// Bank (Master Data) Page
	Route::get('/banks', function () { return view('contents.bank.index'); })->name('banks');	

	// Booking Via (Master Data) Page
	Route::get('/booking_vias', function () { return view('contents.booking_via.index'); })->name('booking_vias');	

	// Apartment (Master Data) Page
	Route::get('/apartments', function () { return view('contents.apartment.index'); })->name('apartments');

	// Unit Page
	Route::get('/units', function () { return view('contents.unit.index'); })->name('units');
	// Manage Unit Page
	Route::get('/unit/manage/{id}', function ($id) { 
		return view('contents.unit.manage', compact('id')); 
	})->name('unit.manage');	
	// Unit Calendar Page
	Route::get('/units/calendar/{id}', function($id){ 
		return view('contents.unit.calendar', compact('id')); 
	})->name('unit.calendar');

	// Cash Page
	Route::get('/cashs', function(){ return view('contents.cash.index'); })->name('cashs');

	// Create Expenditure Page
	Route::get('/expenditure/create', function(){ return view('contents.expenditure.create'); })->name('expenditure.create');
	// Direct Expenditure List
	Route::get('/expenditure', function(){ return view('contents.expenditure.index'); })->name('expenditure');
	// Approval Expenditure List
	Route::get('/expenditure/approval', function(){ return view('contents.expenditure.approval'); })->name('expenditure.approval');

	// Booking List Page
	Route::get('/booking', function(){ return view('contents.booking.index'); })->name('booking');
	// Create Booking Page
	Route::get('/booking/create', function(){ return view('contents.booking.create'); })->name('booking.create');
});