<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تقرير المجمع للمكاتب حسب الشركة</title>
    <style>
        body {
            font-family: 'Amiri', Arial, sans-serif;
            direction: rtl;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
                font-size: 12px;
            }

            .page-break {
                page-break-before: always;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th, td {
                padding: 6px;
            }
        }

        .print-only {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        #report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        #report-header .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        #report-header p,
        #report-header h4 {
            margin: 5px 0;
            font-size: 14px;
        }

        #report-header img {
            max-width: 80px;
            height: auto;
        }

        .search-params {
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
        }

        .search-params span {
            margin-left: 20px;
        }

        .totals-row {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .totals-row td {
            border-top: 2px solid #000;
        }
    </style>
</head>

<body>

    <div id="report-header">
        <div class="row">
            <div style="flex:1;">
                <p>العنوان: الإتحاد الليبي للتأمين</p>
                <p><a href="mailto:info@insurancefed.ly">info@insurancefed.ly :البريد الالكتروني</a></p>
                <p><a href="http://www.insurancefed.ly">www.insurancefed.ly: الموقع الالكتروني</a></p>
            </div>
            <div style="flex:1; text-align: center;">
                <img src="{{ asset('logo.png') }}" alt="Report Image" style="width: 100px;">
                <h4>دولــة لـيـبـيـا</h4>
                <h4>الاتـــحـاد الليبي للتأمين</h4>
            </div>
                <div style="flex:1; text-align: right;">
                    <p>تاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</p>
                    <p>تم بواسطة: {{ $user->username ?? 'غير معروف' }}</p>
                    <p>تقرير المجمع للمكاتب حسب الشركة</p>
                    <div class="search-params">
                        @if($startDate)
                            <span>من: {{ $startDate }}</span>
                        @endif
                        @if($endDate)
                            <span>إلى: {{ $endDate }}</span>
                        @endif
                        @if($companyId)
                            @php $company = \App\Models\Company::find($companyId); @endphp
                            <span>الشركة: {{ $company->name ?? 'غير معروف' }}</span>
                        @endif
                    </div>
                </div>
        </div>
    </div>

    <hr>
    <div class="print-only">
        <h3 style="text-align: center; margin-bottom: 20px;">تقرير المجمع للمكاتب حسب الشركة</h3>
        <p style="text-align: center; margin-bottom: 10px;">تاريخ التقرير: {{ date('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>المكتب</th>
                <th>الصادرة</th>
                <th>الملغاة</th>
                <th>صافي القسط</th>
                <th>الضريبة</th>
                <th>الدمغة</th>
                <th>الإشراف</th>
                <th>رسوم الإصدار</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->office_name }}</td>
                    <td>{{ $row->issued_count }}</td>
                    <td>{{ $row->canceled_count }}</td>
                    <td>{{ number_format($row->net_premium, 2) }}</td>
                    <td>{{ number_format($row->tax, 2) }}</td>
                    <td>{{ number_format($row->stamp, 2) }}</td>
                    <td>{{ number_format($row->supervision, 2) }}</td>
                    <td>{{ number_format($row->issuing_fee, 2) }}</td>
                    <td>{{ number_format($row->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">لا توجد بيانات للفترة المحددة.</td>
                </tr>
            @endforelse
            @if(count($data) > 0)
                <tr class="totals-row">
                    <td>الإجمالي</td>
                    <td>{{ $totals['issued_count'] }}</td>
                    <td>{{ $totals['canceled_count'] }}</td>
                    <td>{{ number_format($totals['net_premium'], 2) }}</td>
                    <td>{{ number_format($totals['tax'], 2) }}</td>
                    <td>{{ number_format($totals['stamp'], 2) }}</td>
                    <td>{{ number_format($totals['supervision'], 2) }}</td>
                    <td>{{ number_format($totals['issuing_fee'], 2) }}</td>
                    <td>{{ number_format($totals['total'], 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // تأخير بسيط لضمان تحميل الصفحة
        }
    </script>

</body>

</html>
