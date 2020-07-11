@extends('layouts.master')
@section('site_title','Paid Incomes')
@section('paid_payment','active')
@section('content_title','Paid Incomes')



@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="datatables">
							<thead>
								<tr class="info">
									<td>Payment Date</td>
									<td>Transaction</td>
									<td>Nominal</td>
									<td>Action</td>
								</tr>
							</thead>
							<tbody style="font-size:10pt;"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="rm" style="display: none;">
	<p id="api-index">{{ route('api.payment.paid') }}</p>
	<p id="url-invoice">{{ route('payment.invoice', 0) }}</p>
</div>

@endsection



@section('pageJs')

<script src="{{URL::asset('js/lib/SimpleEnc.js?'.uniqid())}}" type="text/javascript"></script> 

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/payment/paid.js?').uniqid() }}"></script>

@endsection
