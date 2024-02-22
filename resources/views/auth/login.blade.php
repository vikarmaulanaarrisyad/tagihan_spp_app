@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Admin</b>LTE</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Selamat datang di aplikasi pembayaran spp</p>
                <form id="loginForm" action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="auth">Username</label>
                        <input type="text" name="auth"
                            class="form-control username @error('auth') is-invalid @enderror" id="auth"
                            placeholder="Username" autocomplete="off">
                        @error('auth')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password"
                            class="form-control password @error('password') is-invalid @enderror" id="password"
                            placeholder="Password" aria-describedby="password-error">
                        @error('password')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="showPassword">
                                <label for="showPassword" class="text-muted" style="font-size: 15px">
                                    Show Password
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" onclick="login()" class="btn btn-primary btn-block" id="loginButton">
                                <span id="buttonText">Login</span>
                                <span id="loadingSpinner" style="display:none;"><i
                                        class="fas fa-spinner fa-spin"></i></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk login
        function login() {
            let username = $('.username').val();
            let password = $('.password').val();

            if (!username) return toastr.info('Username wajib diisi');
            if (!password) return toastr.info('Password wajib diisi');

            // Disable the button to prevent multiple clicks during the Ajax request
            $('#loginButton').attr('disabled', true);
            $('#buttonText').hide();
            $('#loadingSpinner').show();

            $.ajax({
                type: 'POST',
                url: '{{ route('login') }}',
                data: $('#loginForm').serialize(),
                success: function(response) {
                    // You can redirect or perform any other action here
                    toastr.success('Selamat anda berhasil login ke dalam sistem kami');
                    window.location.href = '{{ route('dashboard') }}';
                    $('#loginButton').attr('disabled', false);
                },
                error: function(errors) {
                    // Handle the error response
                    loopErrors(errors.responseJSON.errors);
                    toastr.error(errors.responseJSON.message);
                },
                complete: function() {
                    // Re-enable the button and hide the loading indicator
                    $('#loginButton').attr('disabled', false);
                    $('#buttonText').show();
                    $('#loadingSpinner').hide();
                }
            });
        }
    </script>
@endpush
