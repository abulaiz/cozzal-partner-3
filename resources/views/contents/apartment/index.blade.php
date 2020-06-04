@extends('layouts.master')
@section('site_title','Apartments')
@section('apartments','active')
@section('content_title','Apartments')



@section('button_header')

<button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal1">
	<i class="fa fa-plus mr-1"></i>Add Apartment
</button>

@endsection



@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">List of Apartment</h4>
			</div>
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="table-responsive table-data">
						<table class="table table-bordered table-hover" id="datatables">
							<thead>
								<tr class="info">
									<td>Name</td>
									<td>Address</td>
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
  @include('contents.apartment.index_add_modals')
</div>

<div class="modal fade text-left" id="modal2" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.apartment.index_edit_modals')
</div>


<div class="rm" style="display: none;">
	<p id="url-api-apartments">{{ route('api.apartments.index') }}</p>
	<p id="url-api-apartments-store">{{ route('api.apartments.store') }}</p>
	<p id="url-api-apartments-update">{{ route('api.apartments.update', '0') }}</p>
	<p id="url-api-apartments-destroy">{{ route('api.apartments.destroy', '0') }}</p>
</div>

@endsection



@section('pageJs')

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/apartment/index.js?').uniqid() }}"></script>

@endsection
