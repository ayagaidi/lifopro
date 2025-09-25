@extends('comapny.app')
@section('title', ' مستخدمين المكاتب  ')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <a type="button" href="{{ route('company/offices_users/create',$officeid) }}"

                    class="btn btn-primary btn-bordered waves-effect waves-light "> اضافة مستخدم مكتب  </a>
            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض مستخدمين مكتب     </h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>اسم مستخدم</th>
                                    <th>اسم بالكامل</th>

                                    <th>نوع الحساب </th>


                                    <th>حالة الحساب  </th>

                                    <th>تاريخ انشاء مستخدم </th>


                                    <th style="border-left: none;">تغيير حالة الحساب</th>
                                    <th style="border-Right: none;">تعديل</th>
                                    <th style="border-Right: none;">تغيير كلمة المرور</th>

                                    <th style="border-Right: none;">صلاحيات  </th>



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
                                            searching: true,
                                            ordering: true,
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
 ajax: '{!! route('company/offices_users/offices_users', ['offices_id' => $officeid]) !!}',
                                            columns: [
                                                {
                                                    data: 'username'
                                                },
                                                {
                                                    data: 'fullname'
                                                },
                                                {
                                                    data: 'user_type.slug'
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
                                                    data: 'changepassord'
                                                },
                                                {
                                                    data: 'showpermission'
                                                }
                                                

                                                
                                                

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
