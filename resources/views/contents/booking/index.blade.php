@extends('layouts.master')
@section('site_title','Booking List')
@section('booking_list','active')
@section('content_title','Booking List')



@section('content')

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">List of Expenditure</h4>
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

<div class="rm" style="display: none;">
  <p id="api-booking-index">{{ route('api.booking.index') }}</p>
</div>

@endsection 



@section('pageJs')

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/booking/index.js?').uniqid() }}"></script>

@endsection