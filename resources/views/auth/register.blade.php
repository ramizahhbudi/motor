@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">Register</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="pin" class="form-label">PIN (6 Karakter Alfanumerik)</label>
            <input id="pin" type="password"
                   class="form-control" {{-- Tambahkan kelas ini --}}
                   name="pin"
                   required autocomplete="new-pin" 
                   maxlength="6" /> {{-- Tambahkan maxlength --}}
        </div>
        <div class="mb-3">
            <label for="pin_confirmation" class="form-label">Konfirmasi PIN</label>
            <input id="pin_confirmation"
                   type="password"
                   class="form-control" {{-- Tambahkan kelas ini --}}
                   name="pin_confirmation" required 
                   maxlength="6" /> {{-- Tambahkan maxlength --}}
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Sudah punya akun? Login</a>
        </div>
    </form>
</div>
@endsection