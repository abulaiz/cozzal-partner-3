@extends('layouts.master')
@section('site_title','Invoice Payment')
@section('content_title','Invoice Payment')


@section('content')

<div class="row op-0" id="content">
	<transition-group type="transition" name="fade">
		<div class="col-md-12" v-for="(item, i) in payments" :key="i">
			<transition name="fade">
		        <div class="card" v-if="!item.onsubmit">          
					<div class="card-body">

						<!-- Invoice Info -->
			            <div class="row">
			              <div class="col-md-6 col-sm-12 text-center text-md-left">
			                <div class="media">
			                  <img src="{{URL::asset('fav.png')}}" style=" width: 100px;" alt="company logo" class=""/>
			                  <div class="media-body">
			                    <ul class="ml-2 px-0 list-unstyled">
			                      <li class="text-bold-800">Cozzal</li>
			                      <li>Gateway Apartemen, Tower Shappire A - Lantai G - A 10, Jl. Jend. A. Yani no. 669, Bandung.</li>
			                      <li>022-20546654 / 081809824448</li>
			                    </ul>
			                  </div>
			                </div>
			              </div>
			              <div class="col-md-6 col-sm-12 text-center text-md-right">
			                <h2>INVOICE</h2>

			         		<!-- If has generated as owne payment -->
			                <p v-if="item.has_arranged" class="pb-3"># @{{ item.receipt_number }}</p>
			                
			                <ul class="px-0 list-unstyled">
			                  <li></li>
			                </ul>
			              </div>
			            </div>
			            <!-- Owner Info -->
			            <div class="row pt-2">
			              <div class="col-sm-12 text-center text-md-left">
			                <p class="text-muted">Owner :</p>
			              </div>
			              <div class="col-md-6 col-sm-12 text-center text-md-left">
			                <ul class="px-0 list-unstyled">
			                  <li class="text-bold-800"></li>
			                  <li>@{{ item.owner.name }}</li>
			                  <li>@{{ item.owner.email }}</li>
			                </ul>
			              </div>
			              <div class="col-md-6 col-sm-12 text-center text-md-right">
			                <p><span class="text-muted">Invoice Date :</span> @{{ item.date }}</p>
			              </div>
			            </div>

			            <!-- Invoice Details -->
			            <div class="row pt-2">
			                <div class="table-responsive col-sm-12">
			                  <table class="table">
			                    <thead>
			                      <tr>
			                        <th colspan="6"><center>Unit Expenditures</center></th>
			                      </tr>
			                      <tr>
			                        <th>#</th>
			                        <th>Date</th>
			                        <th>Note</th>
			                        <th>Price</th>
			                        <th>Qty</th>
			                        <th class="text-right">Total</th>
			                      </tr>
			                    </thead>
			                    <tbody>
			                       <tr v-for="(data, index) in item.expenditures">
			                        <td>@{{ index+1 }}</td>
			                        <td>@{{ data.created_at.substr(0, 10) }}</td>
			                        <td>@{{ data.description }}</td>
			                        <td>@{{ data.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}</td>
			                        <td>@{{ data.qty }}</td>
			                        <td class="text-right">@{{ (data.qty*data.price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}</td>
			                      </tr>  			
			                      <tr>
			                        <td colspan="5"><strong>Total Expenditures</strong></td>
			                        <td class="text-right font-weight-bold">@{{ item.expenditure_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}</td>
			                      </tr>
			                    </tbody>
			                  </table>
			                </div>

			                <div class="table-responsive col-sm-12">
			                  <table class="table">
			                    <thead>
			                      <tr>
			                        <th colspan="6"><center>Unit Revenue</center></th>
			                      </tr>
			                      <tr>
			                        <th>#</th>
			                        <th>Check In/Check Out</th>
			                        <th>Unit</th>
			                        <th>Apartment</th>
			                        <th class="text-right">Total Price</th>
			                      </tr>
			                    </thead>
			                    <tbody>
			                      <tr v-for="(data, index) in item.reservations">
			                        <td>@{{ index+1 }}</td>
			                        <td>@{{ data.check_in.substr(0, 10) }} / @{{ data.check_out.substr(0, 10) }}</td>
			                        <td>@{{ data.unit_number }}</td>
			                        <td>@{{ data.unit.apartment.name }}</td>
			                        <td class="text-right">@{{ JSON.parse(data.owner_rent_prices).TP.replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}</td>
			                      </tr>
			                      <tr>
			                        <td colspan="4"><strong>Total Revenue</strong></td>
			                        <td class="text-right font-weight-bold">@{{ toIDR(item.reservation_total) }}</td>
			                      </tr>
			                    </tbody>
			                  </table>
			                </div>

			              	<!-- Earnings Section -->
				            <div class="col-md-12 row">
				                <div class="col-md-6" v-if="!item.is_paid">
				                	@if(!Auth::user()->hasRole('owner'))
				                	<p class="lead" v-if="!item.has_arranged || (item.has_arranged && item.is_accepted)">Payment Resource:</p>
				                	<div class="row" v-if="!item.has_arranged || (item.has_arranged && item.is_accepted)">
					                    <label class="col-md-12">Cash Name</label>
					                    <div class="col-md-11">
						                    <dynamic-select 
						                    :options="option.cashes"
						                    option-value="id"
						                    option-text="name"
						                    placeholder="Type to search"
						                    v-model="payments[i].cash" />                     	
					                    </div>                  
					                    <span v-if="!item.is_paid" class="col text-info">Cash required if want to make payment</span>

					                    <div class="col-md-11 mt-1" v-if="!item.has_arranged">
						                	<label>Earning Paid</label>
						                	<cleave class="form-control" v-model="payments[i].input_earning" 
			                  				:options="cleave"></cleave>	                    	
					                    </div>

					                    <div class="col-md-11 mt-1" v-if="payments[i].input_earning != item.earning">
						                	<label>Description</label>
						                	<textarea class="form-control" v-model="payments[i].input_description" placeholder="Override earning reason"></textarea>	                    	
					                    </div>
				                  	</div>
				                	@endif
				                </div>
				                <div v-if="item.is_paid" class="col-md-6"></div>
				                <div class="col-md-6">
				                  <p class="lead">Earnings</p>
				                  <div class="table-responsive">
				                    <table class="table">
				                      <tbody>
				                        <tr>
				                          <td class="text-bold-800">Total Revenue</td>
				                          <td class="green text-right">(+) @{{ toIDR(item.reservation_total) }}</td>
				                        </tr>
				                        <tr>
				                          <td class="text-bold-800">Total Expenditures</td>
				                          <td class="pink text-right">(-) @{{ toIDR(item.expenditure_total) }}</td>
				                        </tr>
				                        <tr v-if="item.paid_earning != item.earning && item.has_arranged">
				                          <td class="text-bold-800">@{{ item.description }}</td>
				                          <td :class="'text-bold-800 text-right ' + (item.additional_earning >= 0 ? 'green' : 'pink') ">
				                          (@{{ item.additional_earning >= 0 ? '+' : '-' }}) 
				                          	@{{ item.additional_earning >= 0 ? toIDR(item.additional_earning) : toIDR(-1*item.additional_earning) }}</td>
				                        </tr>                        
										<tr>
											<td class="text-bold-800">Earnings</td>
											<td class="text-bold-800 text-right font-weight-bold">@{{ (item.earning!=item.paid_earning && item.paid_earning!=null ) ? toIDR(item.paid_earning) : toIDR(item.earning) }}</td>
										</tr>                  
				                      </tbody>
				                    </table>
				                  </div>
				                </div>
				            </div>
			            </div>

			            <div id="invoice-footer" :class="(item.role == 'owner' && (item.is_accepted || item.is_rejected) ) ? 'hidden rm' : ''">
			              <div class="row mt-2">
			                <div class="col-md-8 col-sm-12">
			                </div>
			                <div class="col-md-4 col-sm-12 text-center">
			                  
			                  <button type="button" class="pull-right btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-target mr-1"></i>Action</button>
			                  <div class="dropdown-menu" x-placement="bottom-start">
			                    
			                    <a v-if="item.has_arranged && !item.is_accepted && !item.is_rejected && item.role == 'owner'" 
			                    @click="reject(i)"
			                    class="dropdown-item" 
			                    href="javascript:void(0)">
			                    <i class="fa fa-times mr-1">
			                    </i>Reject</a>

			                    <a v-if="item.has_arranged && !item.is_accepted && !item.is_rejected && item.role == 'owner'" 
			                    @click="confirm(i)"
			                    class="dropdown-item" 
			                    href="javascript:void(0)">
			                    <i class="fa fa-check mr-1"></i>Confim</a>

			                    <a v-if="!item.is_paid && item.is_accepted && item.role == 'manager'" 
			                    @click="pay(i)"
			                    class="dropdown-item">
			                    <i class="fa fa-money mr-1"></i> Pay Payment</a>

			                    <a v-if="!item.has_arranged" 
			                    @click="send(i)" 
			                    class="dropdown-item">
			                    <i class="fa fa-paper-plane mr-1"></i> Send Invoice</a>     

			                    <a v-if="item.has_arranged && item.role != 'owner'" href="{{ route('payment.invoice.download', $id) }}" target="blank" class="dropdown-item"><i class="fa fa-print mr-1"></i> Download/Print Invoice</a>

			                  </div>
			                </div>
			              </div>
			            </div>
					</div>
		        </div>			
			</transition>
		</div>
	</transition-group>
</div>


<div class="rm" style="display: none;">
	<p id="url-index">{{ route('payment') }}</p>
	<p id="url-report">{{ route('payment_report') }}</p>
	<p id="api-invoice">{{ route('api.payment.invoice', $id) }}</p>
	<p id="api-cashes">{{ route('api.cashes.index') }}</p>
	<p id="api-send">{{ route('api.payment.send') }}</p>
	<p id="api-pay">{{ route('api.payment.pay') }}</p>
	<p id="api-confirm">{{ route('api.payment.confirm') }}</p>
	<p id="api-reject">{{ route('api.payment.reject') }}</p>
</div>

@endsection



@section('pageJs')

<style type="text/css">
	.vue-dynamic-select{
		padding: 0.6rem 1rem !important;
	}
	
	.vue-dynamic-select .result-list{
		left: 7px;
		top: 36px;
	}	
	
	.fade-enter-active,
	.fade-leave-active {
		transition-duration: 0.5s;
		transition-property: height, opacity, transform;
		transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
		overflow: hidden;
	}

	.fade-leave-active {
		opacity: 0;
		transform: translate(2em, 0);
	}

	.fade-enter {
		opacity: 0;
		transform: translate(-2em, 0);
	} 
</style>

<script type="text/javascript" src="{{ URL::asset('js/view/payment/invoice.js?').uniqid() }}"></script>

@endsection
