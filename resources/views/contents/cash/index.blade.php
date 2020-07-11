@extends('layouts.master')
@section('site_title','Cashs')
@section('cashs','active')
@section('content_title','Cashs')



@section('button_header')

<button class="btn btn-info" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	<i class="fa fa-arrows mr-1"></i> Actions
</button>
<div class="dropdown-menu arrow">
  <a class="dropdown-item" data-toggle="modal" data-target="#modal1">
    <i class="fa fa-pencil-square-o mr-1"></i> Create new cash
  </a>  
  <a class="dropdown-item" data-toggle="modal" data-target="#modal2">
    <i class="fa fa-share-square-o mr-1"></i> Make mutation 
  </a>
</div> 

@endsection



@section('content')

<div class="row op-0 animated">
	<div class="col-md-8">
		<div class="card text-white bg-success">
			<div class="card-header">
				<h4 class="card-title text-white">Cash Persentation</h4>
			</div>
			<div class="card-content collapse show">
				<div class="card-body" id="chart">
					<v-chart :options="polar"/>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Cash Summary</h4>
			</div>
			<div class="card-content" id="cash_list">
				<div class="card-body">
					<ul class="list-group mb-3 card">
						<li v-for="(item, index) in cashes" class="list-group-item">
							<div class="d-flex justify-content-between lh-condensed">
								<div>
									<h6 class="my-0">@{{ item.name }}</h6>
									<small class="text-muted">@{{ item.updated_at }}</small>
								</div>
								<span class="text-muted">@{{ item.balance.toLocaleString('tr-TR', {style: 'currency', currency: 'IDR'}) }}</span>									
							</div>	
							<div class="pull-right">
								<div class="btn-group" role="group" aria-label="First Group">
								  <button type="button" @click="addBalance(index)" title="Add Balance" class="btn btn-sm btn-outline-info"><i class="fa fa-money"></i></button>
								  <button type="button" @click="removeCash(index)" title="Remove Cash" class="btn btn-sm btn-outline-danger"><i class="fa fa-times"></i></button>
								</div>
							</div>											
						</li>
						<li class="list-group-item d-flex justify-content-between lh-condensed">
							<div>
								<h6 class="my-0"><strong>Balance Total</strong></h6>
								<small class="text-muted"></small>
							</div>
							<span class="text-muted"><strong>@{{ total_balance.toLocaleString('tr-TR', {style: 'currency', currency: 'IDR'}) }}</strong></span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Cash Mutation List</h4>
			</div>
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="table-responsive table-data">
						<table class="table table-bordered table-hover" id="datatables">
							<thead>
								<tr class="info">
	                                <td>Mutation Date</td>
	                                <td>Cash</td>
	                                <td>Mutation Fund</td>
	                                <td>Type</td>
	                                <td>Description</td>
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

<!-- Modal Create New Cash -->
<div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog"  aria-hidden="true">
	@include('contents.cash.index_create_cash')
</div>

<!-- Modal Create Mutation -->
<div class="modal fade text-left" id="modal2" tabindex="-1" role="dialog"  aria-hidden="true">
	@include('contents.cash.index_create_mutation')
</div>

<!-- Modal Add Balance -->
<div class="modal fade text-left" id="modal3" tabindex="-1" role="dialog"  aria-hidden="true">
	@include('contents.cash.index_add_balance')
</div>

<div class="rm" style="display: none;">
	<p id="url-api-cashes">{{ route('api.cashes.index') }}</p>
	<p id="url-api-cashes-store">{{ route('api.cashes.store') }}</p>
	<p id="url-api-cashes-update">{{ route('api.cashes.update', '0') }}</p>
	<p id="url-api-cashes-destroy">{{ route('api.cashes.destroy', '0') }}</p>
	<p id="url-api-cash-mutations">{{ route('api.cash.mutations') }}</p>
	<p id="url-api-cash-mutation-store">{{ route('api.cash.mutation.store') }}</p>
</div>

@endsection



@section('pageJs')

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/cash/index.js?').uniqid() }}"></script>

@endsection
