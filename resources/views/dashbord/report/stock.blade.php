@extends('layouts.app')
@section('title', 'تقرير المخزون')

@section('content')

<script>
    $(document).ready(function () {
        // Hide UI elements not needed for print
        $('.main-menu, .fixed-navbar').hide();
        $('.main-content').css({ width: '100%', margin: '20px', padding: '0px' });

        // Show all table rows and columns before printing
        $("#datatable1 tr").css("display", "table-row");
        $("#datatable1 td, #datatable1 th").css("display", "table-cell");

        // Add current date and time
        const today = new Date().toLocaleString('ar-LY', { timeZone: 'Africa/Tripoli' });
        $('#report-date').text(today);

        $("#report-header").css("display", "block");

        const headerHtml = document.getElementById('report-header').outerHTML;
        const tableHtml = document.getElementById('datatable1').outerHTML;
        const printHtml = headerHtml + tableHtml;

        printJS({
            printable: printHtml,
            type: 'raw-html',
            style: `
                @page { margin: 20mm; }

                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    direction: rtl;
                    box-sizing: border-box;
                }

                #datatable1 {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 0;
                }

                #datatable1 th, #datatable1 td {
                    border: 1px solid #ddd;
                    font-size: 10px;
                    padding: 8px;
                    text-align: center;
                }

                #datatable1 th {
                    background-color: #f4f4f4;
                    font-weight: bold;
                }

                #report-header {
                    margin: 0 0 20px 0;
                    text-align: center;
                }

                #report-header .col-md-4 {
                    display: inline-block;
                    vertical-align: top;
                    width: 30%;
                    padding: 0 10px;
                    text-align: right;
                }

                #report-header img {
                    max-width: 80px;
                    margin: auto;
                    display: block;
                }

                @media print {
                    .view, .no-print {
                        display: none;
                    }
                    #report-header {
                        display: block;
                    }

                    #datatable1, #datatable1 td, #datatable1 th {
                        border: 1px solid #ddd !important;
                    }

                    #datatable1 tfoot {
                        display: table-row-group;
                    }

                    #datatable1 tr {
                        page-break-inside: avoid;
                    }
                }
            `,
            scanStyles: true,
            header: ''
        });
    });
</script>

<div class="row small-spacing" style="margin-top: 50px">
    <div class="col-md-12">
        <div class="box-content">
            <div class="table-responsive" data-pattern="priority-columns">
                {{-- Report Header --}}
                <div id="report-header" style="display: none">
                    <div class="row">
                        <div class="col-md-4">
                            <p style="font-weight: bold">العنوان: الإتحاد الليبي للتأمين</p>
                            <p style="font-weight: bold">
                                <a href="mailto:info@insurancefed.ly">info@insurancefed.ly :البريد الالكتروني</a>
                            </p>
                            <p style="font-weight: bold">
                                <a href="http://www.insurancefed.ly">www.insurancefed.ly :الموقع الالكتروني</a>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('logo.png') }}" alt="Report Image" style="width: 100px;">
                            <h4 style="font-weight: bold">دولــة لـيـبـيـا</h4>
                            <h4 style="font-weight: bold">الاتـــحـاد الليبي للتأمين</h4>
                        </div>
                        <div class="col-md-4">
                            <p style="font-weight: bold">وقت وتاريخ الانشاء: <span id="report-date"></span></p>
                            <p style="font-weight: bold">تقرير المخزون</p>
                            <p style="font-weight: bold">تم إنشاؤه بواسطة: {{ Auth::user()->username }}</p>
                        </div>
                    </div>
                </div>

                {{-- Report Table --}}
                <table id="datatable1" class="table table-bordered table-hover dataTable table-custom">
                    <thead>
                        <tr>
                            <td colspan="2"
                                style="color: white; font-size: larger; font-weight: bold; background-color: #ba8173;">
                                اجمالي مخزون الاتحاد
                            </td>
                            <td colspan="6"
                                style="color: white; font-size: larger; font-weight: bold; background-color: #ba8173;">
                                {{ $cardcount }} بطاقة
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>الشركة</th>
                            <th>اجمالي البطاقات</th>
                            <th>البطاقات المصدرة</th>
                            <th>مخزون البطاقات</th>
                            <th>البطاقات الملغية</th>
                            <th>المبيعات (د.ل)</th>
                            <th>حصة الشركة (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cardsStock as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->companies_name }}</td>
                                <td>{{ $item->total_cards }} بطاقة</td>
                                <td>{{ $item->sold }} بطاقة</td>
                                <td>{{ $item->active_cards }} بطاقة</td>
                                <td>{{ $item->cancelled }} بطاقة</td>
                                <td>{{ number_format($item->total_insurance, 2) }} د.ل</td>
                                <td>{{ number_format($item->percentage, 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-center font-weight-bold">الإجمالي</td>
                            <td>{{ $totals['total_cards'] }} بطاقة</td>
                            <td>{{ $totals['sold'] }} بطاقة</td>
                            <td>{{ $totals['active_cards'] }} بطاقة</td>
                            <td>{{ $totals['cancelled'] }} بطاقة</td>
                            <td>{{ number_format($totals['total_insurance'], 2) }} د.ل</td>
                            <td>{{ number_format($totals['percentage'], 2) }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
