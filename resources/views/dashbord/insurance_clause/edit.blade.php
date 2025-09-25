@extends('layouts.app')
@section('title', 'تعديل بيانات بند التامين')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('insurance_clause') }}"> بند التامين</a>/تعديل بيانات بند التامين</h4>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box-content">
                <form method="POST" class="" action="">
                    @csrf
                    <div class="form-group">
                        <label for="inputName" class="control-label">النوع</label>
                        <select type="text" name="type" class="form-control @error('type') is-invalid @enderror"
                            value="{{ old('type') }}" id="type" placeholder="{{ trans('city.name') }}">
                      @if($InsuranceClause->type=="PV")
                            <option value="PV" selected>PV </option>
                            <option value="CV">CV </option>

                            @endif
                            @if($InsuranceClause->type=="CV")
                            <option value="PV" >PV </option>
                            <option value="CV" selected>CV </option>

                            @endif
                            {{-- 1 --}}
                            {{-- 2 --}}
                        </select>

                        @error('type')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="control-label">وصف</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                            value="{{$InsuranceClause->slug}}" id="slug" placeholder="الوصف">
                        @error('slug')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
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
