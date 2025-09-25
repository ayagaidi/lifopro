@extends('layouts.app')
@section('title', 'ادراة اسعار الاقساط')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
@if($price)
@else
                <a type="button" href="{{ route('price/create') }}"
                    class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">اضافة سعر القسط</a>
@endif
            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض الاسعار</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th> القسط اليومي للبند الأول </th>
                                    <th> القسط اليومي للبند الثاني </th>
                                    <th> الإشراف</th>
                                    <th> الضريبة </th>
                                    <th> الإصدار </th>
                                    <th> الدمغة </th>
                                    <th> معدل الزيادة </th>
<th>تعديل</th>




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
                                            ajax: '{!! route('price/price') !!}',

                                            columns: [{
                                                    data: 'installment_daily_1'
                                                },
                                                {
                                                    data: 'installment_daily_2'
                                                },

                                           
                                                {
                                                    data: 'supervision'
                                                },

                                                {
                                                    data: 'tax'
                                                },
                                                {
                                                    data: 'version'
                                                },

                                                {
                                                    data: 'stamp'
                                                },
                                                {
                                                    data: 'increase'
                                                },
                                                {
                                                    data: 'edit'
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
