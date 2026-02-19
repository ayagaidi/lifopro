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
    <script>
        pdfMake.fonts = {

            Cairo: {
                normal: "Cairo-VariableFont_slnt,wght.ttf",
                bold: 'Cairo-VariableFont_slnt,wght.ttf'
            }
        }
    </script>

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

</head>

<body style="font-family:Cairo;">
    <div id="loader-overlay" style="display: none;">
        <div class="loader"></div>
    </div>
    <div class="main-menu">
        <header class="header">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('logo.svg') }}" class="logo-icon" alt="logo icon">
                الإتحاد الليبي للتأمين
            </a>
            <button type="button" class="button-close fa fa-times js__menu_close"></button>
        </header>
        <!-- /.header -->
        <div class="content">

            <div class="navigation">
                <ul class="menu js__accordion">
                    <li>
                        <a class="waves-effect" href="{{ route('home') }}"><i
                                class="menu-icon mdi mdi-view-dashboard"></i><span>الرئيسية</span></a>
                    </li>
                    @can('user-list')
                        <li class="{{ Request::is('users*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('users') }}"><i
                                    class="menu-icon fa fa-users"></i><span>{{ trans('app.users') }}</span></a>
                        </li>
                    @endcan
                    @can('role-list')
                        <li class="{{ Request::is('roles*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('roles/index') }}"><i
                                    class="menu-icon fa fa-info"></i><span>الصلاحيات</span></a>
                        </li>
                    @endcan


                    @can('region-list')
                        <li class="{{ Request::is('region*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('region') }}"><i
                                    class="menu-icon fa  fa-map-marker"></i><span>المناطق</span></a>
                        </li>
                    @endcan

                    @can('cities-list')
                        <li class="{{ Request::is('cities*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('cities') }}"><i
                                    class="menu-icon fa  fa-map-marker"></i><span>{{ trans('app.city') }}</span></a>
                        </li>
                    @endcan

                    @can('company-list')
                        <li class="{{ Request::is('company*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('company') }}"><i
                                    class="menu-icon fa  fa-hospital-o"></i><span>الشركات</span></a>
                        </li>
                    @endcan

                    @can('offices-list')
                        <li class="{{ Request::is('offices*') ? 'current' : '' }} ">
                            <a class="waves-effect " href="{{ route('offices') }}"><i
                                    class="menu-icon fa  fa-home"></i><span>المكاتب</span></a>
                        </li>
                    @endcan

                    @can('cardrequests-list')
                        <li class="{{ Request::is('cardrequests*') ? 'current' : '' }}">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-credit-card-alt"></i><span>طلب بطاقات التآمين
                                </span><span class="menu-arrow fa fa-angle-down"></span></a>

                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('cardrequests') }}"> طلبات المكتب الموحد </a></li>
                                <li><a href="{{ route('cardrequests/company') }}"> طلبات شركات التآمين </a></li>



                            </ul>
                        </li>
                    @endcan


                    @can('card-list')
                        <li
                            class="{{ Request::is('card') ? 'current' : '' }} {{ Request::is('card/active') ? 'current' : '' }}
                        {{ Request::is('card/sold') ? 'current' : '' }}
                         {{ Request::is('card/inactive') ? 'current' : '' }}
                          {{ Request::is('card/search') ? 'current' : '' }}">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-credit-card"></i><span> البطاقات </span><span
                                    class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('card') }}">كافة البطاقات </a></li>
                                <li><a href="{{ route('card/active') }}"> بطاقات المعينة </a></li>
                                <li><a href="{{ route('card/inactive') }}"> بطاقات متبقية </a></li>
                                <li><a href="{{ route('card/sold') }}"> بطاقات المصدرة </a></li>
                                <li><a href="{{ route('card/cancel') }}"> بطاقات ملغية </a></li>

                                <li><a href="{{ route('card/search') }}"> بحث بواسطة </a></li>




                            </ul>
                        </li>
                    @endcan

                    <li
                        class="">
                        <a class="waves-effect parent-item js__control" href="#"><i
                                class="menu-icon fa fa-edit"></i><span> ادارة </span><span
                                class="menu-arrow fa fa-angle-down"></span></a>
                        <ul class="sub-menu js__content" style="display: none;">
                            @can('price-list')
                                <li><a href="{{ route('price') }}">اسعار الاقساط </a></li>
                            @endcan
                            @can('api-list')
                                <li><a href="{{ route('apiuser') }}">ادراة حساباتAPI</a></li>
                            @endcan
                            @can('car-list')
                                <li><a href="{{ route('car') }}">السيارات </a></li>
                            @endcan
                            @can('country-list')
                                <li><a href="{{ route('country') }}">الدول </a></li>
                            @endcan
                            @can('countrycon-list')
                                <li><a href="{{ route('countryconditions') }}">شروط الدول </a></li>
                            @endcan

                            @can('vehiclenationalities-list')
                            <li><a href="{{ route('vehiclenationalities') }}">جنسية المركبة  </a></li>

                            
                        @endcan
                        @can('insurance_clause-list')
                        <li><a href="{{ route('insurance_clause') }}">بند التآمين  </a></li>

                        
                    @endcan

                     @can('purposeofuses-list')
                    <li><a href="{{ route('purposeofuses') }}">غرض الاستعمال  </a></li>


                @endcan
                
                <!-- Card Field Visibility Management -->
                @can('card-field-visibility-list')
                 <li><a href="{{ route('dashbord.card_field_visibility.index') }}">{{ trans('app.card-field-visibility-list') }}</a></li>
                @endcan
              
                        </ul>
                    </li>








                   

                


                    @if (Auth::user()->id == 1) 
                        <li
                            class="{{ Request::is('report/officeStats') ||
                            Request::is('report/office-users-stats') ||
                            Request::is('report/officeUsersStats') ||
                            Request::is('report/totalcompanyissuingstats') ||
                            Request::is('report/countryissuingsstats')
                                ? 'current'
                                : '' }}">
                            <a class="waves-effect parent-item js__control" href="#">
                                <i class="menu-icon fa fa-bar-chart"></i>
                                <span>إدارة الإحصائيات</span>
                                <span class="menu-arrow fa fa-angle-down"></span>
                            </a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('report/officeStats') }}">الإصدارات حسب المكتب</a></li>
                                <li><a href="{{ route('report/countryissuingsstats') }}">الإصدارات حسب الدولة</a></li>
                                <li><a href="{{ route('report/totalcompanyissuingstats') }}">إصدارات الشركات
                                        والمكاتب</a></li>
                                <li><a href="{{ route('report/officeUsersStats') }}">إصدارات مستخدمي المكاتب</a></li>
                            </ul>
                        </li>
                    @else

                    @can('report-list')
              
                   
                        <li
                            class="{{ Request::is('report/officeStats') ||
                            Request::is('report/office-users-stats') ||
                            Request::is('report/officeUsersStats') ||
                            Request::is('report/totalcompanyissuingstats') ||
                            Request::is('report/countryissuingsstats')
                                ? 'current'
                                : '' }}">
                            <a class="waves-effect parent-item js__control" href="#">
                                <i class="menu-icon fa fa-bar-chart"></i>
                                <span>إدارة الإحصائيات</span>
                                <span class="menu-arrow fa fa-angle-down"></span>
                            </a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('report/officeStats') }}">الإصدارات حسب المكتب</a></li>
                                <li><a href="{{ route('report/countryissuingsstats') }}">الإصدارات حسب الدولة</a></li>
                                <li><a href="{{ route('report/totalcompanyissuingstats') }}">إصدارات الشركات
                                        والمكاتب</a></li>
                                <li><a href="{{ route('report/officeUsersStats') }}">إصدارات مستخدمي المكاتب</a></li>
                            </ul>
                        </li>
                         @endcan
                    @endif


                        <li class="{{ Request::is('report/issuing') ? 'current' : '' }}">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-credit-card"></i><span> ادارة التقارير </span><span
                                    class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content" style="display: none;">
                                {{-- <li><a href="{{ route('report/issuing') }}"> تقارير المبيعات </a></li> --}}

                                @php
                                    $controller = app()->make(\App\Http\Controllers\Dashbord\ReportController::class);
                                    $years = $controller->getAvailableYears();
                                @endphp
                                @foreach($years as $year)
                                    <li><a href="{{ route('report/issuing/year', $year) }}"> تقارير المبيعات {{ $year }}</a></li>
                                    <li><a href="{{ route('report/issuing/summary/year', $year) }}"> تقارير المبيعات [مختصر] {{ $year }}</a></li>
                                @endforeach

{{-- <li><a href="{{ route('report/issuing/summary') }}"> تقارير المبيعات [مختصر]</a></li> --}}
{{-- <li><a href="{{ route('report/issuing/summary/archives') }}"> تقارير المبيعات [ارشيف][مختصر]</a></li> --}}

<li><a href="{{ route('report/salescount') }}"> تقارير   اجمالي عدد البطاقات</a></li>
                                <li><a href="{{ route('report/stock') }}"> تقارير المخزون </a></li>
                                <li><a href="{{ route('report/sales') }}"> تقارير اجمالي المبيعات </a></li>
                                <li><a href="{{ route('report/cancelcards') }}"> تقارير البطاقات الملغية </a></li>

                                <li><a href="{{ route('report/requestcompany') }}"> تقارير طلبات شركات التآمين </a></li>
@if (Auth::user()->id == 1)
    <li>
        <a href="{{ route('report/companySummary') }}">
            تقارير مجمع لإصدارات الشركات
        </a>
    </li>
        <li>
        <a href="{{ route('report/officeSummaryByCompany') }}">
            (مكاتب)تقارير مجمع لإصدارات الشركات
        </a>
    </li>
 
@else
    @can('companySummary')
        <li>
            <a href="{{ route('report/companySummary') }}">
                تقارير مجمع لإصدارات الشركات
            </a>
     
        </li>
          <li>
        <a href="{{ route('report/officeSummaryByCompany') }}">
            (مكاتب)تقارير مجمع لإصدارات الشركات
        </a>
    </li>
    @endcan
@endif


                            </ul>
                        </li>
               


                    @can('activity-list')
                        <!--<li class="{{ Request::is('card/update') ? 'current' : '' }} ">-->
                        <!--    <a class="waves-effect " href="{{ route('card/update') }}"><i-->
                        <!--            class="menu-icon fa fa-refresh"></i><span>تحديث البطاقات </span></a>-->
                        <!--</li>-->
                         <li class="{{ Request::is('logs*') ? 'current' : '' }} ">
                            <a class="waves-effect parent-item js__control" href="#"><i
                                    class="menu-icon fa fa-list"></i><span>سجلات النظام</span><span
                                    class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content" style="display: none;">
                                <li><a href="{{ route('logs/activity') }}">سجل النشاط</a></li>
                                <li><a href="{{ route('logs/api') }}">سجل واجهات الربط</a></li>
                                                          <li><a href="{{ route('logs/api') }}">السجلات</a></li>

                            </ul>
                        </li>
                
                    @endcan
                   

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
                <h5 class="name">{{ Auth::user()->username }}</h5>
            </a>
            <div class="ico-item">
                <img src="{{ asset('admin.png') }}" alt="" class="ico-img">
                <ul class="sub-ico-item">
                    <li><a href="{{ route('users/profile', encrypt(Auth::user()->id)) }}"><i class="fa fa-user"></i>
                            {{ trans('app.Profile') }}</a></li>
                    <li><a href="{{ route('users/myactivity') }}"><i class="fa fa-list"></i>
                            سجلاتي</a></li>
                    <li><a href="{{ route('users/ChangePasswordForm', encrypt(Auth::user()->id)) }}"><i
                                class="fa fa-lock"></i>{{ trans('app.changepassword') }} </a></li>
                    <li><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i
                                class="fa fa-sign-out"></i>
                            {{ trans('app.logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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

    {{-- <script src="{{ asset('sweetalert2@11') }}"></script> --}}

    <script src="{{ asset('dash/assets/scripts/main.min.js') }}"></script>

</body>

</html>
