@extends('layouts.master')
@section('site_title','Unit Calendar')
@section('units','active')
@section('content_title','Unit Calendar')



@section('button_header')

@if(Auth::user()->hasRole('owner'))
<button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal1">
  <i class="fa fa-calendar mr-1"></i> Unit Avalibility
</button>
@else
  <button class="btn btn-info" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-plus mr-1"></i>Add Event
  </button>
  <div class="dropdown-menu arrow">
    <a class="dropdown-item" data-toggle="modal" data-target="#modal1">
      <i class="fa fa-calendar mr-1"></i> Unit Avalibility
    </a>  
    <a class="dropdown-item" data-toggle="modal" data-target="#modal3">
      <i class="fa fa-pencil-square-o mr-1"></i> Mod Price
    </a>
  </div> 
@endif

@endsection


@section('breadcumb_nav')

<li class="breadcrumb-item"><a href="{{URL::to('dashboard')}}">Home</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('units') }}">Unit</a>
</li>
<li class="breadcrumb-item active">Calendar</li>

@endsection


@section('content')

<div class="row">  
	<div class="col-12">
      <div class="card">
        <div class="card-content collapse show">
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-12" id="app">
                <dynamic-select 
                    :options="units"
                    option-value="id"
                    option-text="name"
                    placeholder="Type to search"
                    v-model="unit" />        
              </div>              
            </div>
            <div id='unit-calendar' class="animated" style="display: none; opacity: 0;"></div>
            <div class="loader-wrapper" id="waiting-calendar">
              <div class="loader-container">
                <div class="ball-beat loader-primary">
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
              </div>
            </div>                
          </div>
        </div>
      </div>
	</div>
</div>

<div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.unit.calendar_add_availability')
</div>


<div class="modal fade text-left" id="modal2" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.unit.calendar_edit_availability')
</div>

<div class="modal fade text-left" id="modal3" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.unit.calendar_add_price')
</div>

<div class="modal fade text-left" id="modal4" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.unit.calendar_edit_price')
</div>

<div class="rm" style="display: none;">
  <p id="url-api-availability-delete">{{ route('api.calendar.availability.delete') }}</p>
  <p id="url-api-availability-store">{{ route('api.calendar.availability.store') }}</p>
  <p id="url-api-availability-update">{{ route('api.calendar.availability.update') }}</p>
  <p id="url-api-price-delete">{{ route('api.calendar.price.delete') }}</p>
  <p id="url-api-price-store">{{ route('api.calendar.price.store') }}</p>
  <p id="url-api-price-update">{{ route('api.calendar.price.update') }}</p>
  <p id="api-units">{{ route('api.units.index') }}</p>
  <p id="url-api-calendar">{{ route('api.calendar', $id) }}</p>
  <p id="parse-unit-id">{{ $id }}</p>
  <p id="url-calendar">{{ route('unit.calendar', 0) }}</p>
</div>

@endsection



@section('pageJs')

<style type="text/css">
  .mx-datepicker{
    width: 100% !important;
  }
</style>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/loaders/loaders.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/core/colors/palette-loader.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/calendars/fullcalendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/vendors/css/calendars/fullcalendar.min.css') }}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

<script src="{{ URL::asset('app-assets/vendors/js/extensions/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('app-assets/vendors/js/extensions/fullcalendar.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('app-assets/js/scripts/customizer.js') }}" type="text/javascript"></script>



<script type="text/javascript" src="{{ URL::asset('js/view/unit/calendar.js?').uniqid() }}"></script>

@endsection
