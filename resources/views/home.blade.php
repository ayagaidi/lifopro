@extends('layouts.app')

@section('title', 'الرئيسية')
@section('content')
    <div class="row small-spacing">
          @if( $unionCards<= 2000  )
        <div class="col-md-12" >
                <div class="box-content" style="background-color: red;">
                    <div class="statistics-box with-icon" style="text-align: center;color: white;">
                        <h3 class="counter text-inverse" style="color: white !important;font-size: x-large;">
                            {{ $unionCards }} بطاقة</h3>
                        <p class="text" style="color: white;font-size:large">قاربت الكميه ع النفاذ </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            @else
            
            
            @endif
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('home') }}">الرئيسية</a> </h4>


            </div>
        </div>

        <div class="col-md-12">
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-hospital-o text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $company }} شركة</h2>
                        <p class="text" style="color: #773f2d">الشركات المسجلة </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-users text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $CompanyUser }} مستخدم</h2>
                        <p class="text" style="color: #773f2d">مستخدمين الشركات </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-home text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $Office }} مكتب</h2>
                        <p class="text" style="color: #773f2d">مكتب مسجل </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-users text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $OfficeUser }} مستخدم</h2>
                        <p class="text" style="color: #773f2d">مستخدمين مكاتب </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $cards->all_cards }} بطاقة</h2>
                        <p class="text" style="color: #773f2d">اجمالي البطافات</p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: blue !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                          {{ $cards->companystocksyill }}   بطاقة</h2>
                        <p class="text" style="color: #773f2d">  البطاقات المتبقية</p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color:rgb(17, 140, 111) !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $cards->totalcompanystock }} بطاقة</h2>

                        <p class="text" style="color: #773f2d"> اجمالي بطاقات الشركات  </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color:gray !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $cards->active_cards }} بطاقة</h2>

                        <p class="text" style="color: #773f2d">مخزون الشركات   </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            {{-- <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: orange !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $cards->companystock }} بطاقة</h2>

                        <p class="text" style="color: #773f2d">مخزون الشركات  </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div> --}}
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: green !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $cards->sold }} بطاقة</h2>

                        <p class="text" style="color: #773f2d">البطافات الصادرة </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: red !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important;font-size: x-large;">
                            {{ $cards->cancel }} بطاقة</h2>

                        <p class="text" style="color: #773f2d">البطافات الملغية</p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issuings->today_count }} بطاقة</h2>
                        <p class="text" style="color: #773f2d">بطاقات المصدرة (هذا اليوم)</p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issuings->month_count }} بطاقة</h2>
                        <p class="text" style="color: #773f2d">بطاقات المصدرة (هذا الشهر)</p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issuings->total }} بطاقة</h2>
                        <p class="text" style="color: #773f2d">بطاقات المصدرة (الكلي )</p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-money text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issuings->today_sum}}دينار
                        </h2>
                        <p class="text" style="color: #773f2d;font-size: small;">اجمالي قيمة البطاقة المصدرة (هذا اليوم)
                        </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-money text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issuings->month_sum}} دينار
                        </h2>
                        <p class="text" style="color: #773f2d;font-size: small;">اجمالي قيمة البطاقة المصدرة (هذا الشهر)
                        </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="box-content">
                    <div class="statistics-box with-icon">
                        <i class="ico fa fa-money text-inverse " style="color: #aa5940 !important;"></i>
                        <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issuings->total_sum }} دينار</h2>
                        <p class="text" style="color: #773f2d;font-size: small;"> اجمالي قيمة البطاقة المصدرة (الكلي )
                        </p>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
           

        </div>
       

    </div>

    </div>
@endsection
