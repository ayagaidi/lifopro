@extends('layouts.app')
@section('title', 'ادراة شروط الدول')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <a type="button" href="{{ route('countryconditions/create') }}"
                    class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">اضافة شرط دولة</a>

            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض شروط الدول</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>الاسم</th>

                                    <th>الرمز</th>
                                    <th>عنوان المكتب الموحد</th>
                                    <th> بيان نوع التغطية</th>

                                    <th>حالة الشرط</th>
                                    <th>تاريخ الاضافة</th>

                                    <th>تعديل</th>
                                    <th>تفعيل/الغاء</th>






                                </tr>
                            </thead>
                            <tbody>
                                <script>
                                    $(document).ready(function() {
                                        $('#datatable1 thead tr').clone(true).appendTo('#datatable1 thead');
                                        $('#datatable1 thead tr:eq(1) th').each(function(i) {
                                            var title = $(this).text();
                                            if ((title == 'الاسم') || (title == 'الرمز')) {

                                                $(this).html('<input  type="text"   placeholder=" بحث بواسطة ' + title + '" />');

                                            } else {
                                                $(this).html('');

                                            }

                                            $('input', this).on('keyup change', function() {
                                                if (table.api().column(i).search() !== this.value) {
                                                    table.api()
                                                        .column(i)
                                                        .search(this.value)
                                                        .draw();
                                                }
                                            });
                                        });
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
                                            searching: false,
                                            ordering: true,
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            ajax: '{!! route('countryconditions/countryconditions') !!}',

                                            columns: [{
                                                    data: 'countries.name'
                                                },
                                                {
                                                    data: 'countries.symbol'
                                                },
                                                {
                                                    data: 'unifiedofficaddress'
                                                },
                                                {
                                                    data: 'statementypecoverage'
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
