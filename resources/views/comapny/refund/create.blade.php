@extends('comapny.app')
@section('title', 'اضافة عملية راجعة'))
@section('content')
  


    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('company/refund') }}">ادارة الراجعات</a>/اضافة عملية راجعة</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
               
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="row">
                       
                        <div class="form-group col-md-4">
                            <label for="offices_id" class="control-label">المكتب</label>
                            <select name="offices_id" class="form-control @error('offices_id') is-invalid @enderror" id="offices_id">
                                <option value="">اختر المكتب</option>
                                @forelse ($Offices as $Office)
                                    <option value="{{ $Office->id }}">{{ $Office->name }}</option>
                                @empty
                                    <option value="">لاتوجد مكاتب</option>
                                @endforelse
                            </select>
                            @error('offices_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                      
                        
                  
                        

        
                      

                   
                    <div class="form-group   col-md-4" style="margin-top: 28px;">
                        <button type="submit"
                            class="btn btn-primary waves-effect waves-light">ارجاع</button>
                    </div>

                    </div>
                </form>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

@endsection
