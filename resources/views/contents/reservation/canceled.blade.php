@extends('layouts.master')
@section('site_title','Canceled Reservation')
@section('canceled_reservation','active')
@section('content_title','Canceled List')



@section('content')


@endsection 



@section('pageJs')

<script type="text/javascript" src="{{ URL::asset('js/view/reservation/canceled.js?').uniqid() }}"></script>

@endsection