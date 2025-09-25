@extends('layouts.app')
@section('title', 'اضافة   مستخدمين مكتب'))
@section('content')
    

  
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('offices_users',$officeid) }}"> المكاتب </a>/اضافةمستخدمين مكتب {{$Office->name}} </h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
               
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                  
                  
                  
                 

                    <div class="row">
                                              
                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">{{trans('users.username')}}</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" id="username" placeholder="{{trans('users.username')}}" >
                            @error('username')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        </div>
                        <div class="form-group  col-md-3">
                            <label for="inputEmail" class="control-label">{{trans('users.user_types')}}</label>
                            <select  name="user_type_id" class="form-control @error('user_type_id') is-invalid @enderror  select2  wd-250"  data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                                <option value="">اختر</option>

                                @forelse ($user_types as $user_type)
                                <option value="{{$user_type->id}}"> {{$user_type->slug}}</option>
                                @empty
                                <option value=""{{trans('users.user_types')}}></option>
                                @endforelse
                            </select>
        
                                @error('user_type_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group  col-md-3">
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
                        <div class="form-group  col-md-3">
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
                    </div>
                    <div class="form-group" style="text-align: left">
                        <button type="submit"
                            class="btn btn-primary waves-effect waves-light">{{ trans('users.addbtn') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
   
@endsection
