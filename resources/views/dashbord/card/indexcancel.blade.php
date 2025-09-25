@extends('layouts.app')
@section('title', 'كافة البطاقات الملغية')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('card/cancel') }}">البطاقات</a>/ الملغية كافة البطاقات</h4>

            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض البطاقات</h4>
                    <div class="mb-3 text-left">
    <a href="{{ route('card/cancelall/pdf') }}" target="_blank" class="btn btn-round">
        <i class="fa fa-print"></i> طباعة النتائج PDF
    </a>
</div
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    
                                    <th>رقم البطاقة</th>
                                    <th>إسم الشركة</th>
                                    <th>حالة البطاقة</th>
                                    <th>رقم الطلب </th>
                                    <th> تاريخ اصدار البطاقة </th>
<th>تاريخ الغآء البطاقة</th>




                                </tr>
                            </thead>
                            <tbody>
                                <script>
   $('#loader-overlay').show();

$(document).ready(function() {

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
                                            ajax: '{!! route('card/allcancel') !!}',

                                           columns: [
    
    { data: 'card_number' },
    { data: 'companies_name' },     // ← بدلًا من companies_id
    { data: 'cardstautes_name' },   // ← بدلًا من cardstautesname
    { data: 'request_number' },     // ← بدلًا من request_numberr
    { data: 'issuing_date' },
    {
        data: 'card_delete_date',
        render: function(data, type, row) {
            return data ? data : 'N/A';
        }
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
