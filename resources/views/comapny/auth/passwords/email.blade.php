@extends('layouts.authapp')
@section('title',"  نسيت كلمة المرور")

@section('content')
    <form method="POST" class="frm-single" action="{{ route('password.email') }}" style="max-width: 600px;">
        @csrf
    <div class="inside">
        <div class="title"><img src="{{asset('logo.svg')}}" alt="" style="max-width: 40% !important;"></div>

        <!-- /.title -->
        <div class="frm-title"> نسيت كلمة المرور</div>
        <!-- /.frm-title -->
        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

      
                    <!-- /.frm-title -->
                    <div class="frm-input"><input type="text"  name="email" placeholder="{{trans('login.email')}}" class="frm-inp form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"><i class="fa fa-user frm-ico"></i></div>
                    @error('email')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror


   
     
        
        <!-- /.clearfix -->
        <button type="submit" class="frm-submit">إرسال رابط إعادة تعيين كلمة السر<i class="fa fa-arrow-circle-right"></i></button>
       <!-- /.frm-input -->
       <div class="clearfix margin-bottom-20">
            
      
        <div class="pull-right">
        
            
            
            <a href="{{ route('login') }}" class="a-link"><i class="fa fa-unlock-alt"></i>{{trans('login.login')}}</a></div>
        <!-- /.pull-right -->
    </div>
        <!-- /.row -->
        <div class="frm-footer"><?php echo date("Y"); ?> &copy;{{trans('login.copyright')}} </div>
        <!-- /.footer -->
    </div>
    <!-- .inside -->
</form>

@endsection
