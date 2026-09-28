@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
  <div class="mb-4">
    <span class="badge-organic" style="background: var(--color-sage-soft); color: var(--color-primary); border-color: var(--color-sage);">
      Portal Sign In
    </span>
    <h1 class="h2 mt-2 mb-1" style="font-family: var(--font-serif); color: var(--color-dark);">Welcome Back</h1>
    <p class="text-muted-lux small mb-0">Sign in to manage your orders, fresh produce stall, or marketplace operations.</p>
  </div>

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
      <label for="email" class="form-label-lux">Email Address</label>
      <input type="email" name="email" id="email" class="form-control form-control-lux w-100" value="{{ old('email') }}" placeholder="name@domain.com" required autocomplete="email" autofocus>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label-lux">Password</label>
      <div class="input-group">
        <input type="password" name="password" id="password" class="form-control form-control-lux rounded-start" placeholder="Enter your password" required autocomplete="current-password">
        <button type="button" class="btn btn-outline-secondary border" style="border-color: var(--color-border) !important; background: var(--color-surface);" id="togglePw" onclick="togglePassword('password', 'eyeIcon')">
          <i class="bi bi-eye" id="eyeIcon"></i>
        </button>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label small text-muted" for="remember">Keep me signed in</label>
      </div>
      <a href="{{ route('password.request') }}" class="small text-forest fw-semibold">Forgot password?</a>
    </div>

    <button type="submit" class="btn btn-lux-primary w-100 py-3 mb-2">
      <i class="bi bi-box-arrow-in-right me-1"></i>
      <span>Sign In</span>
    </button>
  </form>

  <div class="mt-4 pt-3 border-top text-center text-muted small" style="border-color: var(--color-border-subtle) !important;">
    <span>New to eGreen Basket?</span>
    <div class="d-flex justify-content-center gap-3 mt-2">
      <a href="{{ route('register') }}" class="text-forest fw-semibold text-decoration-none">
        <i class="bi bi-basket me-1"></i>Shopper Account
      </a>
      <span class="text-muted">•</span>
      <a href="{{ route('register.farmer') }}" class="text-forest fw-semibold text-decoration-none">
        <i class="bi bi-shop me-1"></i>Grower Stall Registration
      </a>
    </div>
  </div>

  <script>
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      }
    }
  </script>
@endsection
