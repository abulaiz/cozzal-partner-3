@extends('layouts.master')
@section('site_title','Owner Payment')
@section('payment','active')
@section('content_title','Owner Payment')



@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-content collapse show">
				<div class="card-body">
				  
					<div class="row" id="app">
						<div class="col-md-12">
							<dynamic-select 
							  :options="option.owners"
							  option-value="id"
							  option-text="name"
							  placeholder="Choose Owner First"
							  v-model="owner" /> 
						</div>
					</div>

					<div class="mt-2">
						<ul class="nav nav-tabs nav-underline nav-justified" id="nav-ul">
							<li class="nav-item">
								<a class="nav-link active" @click="show(0)" id="base-tab11" data-toggle="tab" aria-controls="tab11"
							href="#tab11" aria-expanded="false"><i class="fa fa-list-ul"></i>History</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" @click="show(1)" id="base-tab12" data-toggle="tab" aria-controls="tab12" href="#tab12"
							aria-expanded="false"><i class="fa fa-check-square-o"></i>Paid</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" @click="show(2)" id="base-tab13" data-toggle="tab" aria-controls="tab13" href="#tab13"
							aria-expanded="false"><i class="fa fa-wpforms"></i>Unpaid</a>
							</li>
						</ul>
						<div class="tab-content px-1 pt-1">
							<!-- History -->
							<div class="tab-pane active" id="tab11">
								<div class="table-responsive">
									<table class="table table-bordered table-hover" id="datatables1">
										<thead>
											<tr class="info">
												<td>Receipt Number</td>
												<td>Date</td>
												<td>Nominal</td>
												<td>Status</td>
												<td>Action</td>
											</tr>
										</thead>
										<tbody style="font-size:10pt;"></tbody>
									</table>
								</div>
							</div>
							<!-- Paid -->
							<div class="tab-pane" id="tab12">
								<div class="table-responsive">
									<table class="table table-bordered table-hover" id="datatables2">
										<thead>
											<tr class="info">
												<td>Type</td>
												<td>Receipt Number</td>
												<td>Owner</td>
												<td>Unit</td>
												<td>Check In/Out</td>
											</tr>
										</thead>
										<tbody style="font-size:10pt;"></tbody>
									</table>
								</div>
							</div>
							<!-- Unpaid -->
							<div class="tab-pane" id="tab13">
								<div class="table-responsive">
									<table class="table table-bordered table-hover" id="datatables3">
										<thead>
											<tr class="info">
												<td>
													<div class="row skin skin-flat">
														<div class="col-md-6 col-sm-12">
															<input type="checkbox" id="gck">
														</div> 
													</div>                                    
												</td>
												<td>Type</td>
												<td>Receipt Number</td>
												<td>Owner</td>
												<td>Unit</td>
												<td>Check In/Out</td>
											</tr>
										</thead>
										<tbody style="font-size:10pt;" id="tblUnpaid"></tbody>
									</table>
									<hr>
									<label id="selected-rec-caption"></label>
									<div class="pull-right">
										<button id="propose-payment" class="btn-lg dropdown-item btn btn-info text-white" disabled>
											<i class="fa fa-file-text-o mr-1"></i>Propose Payment
										</button>
									</div>
								</div>
							</div> 
						</div>                
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="rm" style="display: none;">
	<p id="api-index">{{ route('api.payment') }}</p>
	<p id="api-owners">{{ route('api.owners.index') }}</p>
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
</style>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/forms/checkboxes-radios.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/vendors/css/forms/icheck/icheck.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/vendors/css/forms/icheck/custom.css') }}">
<script src="{{ URL::asset('app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>

<script src="{{URL::asset('js/lib/checkboxTable.js?'.uniqid())}}" type="text/javascript"></script> 

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/payment/index.js?').uniqid() }}"></script>

@endsection
