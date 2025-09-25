@extends('layouts.app')
@section('title', 'المكاتب')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('offices') }}">المكاتب</a>/عرض مكتب شركة  {{$companyoffices->companies->name}}</h4>
            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title"> عرض مكاتب شركة {{$companyoffices->companies->name}}</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>الشركة</th>
                                    <th> المكتب</th>

                                    <th> مدير المكتب (المندوب) </th>

                                    <th> رقم هاتف المدير (المندوب) </th>

                                    
                                    <th>العنوان</th>
                                    <th>البريد الالكتروني</th>

                                    <th>حالة المكتب </th>

                                    <th>تاريخ انشاء المكتب </th>


                                    <th style="border-left: none;">تغيير حالة المكتب</th>
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
                                            ajax: '{!! route('offices/offices', ['company_id' => $companyoffices->companies->id]) !!}',

                                            columns: [
                                                {
                                                    data: 'companies.name'
                                                },
                                                {
                                                    data: 'name'
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
                                                    data: 'company_users'
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
