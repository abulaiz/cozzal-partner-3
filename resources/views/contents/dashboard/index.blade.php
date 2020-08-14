@extends('layouts.master')
@section('site_title','Dashboard')
@section('dashboard','active')
@section('content_title','Dashboard')

@section('button_header')

@endsection

@section('content')

<div class="row op-0 animated" id="content">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Transaction in @{{ transaction_year }}</h4>
				<div class="heading-elements">
	                <div class="dropdown">
	                  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                    Select Years
	                  </button>
	                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	                    <div class="dropdown-item">
	                    	<date-picker type="year" format="YYYY" value-type="YYYY" v-model="transaction_year"></date-picker>
	                    </div>
	                  </div>
	                </div>
              	</div>				
			</div>
			<div class="card-content collapse show">
				<div class="card-body row">
					<div class="col-md-9 border-right-grey border-right-lighten-2">
					    <transaction-statistic v-if="transaction_collection != null" :chart-data="transaction_collection"></transaction-statistic>					
					</div>
					<div class="col-md-3">
						<div class="list-group">
							<a href="javascript:void(0)" class="list-group-item list-group-item-action">
								<div class="media">
				                    <div class="media-body text-left">
				                      <h3 class="success">@{{ total_reservation }} Day</h3>
				                      <span>Total Reservations</span>
				                    </div>
				                    <div class="media-right media-middle">
				                      <i class="fa fa-check-square-o success font-large-2 float-right"></i>
				                    </div>
				                </div>						
							</a>
							<a href="javascript:void(0)" class="list-group-item list-group-item-action">
								<div class="media">
				                    <div class="media-body text-left">
				                      <h3 class="info">@{{ total_booking }} Day</h3>
				                      <span>Total Bookings</span>
				                    </div>
				                    <div class="media-right media-middle">
				                      <i class="fa fa-pencil-square-o info font-large-2 float-right"></i>
				                    </div>
				                </div>					
							</a>
							<a href="javascript:void(0)" class="list-group-item list-group-item-action">
								<div class="media">
				                    <div class="media-body text-left">
				                      <h3 class="warning">@{{ total_cancellation }} Day</h3>
				                      <span>Total Cancellations</span>
				                    </div>
				                    <div class="media-right media-middle">
				                      <i class="fa fa-share-square-o warning font-large-2 float-right"></i>
				                    </div>
				                </div>	
							</a>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
	@hasrole('manager')
	<div class="col-md-12 mt-1">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Incomes in @{{ income_year }}</h4>
				<div class="heading-elements">
	                <div class="dropdown">
	                  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                    Select Years
	                  </button>
	                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	                    <div class="dropdown-item">
	                    	<date-picker type="year" format="YYYY" value-type="YYYY" v-model="income_year"></date-picker>
	                    </div>
	                  </div>
	                </div>
              	</div>				
			</div>
			<div class="card-content collapse show">
				<div class="card-body row">
					<div class="col-md-3 border-right-grey border-right-lighten-2 mb-2">
						<div class="list-group">
							<a href="javascript:void(0)" class="list-group-item list-group-item-action">
								<div class="media">
				                    <div class="media-right media-middle">
				                      <i class="fa fa-plus-square-o success font-large-2 float-right"></i>
				                    </div>									
				                    <div class="media-body text-right">
				                      <h3 class="success">@{{ total_income }}</h3>
				                      <span>Total Incomes</span>
				                    </div>
				                </div>						
							</a>
							<a href="javascript:void(0)" class="list-group-item list-group-item-action">
								<div class="media">
				                    <div class="media-right media-middle">
				                      <i class="fa fa-calculator info font-large-2 float-right"></i>
				                    </div>									
				                    <div class="media-body text-right">
				                      <h3 class="info">@{{ total_gross_profit }}</h3>
				                      <span>Total Gross Profits</span>
				                    </div>
				                </div>					
							</a>
						</div>								
					</div>					
					<div class="col-md-9">
					    <income-statistic v-if="income_collection != null" :chart-data="income_collection"></income-statistic>					
					</div>
				</div>
			</div>
		</div>
	</div>	
	@endhasrole
</div>


<div class="rm" style="display: none;">
	<p id="api-incomes">{{ route('api.incomes.statistic', 0) }}</p>
	<p id="api-transactions">{{ route('api.transactions.statistic', 0) }}</p>
</div>

@endsection

@section('pageJs')

<script type="text/javascript" src="{{ URL::asset('js/view/dashboard/index.js?').uniqid() }}"></script>


@endsection
