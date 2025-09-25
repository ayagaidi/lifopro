@extends('comapny.app')
@section('title', 'التوزيع')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <div class="box-content ">
                    <h4 class="box-title"><a href="{{ route('company/distribution') }}">ادارة التوزيع</a>/تفاصيل توزيعات المكتب  {{$office->name}}  </h4>
                </div>            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض التوزيعات </h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">

                            <thead>
                                <tr>


                                    <th>عدد البطاقات  </th>
                                    <th>تاريخ التوزيع</th>
                                   


                                   




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
                                            ajax: '{!! route('company/distribution/detailall', ['office_id' => $office_id]) !!}',

                                            columns: [
                                               
                                              
                                                {
                                                    data: 'numerofcard'
                                                },
                                                {
                                                    data: 'created_at'
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
