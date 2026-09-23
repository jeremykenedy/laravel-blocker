@extends('laravelblocker::modern.layout')
@section('blocker-content')
    @include('laravelblocker::modern.list', ['deleted' => true])
@endsection
