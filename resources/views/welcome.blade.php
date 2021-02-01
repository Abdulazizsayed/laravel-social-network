@extends('layouts.app')

@section('content')
<div class="guest">
    <div class="container-fluid">
        <div class="background-text text-center">
            <p>
                Start using our platform for <span class="free"> <strong> Free </strong></span>
            </p>
            <p>
                {{ App\User::all()->count() }} users are using our platform!
            </p>
            <a class="btn btn-success" href="{{ route('login') }}">Login</a>
            <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
        </div>
    </div>
</div>
@endsection
