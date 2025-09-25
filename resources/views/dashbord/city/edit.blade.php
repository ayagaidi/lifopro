@extends('layouts.app')
@section('title',trans('city.edit'))

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('cities') }}">{{trans('app.city')}}</a>/{{trans('city.edit')}}</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group"  >
                    <label for="inputName" class="control-label">المنطقة</label>
                    <select name="regions_id" class="form-control @error('regions_id') is-invalid @enderror  select2  wd-250"  data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>

                        @forelse ($regions as $region)
                        {{-- <option value="{{$region->id}} " {{$region->id == $city->regions_id  ? 'selected' : ''}}> {{$region->name}}</option> --}}
                        <option value="{{$region->id}}"> {{$region->name}}</option>

                        @empty
                        <option value="">لايوجد مناطق</option>
                        @endforelse
                    </select>
                    @error('regions_id')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('city.name')}}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $city->name }}" id="name" placeholder="{{trans('city.name')}}" >
                    @error('name')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
             
                <div class="form-group">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{trans('city.editbtn')}}</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection