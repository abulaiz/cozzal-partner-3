@extends('layouts.master')
@section('site_title','Approval Expenditure')
@section('approve_expenditure','active')
@section('content_title','Approval Expenditure')



@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">List of Expenditure</h4>
			</div>
			<div class="card-content collapse show">
            <div class="card-body">
              <ul class="nav nav-tabs nav-top-border no-hover-bg">
                <li class="nav-item">
                  <a class="nav-link active" id="base-tab11" data-toggle="tab" aria-controls="tab11"
                  href="#tab11" aria-expanded="false">Billing</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="base-tab12" data-toggle="tab" aria-controls="tab12" href="#tab12"
                  aria-expanded="false">Need to approved<span class="dbadge badge badge-info float-right mr-2" id="apprv_tab_badge"></span></a>
                </li>
              </ul>
              <div class="tab-content px-1 pt-1">
                <div role="tabpanel" class="tab-pane active" id="tab11" aria-expanded="true" aria-labelledby="base-tab11">                  
                  <div class="table-responsive table-data animated" id="res1">
                    <table class="table table-bordered table-hover" id="datatables-1">
                      <thead>
                        <tr class="info">
                          <td>No</td>
                          <td>Description</td>
                          <td>Necessary</td>
                          <td>Price</td>
                          <td>Qty</td>
                          <td>Total</td>
                          <td>Due At</td>
                          <td>Action</td>
                        </tr>
                      </thead>
                      <tbody style="font-size:10pt;">
                      </tbody>
                    </table>
                    <hr>                   
                  </div> 
                  <div class="loader-wrapper" id="table-loader">
                    <div class="loader-container">
                      <div class="ball-beat loader-info">
                        <div></div>
                        <div></div>
                        <div></div>
                      </div>
                    </div>
                  </div>                                  
                </div>
                <div role="tabpanel" class="tab-pane" id="tab12" aria-labelledby="base-tab12">
                  <div class="table-responsive table-data animated" id="res2">
                    <table class="table table-bordered table-hover" id="datatables-2">
                      <thead>
                          <tr class="info">
                            <td>No</td>
                            <td>Description</td>
                            <td>Necessary</td>
                            <td>Price</td>
                            <td>Qty</td>
                            <td>Total</td>
                            <td>Action</td>
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
	<p id="api-url-expenditures">{{ route('api.expenditures.index', '0') }}</p>
</div>

@endsection



@section('pageJs')

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

{{-- <script type="text/javascript" src="{{ URL::asset('js/view/expenditure/approval.js?').uniqid() }}"></script> --}}

@endsection
