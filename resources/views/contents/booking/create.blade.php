@extends('layouts.master')
@section('site_title','Input Booking')
@section('create_booking','active')
@section('content_title','Input Booking')



@section('content')

<div class="row">
  <div class="col-12">
    <div class="card op-0 animated" id="content">
      <div class="card-header">
        <h4 class="card-title">Completed by two step</h4>
      </div>
      <div class="card-content">
        <div class="card-body">
          <div id="loader">
            <div class="pull-right">
              <vue-loaders class="mr-2" v-if="onsubmit" name="line-scale-pulse-out-rapid" color="#967ADC"></vue-loaders>
            </div>
          </div>             
          <div class="number-tab-steps wizard-circle">
            <!-- Step 1 -->
            <h6><i class="step-icon fa fa-user"></i> Step 1</h6>
            <fieldset>
              <div id="_step1" class="pb-3 pt-1">
                <tenant-menu :url="url" @select="setTenant" @unselect="resetTenant"></tenant-menu>
              </div>
            </fieldset>
            <!-- Step 2 -->
            <h6><i class="step-icon fa fa-calendar"></i>Step 2</h6>
            <fieldset>
              <div class="row" id="_step2">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Check in  </label>
                    <div>
                      <date-picker :lang="lang" format="DD-MM-YYYY" value-type="YYYY-MM-DD" v-model="check_in"></date-picker>
                    </div>
                  </div>
                </div>  
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Check out  </label>
                    <div>
                      <date-picker :lang="lang" format="DD-MM-YYYY" value-type="YYYY-MM-DD" v-model="check_out" :disabled-date="notBeforeCheckIn"></date-picker>
                    </div>
                  </div>
                </div>                                 
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Days </label>
                    <input type="number" min="1" class="form-control" v-model="count_day">
                  </div>
                </div>                                                        
              </div>
            </fieldset>

            <!-- Step 3 -->
            <h6><i class="step-icon fa fa-building-o"></i>Step 3</h6>
            <fieldset>
              <div class="row" id="_step3">                           
                <div class="col-md-6">
                  <div class="form-group">
                      <label>Apartment</label>
                      <div>
                          <dynamic-select 
                              :options="option.apartments"
                              option-value="id"
                              option-text="name"
                              placeholder="Type to search"
                              v-model="apartment" />                    
                      </div>
                      <div v-show="!unit_loaded || option.units == null">
                        <p class="text-primary">Unit list will appear when you has select apartment and if unit available</p>
                      </div>
                  </div>
                </div>
                <div class="col-md-6" v-show="unit_loaded">
                  <div class="form-group">
                      <label>Unit</label>
                      <div>
                          <dynamic-select 
                              :options="option.units"
                              option-value="id"
                              option-text="unit_number"
                              placeholder="Type to search"
                              v-model="unit" />                    
                      </div>
                  </div>
                </div>  
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Guest Count </label>
                    <input type="number" min="1" class="form-control" v-model="guest_count">
                  </div>
                </div>                                                          
              </div>
            </fieldset>   

            <!-- Step 4 -->
            <h6><i class="step-icon fa fa-money"></i>Step 4</h6>
            <fieldset>
            <div class="row" id="_step4">

              <!-- Message if has mod price -->
              <div class="col-md-12" v-if="price.mod_prices.length > 0">
                <div class="alert bg-info alert-icon-left" role="alert">
                  <span class="alert-icon"><i class="fa fa-info-circle"></i></span><strong>Info. </strong>Prices on several dates have changed</div>                
              </div>
              <!-- Mode Prices Field -->
              <div class="col-md-12" v-if="price.mod_prices.length > 0">
                <div class="col-md-12 pb-2 mb-1 border-bottom-blue-grey border-bottom-lighten-2">
                  <div class="row mt-1" v-for="(item, i) in price.mod_prices">
                    <div class="row">
                      <div class="col-md-12">
                        <p class="font-weight-bold">From @{{ mod_prices[i].start }} to @{{ mod_prices[i].end }}</p>
                      </div>
                      <div class="col-md-6">
                        <label>Owner Price :</label>
                        <cleave class="form-control" v-model="price.mod_prices[i].owner_price" @keyup.native="modPriceKeyUp" :options="cleave"></cleave>
                      </div>
                      <div class="col-md-6">
                        <label>Rent Price :</label>
                        <cleave class="form-control" v-model="price.mod_prices[i].rent_price" @keyup.native="modPriceKeyUp" :options="cleave"></cleave>
                      </div>                       
                    </div>                   
                  </div>
                </div>                
              </div>


              <!-- Normal Price -->
              <div class="col-md-6">
                <div v-show="show.owner_weekday_price" class="form-group">
                  <label>Owner Weekday :</label>
                  <cleave class="form-control" v-model="price.owner_weekday_price" 
                  :options="cleave"></cleave>
                </div>
                <div v-show="show.owner_weekend_price" class="form-group">
                  <label>Owner Weekend :</label>
                  <cleave class="form-control" v-model="price.owner_weekend_price" 
                  :options="cleave"></cleave>
                </div>
                <div v-show="show.owner_weekly_price" class="form-group">
                  <label>Owner Weekly :</label>
                  <cleave class="form-control" v-model="price.owner_weekly_price" 
                  :options="cleave"></cleave>
                </div>
                <div v-show="show.owner_monthly_price" class="form-group">
                  <label>Owner Monthly :</label>
                  <cleave class="form-control" v-model="price.owner_monthly_price" 
                  :options="cleave"></cleave>
                </div>  
                <div class="form-group">
                  <label>Owner Total :</label>
                  <cleave :readonly="true" class="form-control" v-model="price.owner_price_total" :options="cleave"></cleave>
                </div>                   
              </div> 
              <div class="col-md-6">
                <div v-show="show.rent_weekday_price" class="form-group">
                  <label>Rent Weekday :</label>
                  <cleave class="form-control" v-model="price.rent_weekday_price" 
                  :options="cleave"></cleave>
                </div>
                <div v-show="show.rent_weekend_price" class="form-group">
                  <label>Rent Weekend :</label>
                  <cleave class="form-control" v-model="price.rent_weekend_price" 
                  :options="cleave"></cleave>
                </div>
                <div v-show="show.rent_weekly_price" class="form-group">
                  <label>Rent Weekly :</label>
                  <cleave class="form-control" v-model="price.rent_weekly_price" 
                  :options="cleave"></cleave>
                </div>
                <div v-show="show.rent_monthly_price" class="form-group">
                  <label>Rent Monthly :</label>
                  <cleave class="form-control" v-model="price.rent_monthly_price" 
                  :options="cleave"></cleave>
                </div>  
                <div class="form-group">
                  <label>Rent Total :</label>
                  <cleave :readonly="true" class="form-control" v-model="price.rent_price_total" 
                  :options="cleave"></cleave>
                </div>                   
              </div>  
              <div class="col-md-6 mb-1">
                <a href="javascript:void(0)" class="btn btn-outline-info btn-sm tooltipp" :title="date_info">
                  <i class="fa fa-calendar"></i>
                </a>
              </div>
              <div v-show="show.charge" class="col-md-6">
                <div class="form-group">
                  <label>Charge :</label>
                  <cleave class="form-control" v-model="price.charge" 
                  :options="cleave"></cleave>
                </div>
              </div>  
              <div v-show="show.deposite" :class="`${show.charge ? 'offset-md-6' : ''} col-md-6`">
                <div class="form-group">
                  <label>Deposite :</label>
                  <cleave class="form-control" v-model="price.deposite" 
                  :options="cleave"></cleave>
                </div>
              </div>  
              <div :class="`${show.deposite || show.charge ? 'offset-md-6' : ''} col-md-6`">
                <div class="form-group">
                  <label><strong>Amount Bill :</strong></label>
                  <cleave class="form-control" v-model="price.amount_bill" :options="cleave"></cleave>
                </div>
              </div>
            </div>          
            </fieldset>    

            <!-- Step 5 -->
            <h6><i class="step-icon fa fa-check-square"></i>Step 5</h6>
            <fieldset>
              <div class="row" id="_step5">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Payment Type :</label>
                    <div>
                        <dynamic-select 
                            :options="option.payment_types"
                            option-value="id"
                            option-text="name"
                            placeholder="Select one"
                            v-model="payment_type" />  
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="payment_type.id != 3">
                  <div class="form-group">
                    <label>DP Via :</label>
                    <div>
                        <dynamic-select 
                            :options="option.cashes"
                            option-value="id"
                            option-text="name"
                            placeholder="Type to search"
                            v-model="cash" />                    
                    </div>
                  </div>
                </div>  
                <div class="col-md-6" v-if="payment_type.id != 3">
                  <div class="form-group">
                    <label>Payment Slip</label>
                    <upload-image v-model="attachment"></upload-image>
                  </div>                  
                </div>
                <div class="col-md-6" v-if="payment_type.id != 3">
                  <div class="form-group">
                    <label>DP :</label>
                    <cleave class="form-control" v-model="dp" :readonly="payment_type.id != 2"
                    :options="cleave"></cleave>
                  </div>
                </div> 
                <div class="col-md-12 border-top border-top-grey border-top-lighten-2 pt-1"></div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Booking Via :</label>
                    <div>
                        <dynamic-select 
                            :options="option.booking_vias"
                            option-value="id"
                            option-text="name"
                            placeholder="Type to search"
                            v-model="booking_via" />                    
                    </div>
                  </div>
                </div>   
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Note :</label>
                    <textarea class="form-control" v-model="note"></textarea>
                  </div>
                </div>    
              </div>
            </fieldset>

          </div>       
        </div>
      </div>
    </div>
  </div>
</div>

<div class="rm" style="display: none;">
  <p id="api-tenants-index">{{ route('api.tenants.index') }}</p>
  <p id="api-tenants-store">{{ route('api.tenants.store') }}</p>
  <p id="api-apartments">{{ route('api.apartments.index') }}</p>
  <p id="api-booking_vias">{{ route('api.booking_vias.index') }}</p>
  <p id="api-cashes">{{ route('api.cashes.index') }}</p>
  <p id="api-units">{{ route('api.unit.availability') }}</p>
  <p id="api-mod-prices">{{ route('api.unit.mod_prices') }}</p>
  <p id="api-booking-store">{{ route('api.booking.store') }}</p>
</div>

@endsection 



@section('pageJs')

<style type="text/css">
  .iti-flag {
    background-image : url({{ URL::asset('images/flags.9c96e0ed.png')  }}) !important;
  } 
  .vue-dynamic-select{
    padding: 0.6rem 1rem !important;
  }

  .vue-dynamic-select .result-list{
    left: 7px;
    top: 36px;
  }

  .mx-datepicker{
    width: 100% !important;
  }  
  .mx-input{
    height: 40px !important;
  }
</style>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/forms/wizard.min.css') }}">
<script src="{{ URL::asset('app-assets/vendors/js/extensions/jquery.steps.min.js') }}" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/vendors/css/ui/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/ui/jqueryui.css') }}">
<script src="{{ URL::asset('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/booking/create.js?').uniqid() }}"></script>

@endsection