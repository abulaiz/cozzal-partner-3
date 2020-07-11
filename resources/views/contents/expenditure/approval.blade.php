@extends('layouts.master')
@section('site_title','Approval Expenditure')
@section('approve_expenditure','active')
@section('content_title','Approval Expenditure')



@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-content">
        <div class="card-body">
          <ul class="nav nav-tabs nav-underline nav-justified" id="nav-ul">
            <li class="nav-item">
              <a class="nav-link active" @click="show(0)" ref="tab1" id="base-tab11" data-toggle="tab" aria-controls="tab11"
            href="#tab11" aria-expanded="false"><i class="fa fa-clock-o"></i>Billing</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" @click="show(1)" ref="tab2" id="base-tab12" data-toggle="tab" aria-controls="tab12" href="#tab12"
            aria-expanded="false"><i class="fa fa-pencil-square-o"></i>Non Billing</a>
            </li>
          </ul>
          <div class="tab-content px-1 pt-1">
            <div role="tabpanel" class="tab-pane active" id="tab11" aria-expanded="true" aria-labelledby="base-tab11">                  
              <div class="table-responsive table-data animated">
                <table class="table table-bordered table-hover" id="datatables1">
                  <thead>
                    <tr class="info">
                      <td>No</td>
                      <td>Description</td>
                      <td>Necessary</td>
                      <td>Price</td>
                      <td>Qty</td>
                      <td>Total</td>
                      <td>Due At</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody style="font-size:10pt;">
                  </tbody>
                </table>
                <hr>                   
              </div>                                  
            </div>
            <div role="tabpanel" class="tab-pane" id="tab12" aria-labelledby="base-tab12">
              <div class="table-responsive table-data animated">
                <table class="table table-bordered table-hover" id="datatables2">
                  <thead>
                      <tr class="info">
                        <td>No</td>
                        <td>Description</td>
                        <td>Necessary</td>
                        <td>Price</td>
                        <td>Qty</td>
                        <td>Total</td>
                        <td>Action</td>
                      </tr>
                  </thead>
                  <tbody style="font-size:10pt;">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade text-left" id="pay" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.expenditure.approval_pay')
</div> 

<div class="modal fade text-left" id="approve" tabindex="-1" role="dialog"  aria-hidden="true">
  @include('contents.expenditure.approval_approve')
</div> 

<div class="rm" style="display: none;">
  <p id="api-cashes">{{ route('api.cashes.index') }}</p>
  <p id="api-billing">{{ route('api.expenditures.index', '2') }}</p>
	<p id="api-non-billing">{{ route('api.expenditures.index', '3') }}</p>
  <p id="api-destroy">{{ route('api.expenditures.destroy', '0') }}</p>
  <p id="api-approve">{{ route('api.expenditures.approve') }}</p>
  <p id="api-pay">{{ route('api.expenditures.pay') }}</p>
</div>

@endsection



@section('pageJs')

<style type="text/css">
  .nav.nav-tabs.nav-underline .nav-item a.nav-link.active:focus, .nav.nav-tabs.nav-underline .nav-item a.nav-link.active:hover {
    color: #3BAFDA !important;
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/datatables/datatables.min.css')}}"/>
<script type="text/javascript" src="{{URL::asset('assets/datatables/datatables.min.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('js/view/expenditure/approval.js?').uniqid() }}"></script>

@endsection
