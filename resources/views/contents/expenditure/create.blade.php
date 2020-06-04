@extends('layouts.master')
@section('site_title','Create Expenditure')
@section('create_expenditure','active')
@section('content_title','Create Expenditure')



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
            <h6>Step 1</h6>
            <fieldset>
              <div class="row" id="_step1">
                <div class="col-md-6">
                  <div class="form-group">
                      <label>Expenditure Type</label>
                      <div>
                          <dynamic-select 
                              :options="option.type"
                              option-value="id"
                              option-text="name"
                              placeholder="Type to search"
                              v-model="type" />                    
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label>Necessary For</label>
                      <div>
                          <dynamic-select 
                              :options="option.necessary"
                              option-value="id"
                              option-text="name"
                              placeholder="Type to search"
                              v-model="necessary" />                    
                      </div>
                  </div>
                </div>
              </div>
            </fieldset>
            <!-- Step 2 -->
            <h6>Step 2</h6>
            <fieldset>
              <div class="row" id="_step2">
                <div class="col-md-6" v-if="necessary.id == 2">
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
                  </div>
                </div>
                <div class="col-md-6" v-if="necessary.id == 2 && unit_loaded">
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
                      <label>Price (IDR)</label>
                      <div>
                          <cleave class="form-control" v-model="price" :options="cleaveOption"></cleave>
                      </div>
                  </div>                  
                </div>              
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Qty </label>
                    <input type="number" min="1" class="form-control" v-model="qty">
                  </div>
                </div>
                <div class="col-md-6" v-if="type.id == 2">
                  <div class="form-group">
                    <label>Due at </label>
                    <div>
                      <date-picker format="DD-MM-YYYY" value-type="YYYY-MM-DD" v-model="due_at" :disabled-date="notBeforeToday"></date-picker>
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="type.id == 1">
                  <div class="form-group">
                      <label>Source Fund</label>
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
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Short Description </label>
                    <input type="text" v-model="description" required class="form-control" placeholder="Exependiture Description" maxlength="50">           
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
  <p id="api-url-cashes">{{ route('api.cashes.index') }}</p>
  <p id="api-url-apartments">{{ route('api.apartments.index') }}</p>
  <p id="api-url-units">{{ route('api.units.index') }}</p>
  <p id="api-url-expenditures-store">{{ route('api.expenditures.store') }}</p>
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

  .mx-datepicker{
    width: 100% !important;
  }  
  .mx-input{
    height: 40px !important;
  }
</style>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/forms/wizard.min.css') }}">
<script src="{{ URL::asset('app-assets/vendors/js/extensions/jquery.steps.min.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/expenditure/create.js?').uniqid() }}"></script>

@endsection