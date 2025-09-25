@extends('comapny.app')
@section('title', 'عرض وثيقة')

@section('content')

    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">


                <h2 style="color: green;text-align: center;font-weight: bold;">تمت عملية اصدار بوليصة بنجاح </h2>
                <a type="button" href="{{route('company/viewdocument',$card_id)}}" target="_blank"
                class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 " style="float: left;border-color: orange;"> <img  style="max-width: 10%;" src="{{asset('maximize.png')}}">  عرض البوليصة     </a>
   
                <a></a>
            </div>
        </div>
        <div class="col-md-12">

            <div class="box-content">
                <iframe src="{{ $file }}" style="width: 100%;height:100vh" frameborder="0"></iframe>
            </div>
            <!-- /.box-content -->
        </div>


    </div>

@endsection
