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
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="text" name="email" class="form-control username" id="exampleInputEmail1"
                            placeholder="Enter email" aria-describedby="exampleInputEmail1-error" aria-invalid="true">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control password" id="exampleInputPassword1"
                            placeholder="Password" aria-describedby="exampleInputPassword1-error">
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
        // Show password
        $('#showPassword').on('click', function() {
            if ($(this).is(':checked')) {
                $('.password').attr('type', 'text');
            } else {
                $('.password').attr('type', 'password');
            }
        })

        // Fungsi untuk login
        function login() {
            let username = $('.username').val();
            let password = $('.password').val();

            if (!username) return alert('Username wajib diisi');
            if (!password) return alert('Password wajib diisi');

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
                    toastr.success(response.message);
                },
                error: function(error) {
                    // Handle the error response
                    toastr.error(error.responseJSON.message);
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
