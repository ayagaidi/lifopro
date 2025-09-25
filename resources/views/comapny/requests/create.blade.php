@extends('comapny.app')
@section('title',"اضافة طلب")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('company/cardrequests') }}">الطلبات</a>/اضافة طلب</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group">
                    <input type="text" readonly name="name" class="form-control @error('name') is-invalid @enderror" value="{{Auth::user()->companies->name}}" id="name" placeholder="{{trans('city.name')}}" >
                    @error('name')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">عدد البطاقات</label>
                    <input type="number"  max="5000" name="cards_number" class="form-control @error('cards_number') is-invalid @enderror" value="{{ old('cards_number') }}" id="cards_number" placeholder="عدد البطاقات" >
                    @error('cards_number')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">طلب</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('form').on('submit', function(event) {
            // Show the loader
            $('#loader-overlay').show();

           

        });
    });
</script>
@endsection