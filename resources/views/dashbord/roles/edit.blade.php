@extends('layouts.app')
@section('title', 'تعديل صلاحية')
@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('roles/index') }}"> الصلاحيات</a>/تعديل صلاحية</h4>
        </div>
        @if (count($errors) > 0)

            <div>

                <strong>عذرًا!</strong>كانت هناك بعض المشاكل في المدخلات الخاصة بك.<br><br>

                <ul>

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

    </div>
</div>

<div class="col-md-12">
    <div class="box-content">
        {!! Form::model($role, ['method' => 'PATCH','route' => ['roles/update', $role->id]]) !!}
        <div class="row">
            <div class="form-group">
                <label for="inputName" class="control-label">الاسم </label>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                <div>


                    <strong>الصلاحيات:</strong>

                    <br />
                    <div class="row">
                            <div class="col-md-12"></div>
                    @foreach($permission as $value)
                    <div class=col-md-3>

                    <label>{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                        {{ trans('app.'.$value->name) }}</label>

</div>
    
                
                @endforeach
                </div>

</div>
                </div>
        </div>
        <button type="submit" class="btn btn-primary waves-effect waves-light">تعديل</button>

        {!! Form::close() !!}

    </div>
</div>

</div>








@endsection


