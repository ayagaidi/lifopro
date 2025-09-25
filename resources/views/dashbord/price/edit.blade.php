@extends('layouts.app')
@section('title',"تعديل سعر القسط")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('price') }}">اسعار الاقساط</a>/تعديل بيانات سعر القسط</h4>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box-content row">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label"> القسط اليومي للبند الأول </label>
                    <input type="number" name="installment_daily_1" class="form-control @error('installment_daily_1') is-invalid @enderror" step="0.001" value="{{$Price->installment_daily_1 }}" id="installment_daily_1" placeholder="" >
                    @error('installment_daily_1')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">القسط اليومي للبند الثاني</label>
                    <input type="number" name="installment_daily_2" class="form-control @error('installment_daily_2') is-invalid @enderror" step="0.001" value="{{$Price->installment_daily_2 }}" id="installment_daily_2" placeholder="" >
                    @error('installment_daily_2')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الإشراف</label>
                    <input type="number" name="supervision" class="form-control @error('supervision') is-invalid @enderror" step="0.001" value="{{ $Price->supervision }}" id="supervision" placeholder="" >
                    @error('supervision')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الضريبة</label>
                    <input type="number" name="tax" class="form-control @error('tax') is-invalid @enderror" value="{{ $Price->tax}}" step="0.001" id="tax" placeholder="" >
                    @error('tax')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الإصدار</label>
                    <input type="number" name="version" class="form-control @error('version') is-invalid @enderror" value="{{$Price->version }}" step="0.001" id="version" placeholder="" >
                    @error('version')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">   الدمغة</label>
                    <input type="number" name="stamp" class="form-control @error('stamp') is-invalid @enderror" value="{{$Price->stamp }}" id="stamp"  step="0.001" placeholder="" >
                    @error('stamp')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputName" class="control-label">    معدل الزيادة </label>
                    <input type="number" name="increase" class="form-control @error('increase') is-invalid @enderror" value="{{$Price->increase }}" step="0.001" id="increase" placeholder="" >
                    @error('increase')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <button type="submit" style="margin-top: 34px;" class="btn btn-primary waves-effect waves-light">تعديل</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection