@extends('layouts.app')
@section('title', 'عرض صلاحية')

@section('content')
    <div class="content-wrapper box-content">
        <div class="content-header row">
            <div class="content-header col-md-12 col-12 ">
                <h3 class="content-header-title mb-1">عرض صلاحية</h3>
                <div class="row breadcrumbs-top mb-1">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('roles/index') }}">الصلاحيات</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('roles/show', $role->id) }}">عرض صلاحية
                                    {{ $role->name }}</a>
                            </li>

                        </ol>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-header row">
            <div class="content-detached content-left">
                <section class="row">
                    <div class="col-md-12">
                        <div class="card">
                            @if ($message = Session::get('success'))
                                <div>

                                    <p>{{ $message }}</p>

                                </div>
                            @endif
                            <!-- project-info -->



                            <div class="row" style="margin: 10px;">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">

                                        <label for="inputName" class="control-label">اسم الصلاحية :</label>

                                        {{ $role->name }}

                                    </div>

                                </div>
                            </div>

                            <div class="row" style="margin: 10px;">



                                @if (!empty($rolePermissions))
                                    @foreach ($rolePermissions as $v)
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">

                                                    <li>

                                                       *- {{ trans('app.' . $v->name) }}</li>

                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @endif




                            </div>
                        </div>
                    </div>

                </section>


            </div>

        </div>
    </div>
    @endsection
