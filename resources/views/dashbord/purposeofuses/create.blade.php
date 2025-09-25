@extends('layouts.app')
@section('title',"اضافة غرض الاستعمال")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('purposeofuses') }}">غرض الاستعمال</a>/اضافة غرض الاستعمال</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
          
                <div class="form-group">
                    <label for="inputName" class="control-label">التفاصيل</label>
                    <input type="text" name="detail" class="form-control @error('detail') is-invalid @enderror" value="{{ old('detail') }}" id="detail" placeholder="التفاصيل" >
                    @error('detail')
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