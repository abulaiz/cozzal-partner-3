@extends('layouts.master')
@section('site_title','Reservation List')
@section('reservation_report','active')
@section('content_title','Reservation List')



@section('content')

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-content">
        <div class="card-body">
          <ul class="nav nav-tabs nav-underline nav-justified" id="nav-ul">
            <li class="nav-item">
              <a class="nav-link active" @click="show(0)" ref="tab1" id="base-tab11" data-toggle="tab" aria-controls="tab11"
            href="#tab11" aria-expanded="false"><i class="fa fa-check-square-o"></i>Confirmed</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" @click="show(1)" ref="tab2" id="base-tab12" data-toggle="tab" aria-controls="tab12" href="#tab12"
            aria-expanded="false"><i class="fa fa-pencil-square-o"></i>Booked</a>
            </li>
          </ul>
          <div class="tab-content px-1 pt-1">
            <div role="tabpanel" class="tab-pane active" id="tab11" aria-expanded="true" aria-labelledby="base-tab11">                  
              <div class="table-responsive table-data animated">
                <table class="table table-bordered table-hover" id="datatables1">
                  <thead>
                    <tr class="info">
                      <td>Tenant</td>
                      <td>Unit</td>
                      <td>Check In</td>
                      <td>Check Out</td>
                      <td>Income</td>
                      <td>Status</td>
                    </tr>
                  </thead>
                  <tbody style="font-size:10pt;">
                  </tbody>
                </table>
                <hr>                   
              </div>                                  
            </div>
            <div role="tabpanel" class="tab-pane" id="tab12" aria-labelledby="base-tab12">
              <div class="table-responsive table-data animated">
                <table class="table table-bordered table-hover" id="datatables2">
                  <thead>
                      <tr class="info">
                        <td>Tenant</td>
                        <td>Unit</td>
                        <td>Check In</td>
                        <td>Check Out</td>
                        <td>Income</td>
                      </tr>
                  </thead>
                  <tbody style="font-size:10pt;">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="rm" style="display: none;">
  <p id="api-booked">{{ route('api.reservation.report', '0') }}</p>
  <p id="api-confirmed">{{ route('api.reservation.report', '1') }}</p>
</div>
@endsection 



@section('pageJs')
<style type="text/css">
  .nav.nav-tabs.nav-underline .nav-item a.nav-link.active:focus, .nav.nav-tabs.nav-underline .nav-item a.nav-link.active:hover {
    color: #3BAFDA !important;
  }
  .vue-dynamic-select{
    padding: 0.6rem 1rem !important;
  }
  .vue-dynamic-select .result-list{
    left: 7px;
    top: 36px;
  } 
  .mx-datepicker{
    width: 100% !important;
  }  
  .mx-input{
    height: 40px !important;
  }  
</style>

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/reservation/report.js?').uniqid() }}"></script>


@endsection