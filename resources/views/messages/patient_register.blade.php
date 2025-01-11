@extends('layouts.messages')

@section('title') {{$title}} @endsection

@section('message')
<p>The following details are needed to book an appointment, Kindly submit your details through this link:</p>

<a class="btn" href="{{isset($patient_register_link) ? $patient_register_link : url('/')}}">{{isset($patient_register_link) ? $patient_register_link : url('/')}}</a>

<br>

<p>Thanks.<br>
Dr Khan</p>
@endsection
