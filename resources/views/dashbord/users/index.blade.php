@extends('layouts.app')
@section('title', trans('app.users'))

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <a type="button" href="{{ route('users/create') }}"
                    class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">{{ trans('users.add') }}</a>
            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض المستخدمين </h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>{{ trans('users.username') }}</th>
                                    <th>الاسم بالكامل</th>
                                    <th>{{ trans('users.email') }}</th>
                                    <th>{{ trans('users.phonenumber') }}</th>

                                    <th>{{ trans('users.active') }}</th>

                                    <th>{{ trans('users.created_at') }}</th>
                                    <th>صلاحية</th>
                                    <th style="border-left: none;"></th>
                                    <th style="border-Right: none;"></th>

<th></th>


                                </tr>
                            </thead>
                            <tbody>
                                <script>
                                    $(document).ready(function() {

                                        $('#datatable1').dataTable({
                                            "language": {
                                                "url": "../../Arabic.json" //arbaic lang

                                            },
                                            "lengthMenu": [10, 15, 20, 50, 100],
                                            "bLengthChange": true, //thought this line could hide the LengthMenu
                                            serverSide: false,
                                            paging: true,
                                            searching: false,
                                            ordering: true,
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            ajax: '{!! route('users/users') !!}',

                                            columns: [{
                                                    data: 'username'
                                                },
                                                {
                                                    data: 'fullname'
                                                },
                                                {
                                                    data: 'email'
                                                },
                                                {
                                                    data: 'phonenumber'
                                                },

                                                {
                                                    data: 'active',
                                                    render: function(data) {
                                                        if (data == 1) {
                                                            return 'الحساب مفعل <i class="fa fa-circle" style="color:#2be71b;;" aria-hidden="true"></i>'
                                                        } else {
                                                            return 'الحساب معطل <i class="fa fa-circle" style="color:#e71b1b;;" aria-hidden="true"></i>'
                                                        }

                                                    }
                                                },

                                                {
                                                    data: 'created_at'
                                                },
                                                {
                                                    data: 'role'
                                                },



                                                {
                                                    data: 'changeStatus'
                                                },



                                                {
                                                    data: 'edit'
                                                },
                                                 {
                                                    data: 'changepassord'
                                                },
                                                




                                            ],

                                            dom: 'Blfrtip',

                                            buttons: [{
                                                    extend: 'copyHtml5',
                                                    exportOptions: {
                                                        columns: [':visible']
                                                    },
                                                    text: 'نسخ'
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    text: 'excel تصدير كـ '

                                                },
                                                {
                                                    extend: 'colvis',
                                                    text: 'الأعمدة'

                                                },
                                            ],

                                        });

                                    });
                                </script>
                            </tbody>

                        </table>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <!-- /.col-xs-12 -->

        </div>
    </div>

@endsection
