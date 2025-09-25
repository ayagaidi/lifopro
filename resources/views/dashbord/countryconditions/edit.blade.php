@extends('layouts.app')
@section('title', 'تعديل بيانات شرط دولة')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('countryconditions') }}">شروط الدول</a>/تعديل بيانات شرط دولة</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
                <form method="POST" class="" action="">
                    @csrf
                    <div class="form-group">
                        <label for="inputName" class="control-label">الدولة</label>
                        <select type="text" name="countries_id"
                            class="form-control @error('countries_id') is-invalid @enderror"id="countries_id">
                            @forelse ($Contry as $item)
                            <option value="{{$item->id}}" {{$item->id == $CountrycCondition->countries_id  ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                         
                            {{-- <option value="">
                                اختر الدولة

                            </option>
                            @if (count($Contry) > 0)
                                @foreach ($Contry as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    </option>
                                @endforeach
                            @else
                                <option value="">
                                    لايوجد دول حتي الان

                                </option>
                            @endif --}}
                        </select>
                        @error('countries_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="inputName" class="control-label"> عنوان المكتب الموحد
                        </label>
                        <textarea type="text" name="unifiedofficaddress"
                            class="form-control @error('unifiedofficaddress') is-invalid @enderror" value="" id="unifiedofficaddress"
                            placeholder="">{{ $CountrycCondition->unifiedofficaddress }}</textarea>
                        @error('unifiedofficaddress')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="control-label"> بيان نوع التغطية

                        </label>
                        <textarea type="text" name="statementypecoverage"
                            class="form-control @error('statementypecoverage') is-invalid @enderror" id="statementypecoverage" placeholder="">{{$CountrycCondition->statementypecoverage}}</textarea>
                        @error('statementypecoverage')
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
