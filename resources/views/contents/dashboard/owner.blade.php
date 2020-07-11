@extends('layouts.master')
@section('site_title','Dashboard')
@section('dashboard','active')
@section('content_title','Dashboard')

@section('button_header')

@endsection

@section('content')

<div class="row op-0 animated" id="content">
	<div class="col-md-12 mt-1">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title col">Statistic in @{{ year }}</h4>
				<div class="col-md-6 mt-1">
					<div class="btn-group" role="group" aria-label="Basic example">
                      	<button type="button" 
	                    	@click="setStatisticMode('income')"
	                    	:class="`btn btn-sm btn${statistic_mode=='income'?'':'-outline'}-info`"
	                    	>Incomes
                      	</button>
                      	<button type="button" 
							@click="setStatisticMode('outcome')"
							:class="`btn btn-sm btn${statistic_mode=='outcome'?'':'-outline'}-info`"
							>Outcomes
                      	</button>
                      	<button type="button" 
	                      	@click="setStatisticMode('reservation')"
	                      	:class="`btn btn-sm btn${statistic_mode=='reservation'?'':'-outline'}-info`"
	                      	>Reservations
                      	</button>
                    </div>					
				</div>
				<div class="heading-elements">
	                <div class="dropdown">
	                  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                    Select Years
	                  </button>
	                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	                    <div class="dropdown-item">
	                    	<date-picker type="year" format="YYYY" value-type="YYYY" v-model="year"></date-picker>
	                    </div>
	                  </div>
	                </div>
              	</div>				
			</div>
			<div class="card-content collapse show">
				<div class="card-body row">
					<div :class="'col-md-12'+(first_load ? ' op-0' : '')" v-show="first_load || statistic_mode == 'income'">
					    <income-statistic v-if="statistic_collection.income != null" :chart-data="statistic_collection.income"></income-statistic>					
					</div>
					<div :class="'col-md-12'+(first_load ? ' op-0' : '')" v-show="first_load || statistic_mode == 'outcome'">
					    <income-statistic v-if="statistic_collection.outcome != null" :chart-data="statistic_collection.outcome"></income-statistic>					
					</div>
					<div :class="'col-md-12'+(first_load ? ' op-0' : '')" v-show="first_load || statistic_mode == 'reservation'">
					    <transaction-statistic v-if="statistic_collection.reservation != null" :chart-data="statistic_collection.reservation"></transaction-statistic>					
					</div>															
				</div>
			</div>
		</div>
	</div>	
</div>


<div class="rm" style="display: none;">
	<p id="api-incomes">{{ route('api.incomes_report.statistic', 0) }}</p>
	<p id="api-outcomes">{{ route('api.outcomes_report.statistic', 0) }}</p>
	<p id="api-reservations">{{ route('api.reservations_report.statistic', 0) }}</p>
</div>

@endsection

@section('pageJs')

<script type="text/javascript" src="{{ URL::asset('js/view/dashboard/owner.js?').uniqid() }}"></script>


@endsection
