@extends('office.app')
@section('title', 'صلاحيات مستخدم مكتب'))
@section('content')


    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('office/offices_users') }}"> المستخدمين </a>/صلاحيات مستخدم  {{$offices_users->username}}</h4>
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
                        @foreach($role as $rod)
                            <tr>
                                <td>{{ $rod->office_user_permissions->name }}</td>
                                <td>
                                 
                                    <form action="{{route('office/offices_users/deletePermission',$rod->id)}}" method="POST" style="display: inline-block;">
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
