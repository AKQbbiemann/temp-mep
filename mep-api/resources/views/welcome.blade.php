
@extends('layouts.app')

@section('content')

<h1>{{ __("lang.welcome", Auth::user()->name)}}</h1>    

@endsection
