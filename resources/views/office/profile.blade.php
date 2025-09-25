@extends('office.app')
@section('title',trans('users.profilelogger'))

@section('content')
<div class="row small-spacing">
    
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content card">
			
							<!-- /.dropdown js__dropdown -->
							<div class="card-content">
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-xs-5"><label>{{trans('users.username')}}</label></div>
											<!-- /.col-xs-5 -->
											<div class="col-xs-7">{{$user->username}}</div>
											<!-- /.col-xs-7 -->
										</div>
										<!-- /.row -->
									</div>
									<!-- /.col-md-6 -->
									<div class="col-md-6">
										<div class="row">
											<div class="col-xs-5"><label>الاسم بالكامل</label></div>
											<!-- /.col-xs-5 -->
											<div class="col-xs-7">{{$user->fullname}}</div>
											<!-- /.col-xs-7 -->
										</div>
										<!-- /.row -->
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-xs-5"><label>الشركة </label></div>
											<!-- /.col-xs-5 -->
											<div class="col-xs-7">{{$user->offices->companies->name}}</div>
											<!-- /.col-xs-7 -->
										</div>
										<!-- /.row -->
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-xs-5"><label>المكتب </label></div>
											<!-- /.col-xs-5 -->
											<div class="col-xs-7">{{$user->offices->name}}</div>
											<!-- /.col-xs-7 -->
										</div>
										<!-- /.row -->
									</div>
									<!-- /.col-md-6 -->
									
									<!-- /.col-md-6 -->
									<div class="col-md-6">
										<div class="row">
											<div class="col-xs-5"><label>{{trans('users.phonenumber')}}</label></div>
											<!-- /.col-xs-5 -->
											@if($user->phonenumber)
											<div class="col-xs-7">{{$user->phonenumber}}</div>
												
											@else
												
											<div class="col-xs-7">لايوجد</div>
											@endif
											<!-- /.col-xs-7 -->
										</div>
										<!-- /.row -->
									</div>
									<!-- /.col-md-6 -->
									<div class="col-md-6">
										<div class="row">
											<div class="col-xs-5"><label>{{trans('users.email')}}</label></div>
											<!-- /.col-xs-5 -->
											@if($user->email)
											<div class="col-xs-7">{{$user->email}}</div>
												
											@else
												
											<div class="col-xs-7">لايوجد</div>
											@endif
																				<!-- /.row -->
									</div>
									</div>
									<!-- /.col-md-6 -->
								
									<!-- /.col-md-6 -->
								
									<!-- /.col-md-6 -->
									<div class="col-md-6">
										<div class="row">
											<div class="col-xs-5"><label>{{trans('users.created_at')}}</label></div>
											<!-- /.col-xs-5 -->
											<div class="col-xs-7">{{$user->created_at}}</div>
											<!-- /.col-xs-7 -->
										</div>
										<!-- /.row -->
									</div>
								</div>
								<!-- /.row -->
							</div>
							<!-- /.card-content -->
						</div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
      
    </div>
</div>

@endsection