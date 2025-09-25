@extends('office.app')
@section('title', 'تقرير المخزون ')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('office/card') }}">التقارير</a>/ تقرير المخزون </h4>

            </div>
        </div>
        <div class="row small-spacing">
          
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض المخزون</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>اجمالي البطاقات  </th>

                                    <th>بطاقة معينة </th>
                                    <th>بطاقة المصدرة </th>
                                    <th>بطاقة ملغية </th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cardsstock as $item)
                                    <tr>
                                        <td>{{ $item->total_cards }} بطاقة</td>

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
                    {

                        extend: 'pdfHtml5',
                        // orientation: 'landscape',
                        exportOptions: {
                            columns: ':visible'
                        },
                        text: 'تصدير كـ PDF',

                        customize: function(doc) {
                            // Set default font
                            doc.defaultStyle.font = 'Cairo'; // Ensure the font is available

                            // Set paper size
                            doc.pageSize = 'A4'; // Use A4 paper size

                            // Align content to the right and ensure RTL text direction
                            doc.content.forEach(item => {
                                if (item.hasOwnProperty('alignment')) {
                                    item.alignment = 'right';
                                }
                                if (item.hasOwnProperty('text') && typeof item.text ===
                                    'string') {
                                    item.direction =
                                        'rtl'; // Ensure RTL direction for Arabic text
                                }
                                if (item.hasOwnProperty('table')) {
                                    // For tables, align content and set text direction for cells
                                    item.table.body.forEach(row => {
                                        row.forEach(cell => {
                                            if (cell.hasOwnProperty(
                                                    'alignment')) {
                                                cell.alignment =
                                                    'right'; // Align table cell content to the right
                                            }
                                            if (cell.hasOwnProperty(
                                                    'text') && typeof cell
                                                .text === 'string') {
                                                cell.direction =
                                                    'rtl'; // Ensure RTL direction for table cell text
                                            }
                                        });
                                    });
                                }
                            });

                            // Set alignment for table headers and rows
                            if (doc.styles) {
                                if (doc.styles.tableHeader) {
                                    doc.styles.tableHeader.alignment =
                                        'right'; // Align headers to the right
                                    doc.styles.tableHeader.direction =
                                        'rtl'; // Ensure RTL direction for headers
                                }
                                if (doc.styles.tableBodyEven) {
                                    doc.styles.tableBodyEven.alignment =
                                        'right'; // Align even rows to the right
                                    doc.styles.tableBodyEven.direction =
                                        'rtl'; // Ensure RTL direction for even rows
                                }
                                if (doc.styles.tableBodyOdd) {
                                    doc.styles.tableBodyOdd.alignment =
                                        'right'; // Align odd rows to the right
                                    doc.styles.tableBodyOdd.direction =
                                        'rtl'; // Ensure RTL direction for odd rows
                                }
                            }
                        }
                    }




                ],

            });

        });
    </script>
@endsection
