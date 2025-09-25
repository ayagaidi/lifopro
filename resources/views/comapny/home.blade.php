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
        

    </div>
</div>
@endsection
