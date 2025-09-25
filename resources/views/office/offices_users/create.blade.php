@extends('office.app')
@section('title', 'اضافة   مستخدمين مكتب'))
@section('content')
    

  
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('office/offices_users') }}"> المكاتب </a>/اضافة مستخدم مكتب </h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
               
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                  
                  
                  
                 

                    <div class="row">
                                              
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label">{{trans('users.username')}}</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" id="username" placeholder="{{trans('users.username')}}" >
                            @error('username')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        </div>
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label">الاسم بالكامل</label>
                            <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" value="{{ old('fullname') }}" id="fullname" placeholder="" >
                            @error('fullname')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        </div>
                        <div class="form-group  col-md-4">
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
                        <div class="form-group  col-md-4">
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
                        <div class="form-group  col-md-4" id="userpermisson" >
                            <label for="permisson" class="control-label"> الصلاحيات </label>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    @foreach ($Permisson as $item)
                                        <label>
                                            <input type="checkbox" name="permisson[]" value="{{ $item->id }}" 
                                                class="@error('permisson') is-invalid @enderror"> 
                                            {{ $item->name }}
                                        </label><br>
                                    @endforeach
                        
                                    @error('permisson')
                                        <span class="invalid-feedback" style="color: red" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group col-sm-12">
                                    <select class="form-control @error('permisson') is-invalid @enderror"
                                        name="permisson[]" multiple> 
                                        <option value="">اختر</option>

                                        @foreach ($Permisson as $item)

                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                        
                                    @error('permisson')
                                        <span class="invalid-feedback" style="color: red" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div> --}}
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
