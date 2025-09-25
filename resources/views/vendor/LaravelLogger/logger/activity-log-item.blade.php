@section('title', 'تفاصيل سجل')

@php
    $userIdField = config('LaravelLogger.defaultUserIDField');
@endphp

@extends(config('LaravelLogger.loggerBladeExtended'))

@if (config('LaravelLogger.bladePlacement') == 'yield')
    @section(config('LaravelLogger.bladePlacementCss'))
    @elseif (config('LaravelLogger.bladePlacement') == 'stack')
        @push(config('LaravelLogger.bladePlacementCss'))
        @endif

        @include('LaravelLogger::partials.styles')

        @if (config('LaravelLogger.bladePlacement') == 'yield')
        @endsection
    @elseif (config('LaravelLogger.bladePlacement') == 'stack')
    @endpush
@endif

@if (config('LaravelLogger.bladePlacement') == 'yield')
    @section(config('LaravelLogger.bladePlacementJs'))
    @elseif (config('LaravelLogger.bladePlacement') == 'stack')
        @push(config('LaravelLogger.bladePlacementJs'))
        @endif

        @include('LaravelLogger::partials.scripts', ['activities' => $userActivities])

        @if (config('LaravelLogger.bladePlacement') == 'yield')
        @endsection
    @elseif (config('LaravelLogger.bladePlacement') == 'stack')
    @endpush
@endif

@section('template_title')
    {{ trans('LaravelLogger::laravel-logger.drilldown.title', ['id' => $activity->id]) }}
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
    $bootstrapCardClasses = is_null(config('LaravelLogger.bootstrapCardClasses')) ? '' : config('LaravelLogger.bootstrapCardClasses');
    
    switch ($activity->userType) {
        case trans('LaravelLogger::laravel-logger.userTypes.registered'):
            $userTypeClass = 'success';
            break;
    
        case trans('LaravelLogger::laravel-logger.userTypes.crawler'):
            $userTypeClass = 'danger';
            break;
    
        case trans('LaravelLogger::laravel-logger.userTypes.guest'):
        default:
            $userTypeClass = 'warning';
            break;
    }
    
    switch (strtolower($activity->methodType)) {
        case 'get':
            $methodClass = 'info';
            break;
    
        case 'post':
            $methodClass = 'primary';
            break;
    
        case 'put':
            $methodClass = 'caution';
            break;
    
        case 'delete':
            $methodClass = 'danger';
            break;
    
        default:
            $methodClass = 'info';
            break;
    }
    
    $platform = $userAgentDetails['platform'];
    $browser = $userAgentDetails['browser'];
    $browserVersion = $userAgentDetails['version'];
    
    switch ($platform) {
        case 'Windows':
            $platformIcon = 'fa-windows';
            break;
    
        case 'iPad':
            $platformIcon = 'fa-';
            break;
    
        case 'iPhone':
            $platformIcon = 'fa-';
            break;
    
        case 'Macintosh':
            $platformIcon = 'fa-apple';
            break;
    
        case 'Android':
            $platformIcon = 'fa-android';
            break;
    
        case 'BlackBerry':
            $platformIcon = 'fa-';
            break;
    
        case 'Unix':
        case 'Linux':
            $platformIcon = 'fa-linux';
            break;
    
        case 'CrOS':
            $platformIcon = 'fa-chrome';
            break;
    
        default:
            $platformIcon = 'fa-';
            break;
    }
    
    switch ($browser) {
        case 'Chrome':
            $browserIcon = 'fa-chrome';
            break;
    
        case 'Firefox':
            $browserIcon = 'fa-';
            break;
    
        case 'Opera':
            $browserIcon = 'fa-opera';
            break;
    
        case 'Safari':
            $browserIcon = 'fa-safari';
            break;
    
        case 'Internet Explorer':
            $browserIcon = 'fa-edge';
            break;
    
        default:
            $browserIcon = 'fa-';
            break;
    }
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
            <div class="box-content">

                <div class="{{ $containerClass }} @if ($isClearedEntry) panel-danger @else panel-default @endif">
                    <div
                        class="{{ $containerHeaderClass }} @if ($isClearedEntry) bg-danger text-white @else @endif">

                        <h4 class="box-title"><a href="{{ route('activity') }}">السجلات</a>/ {!! trans('LaravelLogger::laravel-logger.drilldown.title', ['id' => $activity->id]) !!} ID</h4>
                    </div>
                </div>
            </div>
            <div class="row small-spacing">
                <div class="container-fluid">

                    @if (config('LaravelLogger.enablePackageFlashMessageBlade'))
                        @include('LaravelLogger::partials.form-status')
                    @endif

                    <div class="panel @if ($isClearedEntry) panel-danger @else panel-default @endif">

                        <div class="{{ $containerBodyClass }}">
                            <div class="row">
                                <div class="col-xs-12 col-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="box-content ">
                                                <label
                                                    class="list-group-item @if ($isClearedEntry) list-group-item-danger @else active @endif">
                                                    {!! trans('LaravelLogger::laravel-logger.drilldown.title-details') !!}
                                                </label>
                                                <br />

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.id') !!}
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $activity->id }}
                                                    </div>

                                                </div>
                                                <br />

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.description') !!} </div>
                                                    <div class="col-md-7">
                                                        {{ $activity->description }}
                                                    </div>

                                                </div>
                                                <br />

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.details') !!}

                                                    </div>
                                                    <div class="col-md-7">

                                                        @if ($activity->details)
                                                            {{ $activity->details }}@else{!! trans('LaravelLogger::laravel-logger.drilldown.list-group.fields.none') !!}
                                                        @endif
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.agent') !!}
                                                    </div>
                                                    <div class="col-md-7">

                                                        <i class="fa {{ $platformIcon }} fa-fw" aria-hidden="true">
                                                            <span class="sr-only">
                                                                {{ $platform }}
                                                            </span>
                                                        </i>
                                                        <i class="fa {{ $browserIcon }} fa-fw" aria-hidden="true">
                                                            <span class="sr-only">
                                                                {{ $browser }}
                                                            </span>
                                                        </i>
                                                        <sup>
                                                            <small>
                                                                {{ $browserVersion }}
                                                            </small>
                                                        </sup>
                                                    </div>
                                                </div>
                                                <br />

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.referer') !!}
                                                    </div>
                                                    <div class="col-md-7">

                                                        <a href="{{ $activity->referer }}">
                                                            {{ $activity->referer }}
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.timePassed') !!}
                                                    </div>
                                                    <div class="col-md-7">

                                                        {{ $timePassed }}
                                                    </div>
                                                </div>
                                                <br />

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.createdAt') !!} </div>
                                                    <div class="col-md-7">

                                                        {{ $activity->created_at }}
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        {{--  --}}
                                        <div class="col-md-4">
                                            <div class="box-content ">
                                                <label c
                                                    class="list-group-item @if ($isClearedEntry) list-group-item-danger @else active @endif">
                                                    {!! trans('LaravelLogger::laravel-logger.drilldown.title-ip-details') !!}
                                                </label>
                                                <br />

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.ip') !!}
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $activity->ipAddress }}

                                                    </div>
                                                    <br />
                                                    @if ($ipAddressDetails)
                                                        @foreach ($ipAddressDetails as $ipAddressDetailKey => $ipAddressDetailValue)
                                                            <dt>{{ $ipAddressDetailKey }}</dt>
                                                            <dd>{{ $ipAddressDetailValue }}</dd>
                                                        @endforeach
                                                    @else
                                                        <p class="text-center disabled">
                                                            <br />
                                                            بيانات عنوان IP الإضافية غير متوفرة.
                                                        </p>
                                                    @endif
                                                </div>
                                                <br />




                                            </div>
                                        </div>
                                        {{--  --}}
                                        <div class="col-md-4">
                                            <div class="box-content ">
                                                <label
                                                    class="list-group-item @if ($isClearedEntry) list-group-item-danger @else active @endif">
                                                    {!! trans('LaravelLogger::laravel-logger.drilldown.title-user-details') !!}
                                                </label>
                                                <br />

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userType') !!} </div>
                                                    <div class="col-md-7">
                                                        <span class="badge badge-{{ $userTypeClass }}">
                                                            {{ $activity->userType }}
                                                        </span>
                                                    </div>

                                                </div>
                                                <br />
                                                @if ($userDetails)
                                                    <br>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userId') !!}

                                                        </div>
                                                        <div class="col-md-7">
                                                            <span class="badge badge-{{ $userTypeClass }}">
                                                                {{ $userDetails->$userIdField }}
                                                            </span>
                                                        </div>
                                                    </div>
                                            
                                            @if (config('LaravelLogger.rolesEnabled'))
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.labels.userRoles') !!}
                                                    </div>
                                                    @foreach ($userDetails->roles as $user_role)
                                                        @if ($user_role->name == 'User')
                                                            @php $labelClass = 'primary' @endphp
                                                        @elseif ($user_role->name == 'Admin')
                                                            @php $labelClass = 'warning' @endphp
                                                        @elseif ($user_role->name == 'Unverified')
                                                            @php $labelClass = 'danger' @endphp
                                                        @else
                                                            @php $labelClass = 'default' @endphp
                                                        @endif
                                                        <div class="col-md-7">
                                                            <span class="badge badge-{{ $labelClass }}">
                                                                {{ $user_role->username }} -
                                                                {!! trans('LaravelLogger::laravel-logger.drilldown.labels.userLevel') !!}
                                                                {{ $user_role->level }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                            @endif
                                            <br>

                                          
                                            <br>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userName') !!}
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $userDetails->username }}
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userEmail') !!} </div>
                                                <div class="col-md-7">
                                                    <a href="mailto:{{ $userDetails->email }}">
                                                        {{ $userDetails->email }}
                                                    </a>
                                                </div>
                                            </div>
                                            <br>
                                            @if ($userDetails->last_name || $userDetails->first_name)
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userFulltName') !!} </div>
                                                    <div class="col-md-7">
                                                        {{ $userDetails->last_name }},
                                                        {{ $userDetails->first_name }}
                                                    </div>
                                                </div>
                                            @endif
                                            <br />
                                            @if ($userDetails->signup_ip_address)
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userSignupIp') !!} </div>
                                                    <div class="col-md-7">
                                                        {{ $userDetails->signup_ip_address }},

                                                    </div>
                                                </div>
                                            @endif
                                            <br />
                                            <div class="row">
                                                <div class="col-md-5">
                                                    {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userCreatedAt') !!}
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $userDetails->created_at }}

                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        {!! trans('LaravelLogger::laravel-logger.drilldown.list-group.labels.userUpdatedAt') !!}
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $userDetails->updated_at }}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @if (!$isClearedEntry)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-content">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-info" style="background-color: #aa5940;color: white">
                                        {!! trans('LaravelLogger::laravel-logger.drilldown.title-user-activity') !!}
                                        <span class="badge"  style="color: white">
                                            {{ $totalUserActivities }} {!! trans('LaravelLogger::laravel-logger.dashboard.subtitle') !!}
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        @include('LaravelLogger::logger.partials.activity-table', [
                                            'activities' => $userActivities,
                                        ])
                                    </li>
                                </ul>
                                <br />
                            </div>
                                </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>


    </div>
    
@endsection
