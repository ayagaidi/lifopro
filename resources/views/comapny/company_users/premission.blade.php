@extends('comapny.app')
@section('title', 'صلاحيات مستخدم شركة'))
@section('content')


    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('company/company_users') }}"> الشركات </a>/صلاحيات مستخدم  {{$CompanyUser->username}}</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-content">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>الصلاحية</th>
                            <th>الحدث</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role as $ro)
                            <tr>
                                <td>{{ $ro->company_user_permissions->name }}</td>
                                <td>
                                 
                                    <form action="{{route('company/company_users/deletePermission',$ro->id)}}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background-color: none;border: none !important;background-color: white;color: red;" class="btn " onclick="return confirm('Are you sure you want to delete this permission?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

@endsection
