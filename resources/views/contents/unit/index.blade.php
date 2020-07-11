@extends('layouts.master')
@section('site_title','Units')
@section('units','active')
@section('content_title','Units')



@section('content')

<div class="row" id="content">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">List of Unit</h4>
			</div>
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="table-responsive table-data">
						<table class="table table-bordered table-hover" id="datatables">
							<thead>
								<tr class="info">
									<td>Unit Number</td>
									<td>Apartment</td>
									<td>Owner</td>
									<td>Rent Price</td>
									<td>Owner Price</td>
									<td>Charge</td>
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

<div class="rm" style="display: none;">
	<p id="url-api-units">{{ route('api.units.index') }}</p>
	<p id="url-api-units-destroy">{{ route('api.units.destroy', '0') }}</p>
</div>

@endsection



@section('pageJs')

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/vendors/css/ui/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/ui/jqueryui.css') }}">
<script src="{{ URL::asset('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/unit/index.js?').uniqid() }}"></script>

@endsection
