@extends('office.app')
@section('title', 'كافة البطاقات الملغية')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('office/card/cancel') }}">البطاقات</a>/ الملغية كافة البطاقات</h4>

            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض البطاقات</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                   
                                    <th>رقم البطاقة</th>
                                    <th>اسم المكتب</th>

                                    <th>حالة البطاقة</th>
                                    <th>رقم الطلب </th>
                                    <th>  تاريخ الغآء البطاقه </th>






                                </tr>
                            </thead>
                            <tbody>
                                <script>
                                    $(document).ready(function() {
                                        $('#loader-overlay').show();

                                        $('#datatable1').dataTable({
                                            "language": {
                                                "url": "{{asset('Arabic.json')}}" //arbaic lang

                                            },
                                            "lengthMenu": [10, 15, 20, 50, 100],
                                            "bLengthChange": true, //thought this line could hide the LengthMenu
                                            serverSide: false,
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            ajax: '{!! route('office/card/allcancel') !!}',

                                            columns: [
                                              
                                                {
                                                    data: 'card_number'
                                                },

                                                {
                                                    data: 'offices'
                                                },
                                                {
                                                    data: 'cardstautes.name'
                                                },
                                                {
                                                    data: 'requests.request_number'
                                                },
                                                {
                                                    data: 'card_delete_date'
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

                                                }
                                            
                                            ],

                                        });

                                        $('#loader-overlay').hide();


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
