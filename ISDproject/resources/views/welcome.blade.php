<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                /* background-color: #fff; */
                background-image: linear-gradient(#32a2a8, #fff);
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height" >
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Đăng nhập</a>

                        <!-- @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif -->
                    @endauth
                </div>
            @endif
        
            <div class="content">
                <div class="title m-b-md">
                <!-- <span class="logo-mini"><b>AZ</b>Land</span> -->
                <img src="{{asset('layouts/dist/img/banner.webp')}}" alt="Công ty bất động sản AZ">
                </div>

                <!-- <div class="links"><a href="#">Công ty CP Bất động sản AZ (AZ Land)</a></div> -->
                <div class="links"><a href="">Tòa nhà AZ Building, 58 Trần Thái Tông , Dịch Vọng , Cầu Giấy , Hà Nội</a></div>
                <div class="links"><a href="">ĐT: (04) 3 5379 373</a></div>
                <div class="links"><a href="">Fax: (04) 3 5379 372</a></div>
                <div class="links"><a href="">E-mail: info@azland.vn</a></div>
                    <!-- <a href="https://laravel.com/docs">Docs</a>
                    
                    <a href="">Website: http://www.azland.com.vn</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a> -->
                
            </div>
        </div>
    </body>
</html>
<!-- @include('partialView.footer') -->