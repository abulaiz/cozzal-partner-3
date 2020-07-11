<div class="row op-0" id="preview">
	<div class="col-md-12">
        <div class="card" v-if="invoice != null">   

			<vue-html2pdf
			:show-layout="false"
			:enable-download="isMobile()"
			:preview-modal="!isMobile()"
			:paginate-elements-by-height="1400"
			filename="Anoenw"
			:pdf-quality="2"
			@progress="onProgress($event)"
			pdf-format="a4"
			pdf-orientation="portrait"
			ref="html2Pdf"
			>
			<section slot="pdf-content">
				<div class="card-body">
					<!-- Invoice Info -->
		            <div class="row pr-1">
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
		                <p># @{{ invoice.receipt_number }}</p>
		                <p><span class="text-muted">Invoice Date :</span>  @{{ invoice.created_at.substr(0, 10) }}</p>
		              </div>
		            </div>
		            <!-- Owner Info -->
		            <div class="row pt-2 pr-1">
		              <div class="col-md-6 col-sm-12 text-center text-md-left">
		                <ul class="px-0 list-unstyled">
		                  <li class="font-weight-bold">@{{ invoice.tenant.name }}</li>
		                  <li>@{{ invoice.tenant.address }}</li>
		                  <li>@{{ invoice.tenant.phone }}</li>
		                  <li>@{{ invoice.tenant.email }}</li>
		                </ul>
		              </div>
		            </div>

		            <!-- Invoice Details -->
		            <div class="row pt-2 pr-1">
		            	<div class="col-sm-12">
			                <div class="table-responsive">
			                  <table class="table">
			                    <tbody>
									<tr>
										<th colspan="2" class="text-center">Reservation</th>
									</tr>				                    	
			                    	<tr>
			                    		<td class="font-weight-bold">Check In</td>
			                    		<td>@{{ invoice.check_in.substr(0, 10) }}</td>
			                    	</tr>
			                    	<tr>
			                    		<td class="font-weight-bold">Check Out</td>
			                    		<td>@{{ invoice.check_out.substr(0, 10) }}</td>
			                    	</tr>	
			                    	<tr>
			                    		<td class="font-weight-bold">Unit</td>
			                    		<td>@{{ invoice.unit.name }}</td>
			                    	</tr>
			                    	<tr>
			                    		<td class="font-weight-bold">Number of Guest</td>
			                    		<td>@{{ invoice.guest }} Person</td>
			                    	</tr>
			                    	<tr>
			                    		<td class="font-weight-bold">Length of stay</td>
			                    		<td>
			                    			@{{ invoice.days.total }} Day (@{{ invoice.days.description }})</td>
			                    	</tr>				                    	
									<tr>
										<th colspan="2" class="text-center">Pricing</th>
									</tr>	
			                    	<tr v-if="invoice.days.is_single_day">
			                    		<td class="font-weight-bold">Price Per Night</td>
			                    		<td>@{{ _currencyFormat(invoice.prices[invoice.days.referenced_single_day]) }}</td>
			                    	</tr>
			                    	<tr v-if="!invoice.days.is_single_day && invoice.days.detail.wd != 0">
			                    		<td class="font-weight-bold">Price Per Night for Weekday</td>
			                    		<td>@{{ _currencyFormat(invoice.prices['WD']) }}</td>
			                    	</tr>
			                    	<tr v-if="!invoice.days.is_single_day && invoice.days.detail.we != 0">
			                    		<td class="font-weight-bold">Price Per Night for Weekend</td>
			                    		<td>@{{ _currencyFormat(invoice.prices['WE']) }}</td>
			                    	</tr>
			                    	<tr v-if="!invoice.days.is_single_day && invoice.days.detail.wk != 0">
			                    		<td class="font-weight-bold">Price Per Night for Weekly</td>
			                    		<td>@{{ _currencyFormat(invoice.prices['WK']) }}</td>
			                    	</tr>
			                    	<tr v-if="!invoice.days.is_single_day && invoice.days.detail.mn != 0">
			                    		<td class="font-weight-bold">Price Per Night for Monthly</td>
			                    		<td>@{{ _currencyFormat(invoice.prices['MN']) }}</td>
			                    	</tr>
			                    	<tr>
			                    		<td class="font-weight-bold">Charge</td>
			                    		<td>@{{ _currencyFormat(invoice.charge) }}</td>
			                    	</tr>	
			                    	<tr>
			                    		<td class="font-weight-bold">Discount</td>
			                    		<td>@{{ _currencyFormat(invoice.discount) }}</td>
			                    	</tr>	
			                    	<tr>
			                    		<td class="font-weight-bold">Total Price</td>
			                    		<td class="font-weight-bold">@{{ _currencyFormat(invoice.amount_bill) }}</td>
			                    	</tr>
			                    	<tr>
			                    		<td class="font-weight-bold">Payment</td>
			                    		<td class="font-weight-bold">@{{ _currencyFormat(invoice.paid) }}</td>
			                    	</tr>				                    													                    	
			                    </tbody>
			                  </table>
			                </div>			            		
		            	</div>
		            </div>
				</div>
			</section>
			</vue-html2pdf>
        </div>			
	</div>	
</div>