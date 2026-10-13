@extends('layouts.auth')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <p>Signed in as {{ auth()->user()->name }}.</p>

    @if (auth()->user()->role === \App\Models\User::ROLE_ADMIN)
        <p><a href="{{ route('admin.dashboard') }}">Open admin page</a></p>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log out</button>
    </form>
@endsection