@extends('layouts.app')
@section('title',"اضافة سعر")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('price') }}">اسعار الاقساط</a>/اضافة الاسعار</h4>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box-content row">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label"> القسط اليومي للبند الأول </label>
                    <input type="number" name="installment_daily_1"  step="0.001"  class="form-control @error('installment_daily_1') is-invalid @enderror" value="{{ old('installment_daily_1') }}" id="installment_daily_1" placeholder="" >
                    @error('installment_daily_1')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">القسط اليومي للبند الثاني</label>
                    <input type="number" name="installment_daily_2"  step="0.001"  class="form-control @error('installment_daily_2') is-invalid @enderror" value="{{ old('installment_daily_2') }}" id="installment_daily_2" placeholder="" >
                    @error('installment_daily_2')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الإشراف</label>
                    <input type="number" name="supervision"  step="0.001"  class="form-control @error('supervision') is-invalid @enderror" value="{{ old('supervision') }}" id="supervision" placeholder="" >
                    @error('supervision')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الضريبة</label>
                    <input type="number" name="tax"  step="0.001"  class="form-control @error('tax') is-invalid @enderror" value="{{ old('tax') }}" id="tax" placeholder="" >
                    @error('tax')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الإصدار</label>
                    <input type="number" name="version"  step="0.001"  class="form-control @error('version') is-invalid @enderror" value="{{ old('version') }}" id="version" placeholder="" >
                    @error('version')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الدمغة</label>
                    <input type="number" name="stamp"   step="0.001" class="form-control @error('stamp') is-invalid @enderror" value="{{ old('stamp') }}" id="stamp" placeholder="" >
                    @error('stamp')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">    معدل الزيادة </label>
                    <input type="number" name="increase"  step="0.001"  class="form-control @error('increase') is-invalid @enderror" value="{{ old('increase') }}" id="increase" placeholder="" >
                    @error('increase')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <button type="submit" style="margin-top: 34px;" class="btn btn-primary waves-effect waves-light">{{trans('city.addbtn')}}</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection