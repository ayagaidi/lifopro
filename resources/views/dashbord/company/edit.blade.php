@extends('layouts.app')
@section('title', 'تعديل بيانات شركة'))
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
                <h4 class="box-title"><a href="{{ route('company') }}">الشركات</a>/تعديل بيانات شركة</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
                <div class="box-content ">
                    <h4 class="box-title">بيانات الشركة</h4>
                </div>
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="row">
                        <div class="form-group  col-md-12">

                            <div class="profile-picture">
                                <img src="{{ asset('logo/companies/' . $companyy->logo) }}" alt="Profile Image">
                                <input type="file" name="img" id="image-upload" accept="image/*">
                                <label for="image-upload">
                                    <i class="fa fa-camera-alt"></i>
                                    حمل الصورة
                                </label>

                            </div>
                            <div style="text-align: center">
                                @error('img')
                                    <span class="invalid-feedback" style="color: red" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label">الشركة</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ $companyy->name }}" id="name" placeholder="الشركة">
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
                                value="{{ $companyy->phonenumber }}" id="phonenumber"
                                placeholder="{{ trans('users.phonenumber') }}">
                            @error('phonenumber')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label"> رمز الشركة </label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                value="{{ $companyy->code }}" id="code" placeholder=" رمز الشركة ">
                            @error('code')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">


                        <div class="form-group  col-md-4">
                            <label for="inputEmail" class="control-label">{{ trans('users.email') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ $companyy->email }}" id="email" placeholder="{{ trans('users.email') }}">
                            @error('email')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group  col-md-4">
                            <label for="inputEmail" class="control-label">الموقع الالكتروني</label>
                            <input type="text" name="website" class="form-control @error('website') is-invalid @enderror"
                                value="{{ $companyy->website }}" id="website" placeholder="الموقع الالكتروني">
                            @error('website')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label"> مدير الشركة (المندوب) </label>
                            <input type="text" name="fullname_manger"
                                class="form-control @error('fullname_manger') is-invalid @enderror"
                                value="{{ $companyy->fullname_manger }}" id="fullname_manger"
                                placeholder="  مدير الشركة (المندوب)   ">
                            @error('fullname_manger')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group  col-md-4">
                            <label for="inputName" class="control-label"> رقم هاتف المدير (المندوب) </label>
                            <input type="text" name="phonenumber_manger"
                                class="form-control @error('phonenumber_manger') is-invalid @enderror"
                                value="{{ $companyy->phonenumber_manger }}" id="phonenumber_manger"
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
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ $companyy->address }}" id="address" placeholder=" العنوان  ">
                            @error('address')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                    </div>


                    <div class="form-group" style="text-align: left;">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">تعديل</button>
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
