{{-- @extends('layouts.messages')

@section('title') {{$title}} @endsection

@section('message')
<p>Good Morning,</p>
<p>Please confirm your{{$parents_confirm == "" ? "" : " son's"}} {{$is_pre_assessment ? "pre assessment" : "circumcision"}} appointment on {{$datetime_formatted}} at {{$branch_address_line}}.{{$parents_confirm}}<br>
No response will result in a cancellation.</p>

<p>Thanks.<br>
Dr Khan</p>
@endsection --}}

@extends('layouts.messages')

@section('title') {{$title}} @endsection

@section('message')
<p>Good Morning,</p>
<p>Please confirm your{{$parents_confirm == "" ? "" : " son's"}} {{$is_pre_assessment ? "pre assessment" : "circumcision"}} appointment  by sending a text message. {{$datetime_formatted}} at {{$branch_address_line}}.{{$parents_confirm}}. {{$parents_away}}<br>
No response will result in a cancellation.</p>

<p>Thanks.<br>
Dr Khan</p>
@endsection
