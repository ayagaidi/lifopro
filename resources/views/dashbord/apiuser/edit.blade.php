@extends('layouts.app')
@section('title',"تعديل حساب API")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('apiuser') }}">حساباتAPI</a>/تعديل حساب API</h4>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box-content row">
            <form method="POST" class="" action="">
                @csrf
                    <div class="form-group col-md-6  ">
                        <label for="inputName" class="control-label">الشركة</label>
                        <select readonly name="companies_id" id="companies_id"
                            class="form-control @error('companies_id') is-invalid @enderror  select2  wd-250"
                            data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                            data-parsley-errors-container="#slErrorContainer" required>

                                <option value="{{ $Apiuser->companies->id }}"> {{ $Apiuser->companies->name }}</option>

                               
                        </select>
                        @error('companies_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                  
                   
                    
                <div class="form-group col-md-6">
                    <label for="inputName" class="control-label"> إسم المستخدم </label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ $Apiuser->username }}" id="username" placeholder=" إسم المستخدم " >
                    @error('username')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group  col-md-6 ">
                    <label for="inputPassword" class="control-label">{{trans('users.password')}}</label>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="password" name="password"data-minlength="8" class="form-control @error('password') is-invalid @enderror" value="{{decrypt($Apiuser->password)}}" id="password" placeholder="{{trans('users.password')}}" >

                            @error('password')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                            <div class="help-block">6 احرف او ارقام على الأقل  </div>
                        </div>
    
                    </div>
                </div>
                <div class="form-group col-md-6  ">
                    <label for="inputPassword" class="control-label">تاكيد كلمة المرور</label>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="password" name="password_confirmation"data-minlength="8" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{decrypt($Apiuser->password)}}" id="password_confirmation" placeholder="تاكيد كلمة المرور" >

                            @error('password_confirmation')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        </div>
    
                    </div>
                </div>
                <div class="form-group col-md-12 " style="text-align: left">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">تعديل</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection