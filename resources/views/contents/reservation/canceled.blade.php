@extends('layouts.master')
@section('site_title','Canceled Reservation')
@section('canceled_reservation','active')
@section('content_title','Canceled List')



@section('content')

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">List of Canceled Reservation</h4>
      </div>
      <div class="card-content collapse show">
        <div class="card-body">
          <div class="table-responsive table-data">
            <table class="table table-bordered table-hover" id="datatables">
              <thead>
                <tr class="info">
					<td>Receipt Number</td>
					<td>Tenant</td>
					<td>Check in</td>
					<td>Check out</td>
					<td>Unit</td>
					<td>DP</td>
					<td>Setlement</td>
					<td>Action</td>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="settlement" tabindex="-1" role="dialog"  aria-hidden="true">
	@include('contents.reservation.canceled_settlement')
</div> 

<div class="rm" style="display: none;">
  <p id="api-canceled-index">{{ route('api.reservation.canceled') }}</p>
  <p id="api-cashes">{{ route('api.cashes.index') }}</p>
  <p id="api-settlement">{{ route('api.reservation.settlement') }}</p>
  <p id="api-destroy">{{ route('api.reservation.destroy') }}</p>
</div>
@endsection 


@section('pageJs')
<style type="text/css">
  .vue-dynamic-select{
    padding: 0.6rem 1rem !important;
  }
  .vue-dynamic-select .result-list{
    left: 7px;
    top: 36px;
  }	
</style>

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/reservation/canceled.js?').uniqid() }}"></script>

@endsection