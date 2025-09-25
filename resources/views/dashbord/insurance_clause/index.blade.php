@extends('layouts.app')
@section('title', 'ادراة بند التامين')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <a type="button" href="{{ route('insurance_clause/create') }}"
                    class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">اضافة بند التامين</a>

            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض بند التامين</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>النص</th>

                                    <th>النوع</th>
                                    <th>تاريخ الاضافة</th>
                                    <th>تعديل</th>






                                </tr>
                            </thead>
                            <tbody>
                                <script>
                                    $(document).ready(function() {
                                        ;
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
                                            ajax: '{!! route('insurance_clause/insurance_clause') !!}',

                                            columns: [{
                                                    data: 'type'
                                                },
                                                {
                                                    data: 'slug'
                                                },


                                                {
                                                    data: 'created_at'
                                                },

                                                {
                                                    data: 'edit'
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
            </div>

        </div>
    </div>

@endsection
