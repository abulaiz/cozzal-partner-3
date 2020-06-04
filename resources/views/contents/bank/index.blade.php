@extends('layouts.master')
@section('site_title','Banks')
@section('banks','active')
@section('content_title','Banks')



@section('button_header')

<button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal1">
	<i class="fa fa-plus mr-1"></i>Add Bank
</button>

@endsection



@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">List of Bank</h4>
			</div>
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="table-responsive table-data">
						<table class="table table-bordered table-hover" id="datatables">
							<thead>
								<tr class="info">
									<td>Name</td>
									<td>Code</td>
									<td>Actions</td>
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
	<p id="url-api-banks">{{ route('api.banks.index') }}</p>
	<p id="url-api-banks-store">{{ route('api.banks.store') }}</p>
	<p id="url-api-banks-update">{{ route('api.banks.update', '0') }}</p>
	<p id="url-api-banks-destroy">{{ route('api.banks.destroy', '0') }}</p>
</div>

@endsection



@section('pageJs')

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/bank/index.js?').uniqid() }}"></script>

@endsection
