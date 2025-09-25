@extends('layouts.app')
@section('title', 'اضافة مكتب'))
@section('content')
    <style>
        .profile-picture {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Scales image to fit container */
        }

        .profile-picture input[type="file"] {
            display: none;
            /* Hide the default file input */
        }

        .profile-picture label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            opacity: 0;
            /* Hide label initially */
            transition: opacity 0.3s ease-in-out;
        }

        .profile-picture:hover label {
            opacity: 1;
            /* Show label on hover */
        }

        .profile-picture label i {
            font-size: 2em;
            margin-right: 10px;
        }
    </style>

    <script>
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    </script>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('offices') }}">المكاتب</a>/اضافة مكتب</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
                <div class="box-content ">
                    <h4 class="box-title">بيانات مكتب</h4>
                </div>
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="row">
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label">الشركة</label>
                            <select name="companies_id" id="companies_id"
                                class="form-control @error('companies_id') is-invalid @enderror  select2  wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer" required>
                                <option value="">اختر الشركة </option>

                                @forelse ($Company as $Compan)
                                    <option value="{{ $Compan->id }}"> {{ $Compan->name }}</option>
                                @empty
                                    <option value="">لايوجد الشركة</option>
                                @endforelse
                            </select>
                            @error('companies_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label">اسم المكتب</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" id="name" placeholder="اسم المكتب">
                            @error('name')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label">{{ trans('users.phonenumber') }}</label>
                            <input type="text" name="phonenumber"
                                class="form-control @error('phonenumber') is-invalid @enderror"
                                value="{{ old('phonenumber') }}" id="phonenumber"
                                placeholder="{{ trans('users.phonenumber') }}">
                            @error('phonenumber')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                 
                        

                        {{-- <div class="form-group  col-md-4">
                            <label for="inputEmail" class="control-label">{{ trans('users.email') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" id="email" placeholder="{{ trans('users.email') }}">
                            @error('email')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                            <div class="help-block with-errors"></div>
                        </div> --}}

                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label"> اسم مدير مكتب (المندوب) </label>
                            <input type="text" name="fullname_manger"
                                class="form-control @error('fullname_manger') is-invalid @enderror"
                                value="{{ old('fullname_manger') }}" id="fullname_manger"
                                placeholder="  اسم مدير مكتب (المندوب)   ">
                            @error('fullname_manger')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                
                       
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label">  رقم هاتف المدير (المندوب)  </label>
                            <input type="text" name="phonenumber_manger"
                                class="form-control @error('phonenumber_manger') is-invalid @enderror"
                                value="{{ old('phonenumber_manger') }}" id="phonenumber_manger"
                                placeholder="   رقم هاتف المدير (المندوب) ">
                            @error('phonenumber_manger')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>


                    </div>
                    <div class="row">
                       
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label"> العنوان </label>
                            <input type="text" name="address"
                                class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}"
                                id="address" placeholder=" العنوان  ">
                            @error('address')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                      
                    </div>

                    <div class="row">
                        <div class="box-content ">
                            <h4 class="box-title">بيانات  ادمن المكتب</h4>
                        </div>                       
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
                    </div>
                    <div class="form-group">
                        <button type="submit"
                            class="btn btn-primary waves-effect waves-light">{{ trans('users.addbtn') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <script>
        const img = document.querySelector('.profile-picture img');
        const input = document.querySelector('#image-upload');

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                img.src = reader.result;
            };

            reader.readAsDataURL(file);
        });
    </script>
@endsection
