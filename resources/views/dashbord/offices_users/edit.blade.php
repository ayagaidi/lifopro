@extends('layouts.app')
@section('title', 'تعديل  مستخدمين مكتب'))
@section('content')
    

  
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('offices_users',$officesUser->offices_id) }}"> مستخدمين مكاتب  </a>/تعديل مستخدم مكتب {{$officesUser->name}} </h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
               
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                  
                  
                  
                 

                    <div class="row">
                                              
                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">{{trans('users.username')}}</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ $officesUser->username }}" id="username" placeholder="{{trans('users.username')}}" >
                            @error('username')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        </div>
                        <div class="form-group  col-md-3">
                            <label for="inputEmail" class="control-label">{{trans('users.user_types')}}</label>
                            <select  name="user_type_id" class="form-control @error('user_type_id') is-invalid @enderror  select2  wd-250"  data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>

                                @forelse ($user_types as $user_type)
                                <option value="{{ $user_type->id }}" {{$user_type->id == $officesUser->user_type_id  ? 'selected' : ''}}> {{ $user_type->slug }}</option>

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
                       
                    </div>
                    <div class="form-group" style="text-align: left">
                        <button type="submit"
                            class="btn btn-primary waves-effect waves-light">تعديل</button>
                    </div>
                </form>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
   
@endsection
