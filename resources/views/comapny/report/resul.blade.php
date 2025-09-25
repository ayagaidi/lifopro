@extends('comapny.app')
@section('title', 'تقارير المبيعات ')

@section('content')

    <script>
        $(document).ready(function() {
            const mainMenu = document.querySelector('.main-menu');
            mainMenu.style.display = 'none';
            const fixednavbar = document.querySelector('.fixed-navbar');
            fixednavbar.style.display = 'none';
            const maincontent = document.querySelector('.main-content');
            maincontent.style.width = '100%';
            maincontent.style.margin = '20px';
            maincontent.style.padding = '0px'; // Ensure proper spacing and remove padding

            "use strict";

            // Handle the print button click event
            $("#datatable1 tr").css("display", "table-row");
            $("#datatable1 td, #datatable1 th").css("display", "table-cell");

            printForm();

            function printForm() {
                // Set the current date in the report header
                const today = new Date().toLocaleString('ar-LY', { timeZone: 'Africa/Tripoli' });
                document.getElementById('report-date').textContent = today;

                         // Add header before printing
                $("#report-header").css("display", "block");

                const headerHtml = document.getElementById('report-header').outerHTML;
                const tableHtml = document.getElementById('datatable1').outerHTML;
                const printHtml = headerHtml + tableHtml;

                printJS({
                    printable: printHtml,
                    type: 'raw-html',
                    style: `
                /* General styling for the page */
                @page {
                    margin: 20mm; /* Adjust as needed */
                }

                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    border: 1px solid #000; /* Border around the entire page */
                    box-sizing: border-box;
                }

                /* General styling for the table */
                #datatable1 {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 0; /* Remove margin */
                    direction: rtl;
                }
                #datatable1 th, #datatable1 td {
                    border: 1px solid #ddd; /* Border for table cells */
                    font-size: 10px; /* Smaller font size for better readability */
                    padding: 8px; /* Increased padding for better readability */
                    text-align: center;
                }
                #datatable1 th {
                    background-color: #f4f4f4; /* Light gray background for header cells */
                    font-weight: bold;
                }
                /* Ensure borders for the entire table */
                #datatable1 tr, #datatable1 tbody {
                    border: 1px solid #ddd; /* Border around table rows and body */
                }

                /* Styling for the header */
                #report-header {
                    margin: 0 0 20px 0; /* Remove margin top and bottom space */
                    text-align: center; /* Center align the header text */
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

                /* Hide unnecessary elements for printing */
               @media print {
                .view, .no-print {
                    display: none;
                }
                #report-header {
                    display: block;
                }
                
                /* Ensure borders are visible in print */
                #datatable1, #datatable1 td, #datatable1 th {
                    border: 1px solid #ddd !important; /* Maintain a clean border style */
                }

                /* Show the footer only on the last page */
                #datatable1 tfoot {
                    display: table-row-group; /* Show the footer group */
                }
                #datatable1 tfoot tr {
                    display: table-row; /* Show the footer row */
                }
                #datatable1 tr {
                    page-break-inside: avoid; /* Avoid breaking rows inside pages */
                }
                #datatable1 tr:not(:last-child) tfoot {
                    display: none; /* Hide footer for all but last row */
                }
            }
          
                `,
                    scanStyles: true,
                    header: ''
                });
            }

            // Export table to Excel
            $("#btnExport").click(function() {
                let table = document.getElementById("datatable1");
                $(".view").css("display", "none");

                TableToExcel.convert(
                    table, {
                        name: 'insurance_report.xlsx', // Filename for the Excel file
                        sheet: {
                            name: 'Sheet 1' // Sheet name in the Excel file
                        }
                    }
                );
            });
        });
    </script>


    <div class="row small-spacing" style="margin-top: 50px">
        <div class="col-md-12">
            <div class="box-content">
                <div class="table-responsive" data-pattern="priority-columns">
                    <div id="report-header" style="display: none">
                        <div class="row">
                            <div class="col-md-4">
                                <p style="font-weight: bold">العنوان: الإتحاد الليبي للتأمين</p>
                                <p style="font-weight: bold"> <a href="mailto:info@insurancefed.ly">info@insurancefed.ly
                                        :البريد الالكتروني</a></p>
                                <p style="font-weight: bold"><a href="http://www.insurancefed.ly">www.insurancefed.ly:
                                        الموقع الالكتروني</a></p>
                            </div>
                            <div class="col-md-4 text-center" style="text-align: center">
                                <img src="{{ asset('logo.png') }}" alt="Report Image" style="width: 100px;">
                                <h4 style="font-weight: bold">دولــة لـيـبـيـا</h4>
                                <h4 style="font-weight: bold">الاتـــحـاد الليبي للتأمين</h4>
                            </div>
                            <div class="col-md-4">
                                <p style="font-weight: bold">وقت وتاريخ الانشاء: <span id="report-date"></span></p>
                                <p style="font-weight: bold">الشركة  : {{Auth::user()->companies->name}}</p>

                                <p style="font-weight: bold"> <span id="report-creator">
                                        تم إنشاؤه من قبل</span>
                                    :{{ Auth::user()->username }}</p>
                                    <p style="font-weight: bold"> <span id="report-creator">
                                        الفترة من :{{$fromdate}}  </span>
                                    الي الفترة :{{$todate}}</p>
                                    <p style="font-weight: bold">  الإجمالي: {{ number_format($total, 3) }} دينار</p>

                                </div>
                        </div>
                    </div>
                    <table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom"
                        style="cursor: pointer;">
                        
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم البطاقة</th>
                                <th>تم الإصدار بواسطة </th>
                                <th>اسم المكتب </th>
                                <th>المؤمن له </th>
                                <th>تاريخ الاصدار </th>
                                <th>صافي القسط </th>
                                <th>الضريبة </th>
                                <th>رسم الدمغة </th>
                                <th>الإشراف </th>
                                <th>الإصدار </th>
                                <th>الاجمالي </th>
                                <th>مدة التامين(من) </th>
                                <th>مدة التامين(الي) </th>
                                <th>عدد الايام </th>
                                <th>نوع المركبة  </th>
                                <th>رقم اللوحة   </th>
                                <th>رقم الهيكل   </th>
                                <th>رقم المحرك   </th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                                $total_installment = 0;
                                $total_tax = 0;
                                $total_stamp = 0;
                                $total_supervision = 0;
                                $total_version = 0;
                                $total_total = 0; // Initialize the count variable
                            @endphp
                            @foreach ($issuing as $item)
                                <tr>
                                    @php
                                       if ($item->office_users_id) {
                                            $user = $item->office_users->username;
                                        }else
                                         if ($item->company_users_id) {
                                            $user = $item->company_users->username;
                                        }
                                        if ($item->companies_id) {
                                            $companies = $item->companies->name;
                                        } else {
                                            $companies = 'الإتحاد الليبي للتأمين';
                                        }

                                        if ($item->offices_id) {
                                            $offices = $item->offices->name;
                                            $companies = $item->offices->companies->name;

                                        } else {
                                            $offices = 'الفرع  الرئيسي';
                                        }
                                        // Accumulate totals
                                        $total_installment += $item->insurance_installment;
                                        $total_tax += $item->insurance_tax;
                                        $total_stamp += $item->insurance_stamp;
                                        $total_supervision += $item->insurance_supervision;
                                        $total_version += $item->insurance_version;
                                        $total_total += $item->insurance_total;
                                    @endphp
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $item->cards->card_number }}</td>
                                    <td>{{ $user }}</td>
                                    <td>{{ $offices }}</td>
                                    <td>{{ $item->insurance_name }}</td>
                                    <td>{{ $item->issuing_date }}</td>
                                    <td>{{ $item->insurance_installment }}</td>
                                    <td>{{ $item->insurance_tax }}</td>
                                    <td>{{ $item->insurance_stamp }}</td>
                                    <td>{{ $item->insurance_supervision }}</td>
                                    <td>{{ $item->insurance_version }}</td>
                                    <td>{{ $item->insurance_total }}</td>
                                    <td>{{ $item->insurance_day_from }}</td>
                                    <td>{{ $item->nsurance_day_to }}</td>
                                    <td>{{ $item->insurance_days_number }}</td>
                                    <td>{{ $item->cars->name ?? $item->cars_id }}</td> 
                                    <td>{{ $item->plate_number }}</td>
                                    <td>{{ $item->chassis_number }}</td>
                                    <td>{{ $item->motor_number }}</td>


                                    
                                </tr>

                            @endforeach
                            <tfoot class="print-footer">
                                <tr>
                                    <td style="text-align: right;"><strong>الإجمالي:</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
    
                                    <td>{{ number_format($total_installment, 3) }}</td>
                                    <td>{{ number_format($total_tax, 3) }}</td>
                                    <td>{{ number_format($total_stamp, 3) }}</td>
                                    <td>{{ number_format($total_supervision, 3) }}</td>
                                    <td>{{ number_format($total_version, 3) }}</td>
                                    <td>{{ number_format($total_total, 3) }}</td>
    
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
