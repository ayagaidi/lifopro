@extends('layouts.app')

@section('title', 'عرض صلاحية')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('roles/index') }}"> الصلاحيات</a>/عرض صلاحية {{ $role->name }}
                </h4>
            </div>

            @if ($message = Session::get('success'))
                <div>

                    <p>{{ $message }}</p>

                </div>
            @endif
        </div>



        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">



                    <strong>الاسم:</strong>

                    {{ $role->name }}

                </div>


                <br />



                <div class="row">
                    <div class="col-md-12">
                        @if (!empty($rolePermissions))
                            @foreach ($rolePermissions as $v)
                                <div class=col-md-3>
                                    <label>

                                        -{{ trans('app.'.$v->name) }}</label>

                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>
    </div>
@endsection
