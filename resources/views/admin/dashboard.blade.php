@extends('layouts.auth')

@section('title', 'Admin')

@section('content')
    <h1>Admin page</h1>
    <p>Welcome, {{ auth()->user()->name }}. You have administrator access.</p>
    <p class="links"><a href="{{ route('dashboard') }}">Back to dashboard</a></p>
@endsection