@section('title',"السجلات")

@extends(config('LaravelLogger.loggerBladeExtended'))

@if(config('LaravelLogger.bladePlacement') == 'yield')
@section(config('LaravelLogger.bladePlacementCss'))
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
@push(config('LaravelLogger.bladePlacementCss'))
@endif

@include('LaravelLogger::partials.styles')

@if(config('LaravelLogger.bladePlacement') == 'yield')
@endsection
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
@endpush
@endif

@if(config('LaravelLogger.bladePlacement') == 'yield')
@section(config('LaravelLogger.bladePlacementJs'))
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
@push(config('LaravelLogger.bladePlacementJs'))
@endif

@include('LaravelLogger::partials.scripts', ['activities' => $activities])
@include('LaravelLogger::scripts.confirm-modal', ['formTrigger' => '#confirmDelete'])



@if(config('LaravelLogger.enableDrillDown'))
@include('LaravelLogger::scripts.clickable-row')
@include('LaravelLogger::scripts.tooltip')
@endif

@if(config('LaravelLogger.bladePlacement') == 'yield')
@endsection
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
@endpush
@endif

@section('template_title')
    {{ trans('LaravelLogger::laravel-logger.dashboard.title') }}
@endsection

@php
    switch (config('LaravelLogger.bootstapVersion')) {
        case '4':
        $containerClass = 'card';
        $containerHeaderClass = 'card-header';
        $containerBodyClass = 'card-body';
        break;
        case '3':
        default:
        $containerClass = 'panel panel-default';
        $containerHeaderClass = 'panel-heading';
        $containerBodyClass = 'panel-body';
    }
    $bootstrapCardClasses = (is_null(config('LaravelLogger.bootstrapCardClasses')) ? '' : config('LaravelLogger.bootstrapCardClasses'));
@endphp

@section('content')
<script>
    jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('users/myactivity') }}"></a> عرض سجلاتي</h4>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box-content">
    <div class="container-fluid">

       

       
       @if(config('LaravelLogger.enablePackageFlashMessageBlade'))
       @include('LaravelLogger::partials.form-status')
       @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="{{ $containerClass }} {{ $bootstrapCardClasses }}">
                    <div class="{{ $containerHeaderClass }}" >
                        <div style="margin: 10px;display: flex; justify-content: space-between; align-items: center;" class="">

                            @if(config('LaravelLogger.enableSubMenu'))

                            <span>
                               سجلي
                                <small>
                                    <sup class="label label-default">
                                        {{ $totalActivities }} {!! trans('LaravelLogger::laravel-logger.dashboard.subtitle') !!}
                                    </sup>
                                </small>
                            </span>


                            @else
                            {!! trans('LaravelLogger::laravel-logger.dashboard.title') !!}
                            <span class="pull-right label label-default">
                                {{ $totalActivities }}
                                <span class="hidden-sms">
                                    {!! trans('LaravelLogger::laravel-logger.dashboard.subtitle') !!}
                                </span>
                            </span>
                            @endif

                        </div>
                    </div>
                    <div class="{{ $containerBodyClass }}">
                        @include('LaravelLogger::logger.partials.activity-tablee', ['activities' => $activities, 'hoverable' => true])
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

@if(config('LaravelLogger.enableLiveSearch'))
@include('LaravelLogger::scripts.live-search-script')
@endif

@include('LaravelLogger::modals.confirm-modal', ['formTrigger' => 'confirmDelete', 'modalClass' => 'danger', 'actionBtnIcon' => 'fa-trash-o'])

@endsection
