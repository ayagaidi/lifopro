@extends('comapny.app')
@section('title', 'تقرير مخزون الشركة ')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('company/card') }}">التقارير</a>/ تقرير مخزون الشركة </h4>

            </div>
        </div>
        <div class="row small-spacing">

            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض مخزون الشركة</h4>
                      <div class="mb-3">
                        <a href="{{ route('company/report/stock/companys') }}" target="_blank" class="btn btn-primary btn-bordered waves-effect waves-light">PDF</a>
               
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>

                                    <th>بطاقة معينة (مخزون الشركة)</th>
                                    <th>بطاقة المصدرة </th>
                                    <th>بطاقة ملغية </th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cardsstock as $item)
                                    <tr>
                                        <td>{{ $item->active_cards }} بطاقة</td>
                                        <td>{{ $item->sold }} بطاقة</td>
                                        <td>{{ $item->cancel }} بطاقة</td>

                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="2">لايوجد مخزون</td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#datatable1').dataTable({
                "language": {
                    "url": "{{ asset('Arabic.json') }}" //arbaic lang

                },
                "lengthMenu": [10, 20, 30, 50],
                "bLengthChange": true, //thought this line could hide the LengthMenu
                serverSide: false,
                paging: true,
                searching: true,
                ordering: true,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',


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
                




                ],

            });

        });
    </script>
@endsection
