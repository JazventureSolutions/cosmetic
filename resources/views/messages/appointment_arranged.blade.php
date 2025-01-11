@extends('layouts.messages')

@section('title') {{$title}} @endsection

@section('message')
<p>Circumcision {{$is_pre_assessment ? "Pre assessment " : ""}}appointment: {{$datetime_formatted}} at {{$branch_address_line}}. {{$parents_confirm}} . {{$parents_away}} Please check your email for further details.{{-- PPE (mask, facial shield and gloves).  --}} {{$medicine ? ("Please bring " . $medicine . " with you.") : ""}}</p>

<p>Thanks.<br>
Dr Khan</p>
@endsection
