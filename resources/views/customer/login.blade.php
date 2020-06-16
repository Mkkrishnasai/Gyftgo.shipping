<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/animate/animate.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/css-hamburgers/hamburgers.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/select2/select2.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/css/main.css')}}">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
{{--                <img src="{{asset('assets/login/images/img-01.png')}}" alt="IMG">--}}
                <h3><strong>3P SHIPPING BY GYFTGO</strong></h3>
            </div>
{{--action="{{ route('customer.login') }}"--}}
            <form class="login100-form validate-form" id="cLoginForm">
                {{ csrf_field() }}
					<span class="login100-form-title">
						Customer Login
					</span>

                <div class="login100-form-validate" style="display: none" id="errorMessage">
                    <strong style="color: white">Failed to Login</strong>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: abc@domain.xyz">
                    <input class="input100" type="text" name="email" placeholder="Email" autocomplete="off">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                </div>

                <div class="container-login100-form-btn">
                    <button type="submit" name="submit" class="login100-form-btn">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{asset('assets/login/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/tilt/tilt.jquery.min.js')}}"></script>
<script >
    $('.js-tilt').tilt({
        scale: 1.1
    })
    $(document).on('submit','#cLoginForm', function (e) {
        e.preventDefault();
        var fd = $(this).serialize();
        $.ajax({
           method : 'POST',
           url : '{{ route('attemptLogin') }}',
           cache: false,
           processData : false,
           dataType : 'json',
           data : fd,
           success : function (data) {
                if(data.status == 200) {
                    window.location.reload();
                }else{
                    $('#errorMessage').html(data.message);
                    $('#errorMessage').show();
                }
           }
        });
    })
</script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/js/main.js')}}"></script>
</body>
</html>
