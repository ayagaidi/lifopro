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

    <!-- Material Design Icon -->
    <link rel="stylesheet" href="{{ asset('dash/assets/fonts/material-design/css/materialdesignicons.css') }}">

    <!-- mCustomScrollbar -->
    <link rel="stylesheet" href="{{ asset('dash/assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css') }}">

    <!-- Waves Effect -->
    <link rel="stylesheet" href="{{ asset('dash/assets/plugin/waves/waves.min.css') }}">

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="{{ asset('dash/assets/plugin/sweet-alert/sweetalert.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('dash/assets/plugin/iCheck/skins/square/blue.css') }}">

    <!-- RTL -->
    <link rel="stylesheet" href="{{ asset('dash/assets/styles/style-rtl.min.css') }}">
    <!-- cairo -->

    <link rel="stylesheet" href="{{ asset('dash/assets/fonts/cairo.css') }}">



    <script src="{{ asset('dash/assets/scripts/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/assets/scripts/modernizr.min.js') }}"></script>
    <script src="{{ asset('dash/assets/plugin/bootstrap/js/bootstrap.min.js') }}"></script>


    <script src="{{ asset('dash/assets/jquery-datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vfs_fonts.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>

    <script src="{{ asset('dash/assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- <script src="{{ asset('dash/assets/plugin/sweet-alert/sweetalert.min.js') }}"></script> -->
    <script src="{{ asset('dash/assets/plugin/waves/waves.min.js') }}"></script>
    <!-- Full Screen Plugin -->
    <script src="{{ asset('dash/assets/plugin/fullscreen/jquery.fullscreen-min.js') }}"></script>
    <script src="{{ asset('dash/assets/plugin/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('dash/assets/plugin/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('sweetalert2@9') }}"></script>

    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('dash/assets/plugin/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('print.min.js') }}"></script>

    <style>
        .dataTables_wrapper .dt-buttons {
            float: none;
            text-align: left;
        }

        .dataTables_wrapper .dataTables_filter {
            text-align: right;
            float: right;
        }

        .logo-icon {
            width: 35px;
            margin-right: 5px;
        }
    </style>

    <script>
        pdfMake.fonts = {

            Cairo: {
                normal: "Cairo-VariableFont_slnt,wght.ttf",
                bold: 'Cairo-VariableFont_slnt,wght.ttf'
            }
        }
    </script>
</head>

<body style="font-family:Cairo;">
    <style>
        /* Loader Overlay */
        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* Loader Spinner */
        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid 8px solid #542c20;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        /* Spinner Animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>


    <!-- Loader HTML -->
    <div id="loader-overlay" style="display: none;">
        <div class="loader"></div>
    </div>
    <div class="main-menu">
        <header class="header">
            <a href="{{ route('office/home') }}" class="logo">
                <img src="{{ asset('logo/companies/' . Auth::user()->offices->companies->logo) }}" class="logo-icon"
                    alt="logo icon">
                {{ Auth::user()->offices->companies->name }}
            </a>
            <button type="button" class="button-close fa fa-times js__menu_close"></button>
        </header>
        <!-- /.header -->
        <div class="content">

            <div class="navigation">
                <ul class="menu js__accordion">
                    <li>
                        <a class="waves-effect" href="{{ route('office/home') }}"><i
                                class="menu-icon mdi mdi-view-dashboard"></i><span>الرئيسية</span></a>
                    </li>
                    @if (Auth::user()->user_type_id == 1)
                        <li class="{{ Request::is('office/offices_users*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('office/offices_users') }}"><i
                                    class="menu-icon fa  fa-users"></i><span>المستخدمين </span></a>
                        </li>
                        <li
                            class="{{ Request::is('office/card') ? 'current' : '' }} {{ Request::is('office/card/active') ? 'current' : '' }}
                        {{ Request::is('office/card/sold') ? 'current' : '' }}
                        {{ Request::is('office/card/search') ? 'current' : '' }}">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-credit-card"></i><span> البطاقات </span><span
                                    class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('office/card') }}">كافة البطاقات </a></li>
                                <li><a href="{{ route('office/card/active') }}"> بطاقات المعينة </a></li>
                                <li><a href="{{ route('office/card/sold') }}"> بطاقات المصدرة </a></li>
                                <li><a href="{{ route('office/card/cancel') }}"> بطاقات ملغية </a></li>

                                <li><a href="{{ route('office/card/search') }}"> بحث بواسطة </a></li>




                            </ul>
                        </li>
                        <li class="{{ Request::is('office/issuing*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('office/issuing') }}"><i
                                    class="menu-icon fa fa-file-text-o"></i><span>اصدار وثيقة </span></a>
                        </li>
                        <li
                            class="{{ Request::is('office/report/issuing') ? 'current' : '' }}{{ Request::is('office/report/stock') ? 'current' : '' }} ">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-credit-card"></i><span> ادارة التقارير </span><span
                                    class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('office/report/issuing') }}"> تقارير المبيعات </a></li>
                                <li><a href="{{ route('office/report/issuing/summary') }}"> تقارير المبيعات  [مختصر]</a></li>
  
                                <li><a href="{{ route('office/report/stock') }}"> تقارير المخزون </a></li>

                            </ul>
                        </li>
                    @endif

                    @php

                        $hasPermission = $OfficeUser
                            ->where('office_users_id', Auth::user()->id)
                            ->where('office_user_permissions_id', 1)
                            ->first();
                            
                        // dd($hasPermission);
                    @endphp
                    @if (Auth::user()->userType->id == 2 && $hasPermission)
                        <li
                            class="{{ Request::is('office/card') ? 'current' : '' }} {{ Request::is('office/card/active') ? 'current' : '' }}
                {{ Request::is('office/card/sold') ? 'current' : '' }}
                {{ Request::is('office/card/search') ? 'current' : '' }}">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-credit-card"></i><span> البطاقات </span><span
                                    class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('office/card') }}">كافة البطاقات </a></li>
                                <li><a href="{{ route('office/card/active') }}"> بطاقات المعينة </a></li>
                                <li><a href="{{ route('office/card/sold') }}"> بطاقات المصدرة </a></li>
                                <li><a href="{{ route('office/card/cancel') }}"> بطاقات ملغية </a></li>

                                <li><a href="{{ route('office/card/search') }}"> بحث بواسطة </a></li>




                            </ul>
                        </li>
                    @endif

                    @php

                        $hasPermission = $OfficeUser
                            ->where('office_users_id', Auth::user()->id)
                            ->where('office_user_permissions_id', 2)
                            ->first();
                        // dd($hasPermission);
                    @endphp
                    @if (Auth::user()->userType->id == 2 && $hasPermission)
                        <li class="{{ Request::is('office/issuing*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('office/issuing') }}"><i
                                    class="menu-icon fa fa-file-text-o"></i><span>اصدار وثيقة </span></a>
                        </li>
                    @endif



                    @php

                        $hasPermission = $OfficeUser
                            ->where('office_users_id', Auth::user()->id)
                            ->where('office_user_permissions_id', 3)
                            ->first();
                        // dd($hasPermission);
                    @endphp
                    @if (Auth::user()->userType->id == 2 && $hasPermission)
                        <li
                            class="{{ Request::is('office/report/issuing') ? 'current' : '' }}{{ Request::is('office/report/stock') ? 'current' : '' }} ">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-credit-card"></i><span> ادارة التقارير </span><span
                                    class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('office/report/issuing') }}"> تقارير المبيعات </a></li>
                                <li><a href="{{ route('office/report/issuing/summary') }}"> تقارير المبيعات  [مختصر]</a></li>

                                <li><a href="{{ route('office/report/stock') }}"> تقارير المخزون </a></li>

                            </ul>
                        </li>
                    @endif




                </ul>
                <!-- /.menu js__accordion -->
            </div>
            <!-- /.navigation -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.main-menu -->

    <div class="fixed-navbar">
        <div class="pull-left">
            <button type="button"
                class="menu-mobile-button glyphicon glyphicon-menu-hamburger js__menu_mobile"></button>
            <h1 class="page-title">@yield('title')</h1>
            <!-- /.page-title -->
        </div>
        <!-- /.pull-left -->
        <div class="pull-right">

            <!-- /.ico-item -->
            <div class="ico-item fa fa-arrows-alt js__full_screen"></div>
            <!-- /.ico-item fa fa-fa-arrows-alt -->

            <!-- /.ico-item -->

            <a href="#" class="ico-item ">
                <h5 class="name">{{ Auth::user()->username }} ( {{ Auth::user()->userType->slug }} مكتب
                    )</h5>
            </a>
            <div class="ico-item">
                <img src="{{ asset('office.png') }}" alt="" class="ico-img">
                <ul class="sub-ico-item">
                    <li><a href="{{ route('office/profile', encrypt(Auth::user()->id)) }}"><i class="fa fa-user"></i>
                            {{ trans('app.Profile') }}</a></li>

                    <li><a href="{{ route('office/ChangePasswordForm', encrypt(Auth::user()->id)) }}"><i
                                class="fa fa-lock"></i>{{ trans('app.changepassword') }} </a></li>
                    <li><a href="{{ route('office/logout') }}"
                            onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i
                                class="fa fa-sign-out"></i>
                            {{ trans('app.logout') }}</a>
                        <form id="logout-form" action="{{ route('office/logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>>
                    </li>
                </ul>
                <!-- /.sub-ico-item -->
            </div>
            <!-- /.ico-item -->
        </div>
        <!-- /.pull-right -->
    </div>
    <!-- /.fixed-navbar -->

    <!-- /.content -->


    <div id="wrapper">
        <div class="main-content">
            @yield('content')
            @include('sweetalert::alert')

            <!-- /.row -->
            <footer class="footer">
                <ul class="list-inline">
                    <li><?php echo date('Y'); ?> &copy;{{ trans('login.copyright') }} </li>

                </ul>
            </footer>
        </div>
        <!-- /.main-content -->
    </div>


    <script src="{{ asset('dash/assets/scripts/main.min.js') }}"></script>
    <script src="{{ asset('sweetalert2@9') }}"></script>

</body>

</html>
