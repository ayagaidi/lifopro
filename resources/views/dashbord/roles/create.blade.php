@extends('layouts.app')
@section('title', 'اضافة صلاحية')

@section('content')
    <div class="content-wrapper">
        <div class="box-content">

        <div class="content-header row">
            <div class="content-header col-md-12 col-12 ">
                <h3 class="content-header-title mb-1">اضافة صلاحية</h3>
                <div class="row breadcrumbs-top mb-1">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('roles/index') }}">الصلاحيات</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('roles/create') }}">اضافة صلاحية </a>
                            </li>

                        </ol>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-detached content-left">
            <section class="row">
                <div class="col-md-12">
                    <div class="card">

                        <!-- project-info -->
                        <div id="project-info" class="card-body row">
                            {!! Form::open(['route' => 'roles/store', 'method' => 'POST', 'style' => 'width: 100%;']) !!}
                            @csrf
       
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="inputName" class="control-label">اسم الصلاحية </label>
                                            {!! Form::text('name', null, ['placeholder' => 'اسم الصلاحية', 'class' => 'form-control']) !!}
                                            @error('Name')
                                                <span class="invalid-feedback" style="color: red" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            
                                            <div class="help-block"></div>
                                            @if (count($errors) > 0)

                                            <div>
            
                                                <strong style="color: red">عذرًا!</strong>كانت هناك بعض المشاكل في المدخلات الخاصة بك.<br><br>
            
                                                <ul>
            
                                                    @foreach ($errors->all() as $error)
                                                        <li style="color: red">{{ $error }}</li>
                                                    @endforeach
            
                                                </ul>
            
                                            </div>
            
                                        @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($permission as $value)
                                   

                                      
                                            <div class=col-md-3>
                                                <label>{{ Form::checkbox('permission[]', $value->name, false, ['class' => 'name']) }}

                                                    {{ trans('app.' . $value->name) }}</label>
                                            </div>
                                        
                                    @endforeach

                                    <div class="col-12 " style="text-align: left;">
                                        <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">
                                            اضافة</button>
                                        <button type="reset" class="btn btn-light">الغاء</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
            </section>


        </div>
        </div>

    </div>

@endsection
