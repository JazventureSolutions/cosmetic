@extends('layouts.messages')

@php
$messages = [
    1 => "You've recently visited Cardiff Circumcision Clinic. Please give a review on Google https://g.co/kgs/UnCB8S and Facebook https://m.facebook.com/Circumcision-clinic-Cardiff-443008196221377.",
    2 => "You've recently visited Circumcision Clinic Glasgow. Please do us a favour and give us a review on Google https://g.co/kgs/KAGdUs and Facebook https://m.facebook.com/CircumcisionClinicGlasgow.",
    3 => "You've recently visited London Circumcision Clinic. Please give a review on Google https://www.google.com/search?q=surbiton+circumcision.",
]
@endphp

@section('title') {{$title}} @endsection

@section('message')
<p>{{$messages[$branchId ?? 1]}}</p>

<p>Thanks.<br>
Dr Khan</p>
@endsection
