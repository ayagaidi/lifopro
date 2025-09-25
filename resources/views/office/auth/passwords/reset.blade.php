@extends('layouts.authapp')
@section('title',"  استعادة كلمة المرور")

@section('content')
    <form method="POST" class="frm-single" action="{{ route('password.update') }}" style="max-width: 400px;">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

    <div class="inside">
        <div class="title"><img src="{{asset('logo.svg')}}" alt="" style="max-width: 40% !important;"></div>

        <!-- /.title -->
        <div class="frm-title"> استعادة كلمة المرور</div>
        <!-- /.frm-title -->
        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

      
                    <!-- /.frm-title -->
                    <div class="frm-input"><input type="text"  name="email" placeholder="{{trans('login.email')}}" class="frm-inp form-control @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus><i class="fa fa-user frm-ico"></i></div>
                    @error('email')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror

                <div class="frm-input"><input type="password"  name="password" placeholder="{{trans('login.password')}}" class="frm-inp form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required autocomplete="new-password"><i class="fa fa-lock frm-ico"></i></div>
                @error('password')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                        

   
                <div class="frm-input"> 
                    <input id="password-confirm" type="password" placeholder="تاكيد كلمة المرور" class="frm-inp form-control" name="password_confirmation" required autocomplete="new-password"><i class="fa fa-lock frm-ico"></i>
                </div>
        
        <!-- /.clearfix -->
        <button type="submit" class="frm-submit">   استعادة كلمة المرور <i class="fa fa-arrow-circle-right"></i></button>
       <!-- /.frm-input -->
       <div class="clearfix margin-bottom-20">
            
        {{-- <div class="pull-left">
            <div class="checkbox primary"><input type="checkbox" id="rememberme"><label for="rememberme">Remember me</label></div>
            <!-- /.checkbox -->
        </div> --}}
        <!-- /.pull-left -->
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

