@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
  <h2 class="fw-bold mb-1" style="font-family: var(--font-heading); font-size: 1.85rem; color: var(--forest-green);">Reset Your Password</h2>
  <p class="text-muted mb-4 small">Enter your email address and we'll send you instructions to reset your password.</p>

  @if(session('status'))
    <div class="alert alert-success border-0 rounded-3 small mb-4">
      <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-4">
      <label for="email" class="form-label fw-semibold small text-dark">Email Address</label>
      <input type="email" name="email" id="email" class="form-control rounded-3" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
    </div>
    <button type="submit" class="btn btn-egreen w-100 rounded-3 py-2 fw-bold">
      <i class="bi bi-envelope me-2"></i> Send Reset Instructions
    </button>
  </form>

  <div class="text-center mt-4">
    <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
      <i class="bi bi-arrow-left me-1"></i>Back to Sign In
    </a>
  </div>
@endsection
