@extends('layouts.app')
@section('title',trans('users.add'))
@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('users') }}">{{trans('app.users')}}</a>/{{trans('users.add')}}</h4>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
                <div class="row">
                <div class="form-group  col-md-6">
                    <label for="inputName" class="control-label">{{trans('users.username')}}</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" id="username" placeholder="{{trans('users.username')}}" >
                    @error('username')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group  col-md-6">
                    <label for="inputName" class="control-label">الاسم بالكامل</label>
                    <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" value="{{ old('fullname') }}" id="fullname" placeholder="الاسم بالكامل" >
                    @error('fullname')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
            </div>
            <div class="row">
               
                <div class="form-group  col-md-6">
                    <label for="inputEmail" class="control-label">{{trans('users.email')}}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" placeholder="{{trans('users.email')}}" >
                    @error('email')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                    <div class="help-block with-errors"></div>
                </div>
            
                <div class="form-group  col-md-6">
                    <label for="inputName" class="control-label">{{trans('users.phonenumber')}}</label>
                    <input type="text" name="phonenumber" class="form-control @error('phonenumber') is-invalid @enderror" value="{{ old('phonenumber') }}" id="phonenumber" placeholder="{{trans('users.phonenumber')}}" >
                    @error('phonenumber')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
              
            </div>
            <div class="row">
                <div class="form-group  col-md-6">
                    <label for="inputPassword" class="control-label">{{trans('users.password')}}</label>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="password" name="password"data-minlength="8" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" id="password" placeholder="{{trans('users.password')}}" >

                            @error('password')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                            <div class="help-block">{{trans('users.8digitsmini')}} </div>
                        </div>
    
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <label for="inputPassword" class="control-label">تاكيد كلمة المرور</label>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="password" name="password_confirmation"data-minlength="8" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{ old('password_confirmation') }}" id="password_confirmation" placeholder="تاكيد كلمة المرور" >

                            @error('password_confirmation')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        </div>
    
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <label for="inputEmail"
                    class="control-label">الصلاحيات</label>
                <select name="roles_id"
                    class="form-control @error('roles_id') is-invalid @enderror  select2  wd-250"
                    data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                    data-parsley-errors-container="#slErrorContainer" required>
                    <option value=""> اختر الصلاحية</option>

                    @forelse ($roles as $role)
                        <option value="{{ encrypt($role->id) }}"> {{ $role->name }}
                        </option>
                    @empty
                        <option value="">لايوجد صلاحيات</option>
                    @endforelse

                </select>

                @error('roles_id')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                <div class="help-block with-errors"></div>
            </div>
            </div>
           
                <div class="form-group">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{trans('users.addbtn')}}</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection