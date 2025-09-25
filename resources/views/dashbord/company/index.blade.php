@extends('layouts.app')
@section('title', 'الشركات')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <a type="button" href="{{ route('company/create') }}"
                    class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">اضافة شركة</a>
            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض الشركات </h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>الشركة</th>
                                    <th>رمز الشركة</th>
                                    <th> مدير الشركة (المندوب) </th>

                                    <th> رقم هاتف المدير (المندوب) </th>

                                    
                                    <th>العنوان</th>
                                    <th>البريد الالكتروني</th>

                                    <th>الموقع الالكتروني </th>
                                    <th>حالة الشركة </th>

                                    <th>تاريخ انشاء الشركة </th>


                                    <th style="border-left: none;">تغيير حالة شركة</th>
                                    <th style="border-Right: none;">تعديل</th>
                                    <th style="border-Right: none;">المستخدمين</th>




                                </tr>
                            </thead>
                            <tbodys>
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
                                            searching: true,
                                            ordering: true,
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            ajax: '{!! route('company/company') !!}',

                                            columns: [{
                                                    data: 'img'
                                                },
                                                {
                                                    data: 'name'
                                                },
                                                {
                                                    data: 'code'
                                                },
                                                {
                                                    data: 'fullname_manger'
                                                },
                                                {
                                                    data: 'phonenumber_manger'
                                                },
                                               
                                                {
                                                    data: 'address'
                                                },
                                                {
                                                    data: 'email'
                                                },
                                                {
                                                    data: 'website'
                                                },

                                                {
                                                    data: 'active',
                                                    render: function(data) {
                                                        if (data == 1) {
                                                            return ' مفعل <i class="fa fa-circle" style="color:#2be71b;;" aria-hidden="true"></i>'
                                                        } else {
                                                            return ' معطل <i class="fa fa-circle" style="color:#e71b1b;;" aria-hidden="true"></i>'
                                                        }

                                                    }
                                                },

                                                {
                                                    data: 'created_at'
                                                },

                                                {
                                                    data: 'changeStatus'
                                                },
                                                {
                                                    data: 'edit'
                                                },
                                                {
                                                  data:'company_users'
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
