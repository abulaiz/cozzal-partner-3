@extends('layouts.master')
@section('site_title','Booking List')
@section('booking_list','active')
@section('content_title','Booking List')



@section('content')

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">List of Booking Data</h4>
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
  <div class="col-md-12 mt-4">
    <!-- Empty space -->
  </div>
</div>

<div class="modal fade text-left" id="payment" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.booking.index_payment')
</div>   

<div class="rm" style="display: none;">
  <p id="api-booking-index">{{ route('api.booking.index') }}</p>
  <p id="api-payment-info">{{ route('api.booking.payment', 0) }}</p>
  <p id="api-payment-store">{{ route('api.booking.payment.store') }}</p>
  <p id="api-payment-settlement">{{ route('api.booking.settlement.deposit') }}</p>
  <p id="api-cashes">{{ route('api.cashes.index') }}</p>
  <p id="api-booking-destroy">{{ route('api.booking.destroy', 0) }}</p>
  <p id="api-booking-confirm">{{ route('api.booking.confirm') }}</p>
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
  .slide-enter-active {
    transition-duration: 0.5s;
    transition-property: height, opacity, transform;
    transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
    overflow: hidden;
  }
  .slide-enter {
    opacity: 0;
    transform: translate(-2em, 0);
  }  
</style>

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/booking/index.js?').uniqid() }}"></script>

@endsection