@extends('layouts.master')
@section('site_title','Manage Unit')
@section('units','active')
@section('content_title','Manage Unit')



@section('button_header')

@endsection



@section('breadcumb_nav')

<li class="breadcrumb-item"><a href="{{URL::to('dashboard')}}">Home</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('units') }}">Unit</a>
</li>
<li class="breadcrumb-item active">Manage</li>


@endsection



@section('content')

<div class="row op-0" id="content">
  <div class="col-md-12">
      <div class="card" id="cd">
        <div class="card-content collapse show">
          <div class="card-body col-md-12">
              <div class="form-body">

                <h4 class="form-section border-bottom-blue-grey border-bottom-darken-5 pb-1 mb-2"><i class="fa fa-file-text-o mr-1"></i> Basic Info</h4>
                <div class="form-group row">
                  <label class="col-md-3 label-control">Owner</label>
                  <div class="col-md-9">
                    <dynamic-select 
                        :options="option.owners"
                        option-value="id"
                        option-text="name"
                        placeholder="Choose Owner"
                        v-model="edit.owner" /> 
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 label-control">Apartment</label>
                  <div class="col-md-9">
                    <dynamic-select 
                        :options="option.apartments"
                        option-value="id"
                        option-text="name"
                        placeholder="Choose Apartment"
                        v-model="edit.apartment" /> 
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="col-md-3 label-control">Unit Number</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" v-model="edit.unit_number"/>
                  </div>
                </div>

                <h4 class="form-section border-bottom-blue-grey border-bottom-darken-5 pb-1 mb-2"><i class="icon icon-user-following mr-1"></i> Owner Price</h4>  
                <div class="col-md-12 mb-3">
                    <div class="form-group row">
                        <label>Price Weekday</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.owner_price_weekday" :options="cleaveOption"></cleave>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label>Price Weekend</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.owner_price_weekend" :options="cleaveOption"></cleave>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label>Price Weekly</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.owner_price_weekly" :options="cleaveOption"></cleave>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label>Price Monthly</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.owner_price_monthly" :options="cleaveOption"></cleave>
                        </div>
                    </div>                         
                </div>

                <h4 class="form-section border-bottom-blue-grey border-bottom-darken-5 pb-1 mb-2"><i class="icon icon-notebook mr-1"></i> Rental Price</h4>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label>Price Weekday</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.rent_price_weekday" :options="cleaveOption"></cleave>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label>Price Weekend</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.rent_price_weekend" :options="cleaveOption"></cleave>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label>Price Weekly</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.rent_price_weekly" :options="cleaveOption"></cleave>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label>Price Monthly</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.rent_price_monthly" :options="cleaveOption"></cleave>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label>Extra Charge</label>
                        <div class="display-contents">
                          <cleave class="form-control" v-model="edit.charge" :options="cleaveOption"></cleave>
                        </div>
                    </div>                                                    
                </div>               
              </div>
              <div class="form-actions mb-2 mt-2" style="float: right;">
                <a v-if="!onsubmit" href="{{ route('units') }}" class="btn btn-outline-primary mr-1">
                  <i class="fa fa-chevron-left"></i> Back
                </a>
                <button v-if="!onsubmit" @click="update" type="button" class="btn btn-primary">
                  <i class="fa fa-check"></i> Save
                </button>
                <vue-loaders v-if="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#967adc"></vue-loaders>
              </div>
          </div>
        </div>
      </div>
  </div>
</div>

<div class="rm" style="display: none;">
  <p id="api-edit">{{ route('api.units.edit', $id) }}</p>
  <p id="api-update">{{ route('api.units.update', $id) }}</p>
  <p id="api-apartments">{{ route('api.apartments.index') }}</p>
  <p id="api-owners">{{ route('api.owners.index') }}</p>
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
  .display-contents{
    display: contents;
  }
</style>

<script type="text/javascript" src="{{ URL::asset('js/view/unit/manage.js?').uniqid() }}"></script>

@endsection
