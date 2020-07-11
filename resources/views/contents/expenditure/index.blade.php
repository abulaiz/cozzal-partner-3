@extends('layouts.master')
@section('site_title','Expenditures')
@section('expenditure_list','active')
@section('content_title','Expenditures')



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
	                                <td>No</td>
	                                <td>Description</td>
	                                <td>Necessary</td>
	                                <td>Source Fund</td>
	                                <td>Price</td>
	                                <td>Qty</td>
	                                <td>Total</td>
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

<div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.bank.index_add_modals')
</div>

<div class="modal fade text-left" id="modal2" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.bank.index_edit_modals')
</div>


<div class="rm" style="display: none;">
	<p id="api-url-expenditures">{{ route('api.expenditures.index', '1') }}</p>
	<p id="api-destroy">{{ route('api.expenditures.destroy', '0') }}</p>
</div>

@endsection



@section('pageJs')

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/expenditure/index.js?').uniqid() }}"></script>

@endsection
