<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير طلبات شركات التأمين</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        #report-header .row {
            display: flex;
            justify-content: space-between;
        }
        #report-header p, #report-header h4 {
            margin: 0;
        }
        .search-params {
            margin-top: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        .search-params span {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<div id="report-header">
    <div class="row">
        <div style="flex:1;">
            <p>العنوان: الإتحاد الليبي للتأمين</p>
            <p><a href="mailto:info@insurancefed.ly">info@insurancefed.ly :البريد الالكتروني</a></p>
            <p><a href="http://www.insurancefed.ly">www.insurancefed.ly :الموقع الالكتروني</a></p>
        </div>
        <div style="flex:1; text-align: center;">
            <img src="{{ asset('logo.png') }}" alt="Report Logo" style="width: 100px;">
            <h4>دولــة لـيـبـيـا</h4>
            <h4>الاتـــحـاد الليبي للتأمين</h4>
        </div>
        <div style="flex:1; text-align: right;">
            <p>وقت وتاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</p>
            <p>تم بواسطة: {{ $user->username ?? 'غير معروف' }}</p>
            <p>تقرير طلبات شركات التأمين</p>

            <div class="search-params">
                @php
                    $filters = $searchParams ?? [];
                    $companyName = null;
                    if (!empty($filters['companies_id']) && $filters['companies_id'] !== "0") {
                        $company = \App\Models\Company::find($filters['companies_id']);
                        $companyName = $company ? $company->name : 'غير معروف';
                    } elseif (isset($filters['companies_id']) && $filters['companies_id'] === "0") {
                        $companyName = 'بدون شركة';
                    }
                @endphp

                @if($companyName)
                    <span>الشركة: {{ $companyName }}</span>
                @endif
                @if(!empty($filters['request_number']))
                    <span>رقم الطلب: {{ $filters['request_number'] }}</span>
                @endif
                @if(!empty($filters['cards_number']))
                    <span>عدد البطاقات: {{ $filters['cards_number'] }}</span>
                @endif
                @if(!empty($filters['request_statuses_id']))
                    @php
                        $status = \App\Models\RequestStatus::find($filters['request_statuses_id']);
                    @endphp
                    <span>الحالة: {{ $status->name ?? '-' }}</span>
                @endif
                @if(!empty($filters['fromdate']))
                    <span>من: {{ $filters['fromdate'] }}</span>
                @endif
                @if(!empty($filters['todate']))
                    <span>إلى: {{ $filters['todate'] }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<hr>

@if($requests->isEmpty())
    <p style="text-align:center; margin-top: 20px;">لا توجد طلبات لعرضها</p>
@else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الطلب</th>
                <th>الشركة</th>
                <th>المستخدم</th>
                <th>عدد البطاقات</th>
                <th>حالة الطلب</th>
                <th>تاريخ الطلب</th>
                <th>حالة التنزيل</th>
                <th>تاريخ التنزيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $index => $req)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $req->request_number }}</td>
                    <td>{{ $req->companies->name ?? '-' }}</td>
                    <td>{{ $req->company_users->username ?? '-' }}</td>
                    <td>{{ $req->cards_number }}</td>
                    <td>{{ $req->request_statuses->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->created_at)->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($req->request_statuses_id == 2)
                            {{ $req->uploded == 0 ? 'لم يتم التنزيل' : 'تم التنزيل' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $req->uploded_datetime ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>
