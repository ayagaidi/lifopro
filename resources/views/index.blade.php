<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- logo.ico --}}
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('logo.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> الاتـــحـاد الليبي للتـــأمين-@yield('title')</title>

    
    <link rel="stylesheet" href="{{ asset('dash/assets/styles/style.min.css') }}">

    <!-- Waves Effect -->
    <link rel="stylesheet" href="{{ asset('dash/assets/plugin/waves/waves.min.css') }}">

    <!-- RTL -->
    <link rel="stylesheet" href="{{ asset('dash/assets/styles/style-rtl.min.css') }}">
	<link rel="stylesheet" href="{{asset('dash/assets/fonts/cairo.css')}}">

</head>

<body style="font-family:Cairo;">

    <div id="single-wrapper"  style="background-color: white">
       
        <div class="row">
            <div class="col-md-4">
               <form method="POST" class="frm-single" action="">
                <div class="title" style="font-weight: bold;color: #a25541;"><img src="{{asset('logo.svg')}}" alt="" style="max-width: 20% !important;">
                    الاتـــحـاد الليبي للتـــأمين</div>
                    <h4 style="text-align: center;margin-top: 60px;font-size: large;font-weight: bold;color: #aa5940;">لتسجيل دخولك اختر</h4>
                    <a href="{{route('login')}}" class="frm-submit" style="text-align: center;padding-top: 13px;height: 50px;margin-top: 20px;"> تسجيل دخول ك [اتحاد الليبي للتآمين]</a>

                    <a href="{{route('company/login')}}" class="frm-submit" style="text-align: center;padding-top: 13px;height: 50px;margin-top: 10px;"> تسجيل دخول ك [شركة تآمين  ]</a>
                    <a href="{{route('office/login')}}" class="frm-submit" style="text-align: center;padding-top: 13px;height: 50px;margin-top: 10px;"> تسجيل دخول ك [مكتب تآمين   ]</a>

                <!-- .inside -->
            </form>
            </div>
            <div class="col-md-8" style="text-align: center;"> 
                <img src="{{asset('bk.svg')}}" alt="" style="max-width: 70% !important;">
            </div>
         
        </div>
        <!-- /.frm-single -->
    </div>


    <script src="{{ asset('dash/assets/scripts/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/assets/scripts/modernizr.min.js') }}"></script>
    <script src="{{ asset('dash/assets/plugin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dash/assets/plugin/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('dash/assets/plugin/waves/waves.min.js') }}"></script>

    <script src="{{ asset('dash/assets/scripts/main.min.js') }}"></script>
</body>

</html>