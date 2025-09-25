@extends('comapny.app')
@section('title',"الرئيسية")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">

            <h4 class="box-title"><a href="{{ route('company/home') }}">الرئيسية</a> </h4>


        </div>
    </div>

    <div class="col-md-12">
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important"> @if($cardsstock){{ $cardsstock->total_cards }} @else 0  @endif بطاقة</h2>
                    <p class="text" style="color: #773f2d">اجمالي البطافات</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>  
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-file text-inverse " style="color:gray !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important"> @if($cardsstock){{ $cardsstock->active_cards }} @else 0  @endif بطاقة</h2>

                    <p class="text" style="color: #773f2d"> معينة(مخزون الشركة)</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div> 
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-file text-inverse " style="color: green !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important"> @if($cardsstock){{ $cardsstock->sold }} @else 0  @endif بطاقة</h2>

                    <p class="text" style="color: #773f2d">اجمالي المصدرة</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div> 
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-file text-inverse " style="color: red !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important"> @if($cardsstock){{ $cardsstock->cancel }} @else 0  @endif بطاقة</h2>

                    <p class="text" style="color: #773f2d">اجمالي الملغية</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issiungtoday }} بطاقة</h2>
                    <p class="text" style="color: #773f2d">بطاقات المصدرة (هذا اليوم)</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issiungmonth }} بطاقة</h2>
                    <p class="text" style="color: #773f2d">بطاقات المصدرة (هذا الشهر)</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-file text-inverse " style="color: #aa5940 !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issiung }} بطاقة</h2>
                    <p class="text" style="color: #773f2d">بطاقات المصدرة (الكلي )</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-money text-inverse " style="color: #aa5940 !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issiungtodaysum }} دينار</h2>
                    <p class="text" style="color: #773f2d;font-size: small;">اجمالي قيمة البطاقة  المصدرة (هذا اليوم)</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-money text-inverse " style="color: #aa5940 !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issiungmonthsum }} دينار</h2>
                    <p class="text" style="color: #773f2d;font-size: small;">اجمالي قيمة البطاقة المصدرة (هذا الشهر)</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box-content">
                <div class="statistics-box with-icon">
                    <i class="ico fa fa-money text-inverse " style="color: #aa5940 !important;"></i>
                    <h3 class="counter text-inverse" style="color: #aa5940 !important">{{ $issiungsum }} دينار</h2>
                    <p class="text" style="color: #773f2d;font-size: small;"> اجمالي قيمة البطاقة  المصدرة (الكلي )</p>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        {{-- <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="box-content">
                <h4 class="counter text-inverse" style="color:#aa5940 !important">
                    {{ $issiungchart->options['chart_title'] }} [هذا الشهر]</h4>

                {!! $issiungchart->renderHtml() !!}
                {!! $issiungchart->renderChartJsLibrary() !!}
                {!! $issiungchart->renderJs() !!}
            </div>
            <!-- /.box-content -->
        </div>

        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="box-content">
                <h4 class="counter text-inverse" style="color:#aa5940 !important">
                    {{ $sumchartmonth->options['chart_title'] }} [هذا الشهر]</h4>

                {!! $sumchartmonth->renderHtml() !!}
                {!! $sumchartmonth->renderChartJsLibrary() !!}
                {!! $sumchartmonth->renderJs() !!}
            </div>
            <!-- /.box-content -->
        </div> --}}

    </div>
</div>
@endsection
