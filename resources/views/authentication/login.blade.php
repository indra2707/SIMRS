    @extends('layouts.authentication.master')
    @section('title', 'Login')

    @section(section: 'css')
    @endsection

    @section('style')
    @endsection

    @section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-5"><img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/3.jpg') }}"
                        alt="looginpage"></div>
                <div class="col-xl-7 p-0">
                    <div class="login-card">
                        <div>
                            <div>
                                <a class="logo text-start" href="{{ route('admin.login') }}">
                                    <img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}"
                                        alt="looginpage">
                                    <img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}"
                                        alt="looginpage">
                                </a>
                            </div>
                            <div class="login-main">
                                <form class="theme-form needs-validation" novalidate="" autocomplete="off">
                                    @csrf
                                    <h4>Sign in to account</h4>
                                    <p>Enter your username & password to login</p>
                                    {{-- <div class="form-group position-relative">
                                        <label class="col-form-label" for="username">Username</label>
                                        <div class="input-group has-validation">
                                            <input class="form-control" id="username" name="username" type="text"
                                                aria-describedby="username" required="" placeholder="Username"
                                                @if (isset($_COOKIE['username'])) value="{{ $_COOKIE['username'] }}" @endif>
                                            <div class="invalid-tooltip">Please enter your username.</div>
                                        </div>
                                    </div>
                                    <div class="form-group position-relative">
                                        <label class="col-form-label" for="username">Password</label>
                                        <div class="input-group has-validation">
                                            <input class="form-control password" id="password" name="password"
                                                type="password" aria-describedby="password" required=""
                                                placeholder="*********"
                                                @if (isset($_COOKIE['password'])) value="{{ $_COOKIE['password'] }}" @endif>
                                                <div class="show-hide"><span class="show"></span></div>
                                            <div class="invalid-tooltip">Please enter password.</div>
                                        </div>
                                    </div>










 --}}


                                    <div class="form-group position-relative">
                                        <label class="col-form-label">Username</label>
                                        <input class="form-control username" type="text" required="" name="username"
                                            placeholder="Username"
                                            @if (isset($_COOKIE['username'])) value="{{ $_COOKIE['username'] }}" @endif>
                                    </div>
                                    <div class="invalid-tooltip">Please enter your username.</div>
                                    <div class="form-group position-relative">
                                        <label class="col-form-label">Password</label>
                                        <input class="form-control password" type="password" name="password" required=""
                                            placeholder="*********"
                                            @if (isset($_COOKIE['password'])) value="{{ $_COOKIE['password'] }}" @endif>
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                    <div class="valid-tooltip">Please enter password.</div>
                                    <div class="form-group mb-0">
                                        <div class="checkbox p-0">
                                            <input id="checkbox1" name="remember" type="checkbox"
                                                @if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) checked @endif>
                                            <label class="text-muted" for="checkbox1">Remember password</label>
                                        </div>
                                        <button class="btn btn-primary btn-block" id="btn-login" type="button">Sign
                                            in</button>
                                    </div>
                                    <script>
                                        (function() {
                                            'use strict';
                                            window.addEventListener('load', function() {
                                                $('#btn-login').click(function() {
                                                    var forms = document.getElementsByClassName('needs-validation');
                                                    var validation = Array.prototype.filter.call(forms, function(form) {
                                                        if (!form.checkValidity()) {
                                                            form.querySelector(".form-control:invalid").focus();
                                                            event.preventDefault();
                                                            event.stopPropagation();
                                                        } else {
                                                            form.classList.remove('was-validated');
                                                            var username = $("input[name='username']").val();
                                                            var password = $("input[name='password']").val();
                                                            var remember = $("input[name='remember']").is(":checked");
                                                            var token = $("meta[name='csrf-token']").attr("content");
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "{{ route('admin.login-process') }}",
                                                                dataType: "json",
                                                                data: {
                                                                    "username": username,
                                                                    "password": password,
                                                                    "remember": remember,
                                                                    "_token": token
                                                                },
                                                                success: function(res, status, xhr) {
                                                                    if (xhr.status == 200 && status == "success") {
                                                                        window.location.href =
                                                                            "{{ route('home') }}";
                                                                    } else {
                                                                        swal({
                                                                            icon: 'warning',
                                                                            title: 'Warning',
                                                                            text: res.message,
                                                                        });
                                                                    }
                                                                },
                                                                error: function(xhr, status, error) {
                                                                    if (xhr.status == 400) {
                                                                        var errors = xhr.responseJSON.errors;
                                                                        swal({
                                                                            icon: 'error',
                                                                            title: error,
                                                                            text: xhr.responseJSON.message,
                                                                        });
                                                                    } else if (xhr.status == 500) {
                                                                        swal({
                                                                            icon: 'error',
                                                                            title: error,
                                                                            text: "Silahkan hubungi administrator!",
                                                                        });
                                                                    }
                                                                }
                                                            });
                                                        }
                                                        form.classList.add('was-validated');
                                                    });
                                                })
                                            }, false);
                                        })();
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script src="{{ asset('assets/js/login.js') }}"></script>
    @endsection
