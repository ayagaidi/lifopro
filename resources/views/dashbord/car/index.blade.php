@extends('layouts.app')
@section('title', 'ادراة السيارات')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <a type="button" href="{{ route('car/create') }}"
                    class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">اضافة سيارة</a>

            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض السيارات</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>الاسم</th>

                                    <th>الرمز</th>
                                    <th>حالة السيارة</th>
                                    <th>تاريخ الاضافة</th>

                                    <th>تعديل</th>
                                    <th>تفعيل/الغاء</th>






                                </tr>
                            </thead>
                            <tbody>
                                <script>
                                    $(document).ready(function() {
                                     
                                        $('#datatable1').dataTable({
                                            "language": {
                                                "url": "{{asset('Arabic.json')}}" //arbaic lang

                                            },
                                            orderCellsTop: true,
                                            fixedHeader: true,
                                            "lengthMenu": [10, 15, 20, 50, 100],
                                            "bLengthChange": true, //thought this line could hide the LengthMenu
                                            serverSide: false,
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            ajax: '{!! route('car/car') !!}',

                                            columns: [{
                                                    data: 'name'
                                                },
                                                {
                                                    data: 'symbol'
                                                },

                                                {
                                                    data: 'active',
                                                    render: function(data) {
                                                        if (data == 1) {
                                                            return ' معينة <i class="fa fa-circle" style="color:#2be71b;;" aria-hidden="true"></i>'
                                                        } else {
                                                            return ' متبقية <i class="fa fa-circle" style="color:#e71b1b;;" aria-hidden="true"></i>'
                                                        }

                                                    }
                                                },
                                                {
                                                    data: 'created_at'
                                                },

                                                {
                                                    data: 'edit'
                                                },
                                                {
                                                    data: 'changeStatus'
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
            </div>

        </div>
    </div>

@endsection
