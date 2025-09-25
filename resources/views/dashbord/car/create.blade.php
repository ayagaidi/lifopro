@extends('layouts.app')
@section('title',"اضافة سيارة")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('car') }}">السيارات</a>/اضافة سيارة</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('city.name')}}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" id="name" placeholder="{{trans('city.name')}}" >
                    @error('name')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">الرمز</label>
                    <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol') }}" id="symbol" placeholder="الرمز" >
                    @error('symbol')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{trans('city.addbtn')}}</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection