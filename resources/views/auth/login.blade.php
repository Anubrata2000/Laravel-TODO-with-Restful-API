@extends('layouts.master-without-nav')

@section('title')
    Sign In - Todo App
@endsection

@section('content')
<div class="auth-page-wrapper pt-5">
    <!-- Auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- Auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="{{ url('/') }}" class="d-inline-block auth-logo">
                                <h2 class="text-white fw-bold"><i class="ri-task-line me-2"></i>Todo Manager</h2>
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">Organize, Track, and Achieve your tasks efficiently</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome Back!</h5>
                                <p class="text-muted">Sign in to manage your Todos.</p>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="p-2 mt-3">
                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required autofocus>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="password-input">Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5 password-input @error('password') is-invalid @enderror" placeholder="Enter password" id="password-input" name="password" required>
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="auth-remember-check" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Todo Manager. Built with Velzon & Laravel.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
@endsection
