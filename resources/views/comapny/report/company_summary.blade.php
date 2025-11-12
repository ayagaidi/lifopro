@extends('comapny.app')
@section('title', 'التقرير المجمع لاصدارات المكاتب')

@section('content')
<div class="box-content">
    <h4 class="box-title">التقرير المجمع لاصدارات المكاتب</h4>

    {{-- فلتر التاريخ --}}
    <form method="GET" action="{{ route('company/report/companySummary') }}" class="mb-4">
        <div class="row">
            <div class="form-group col-md-4">
                <label>من:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="form-group col-md-4">
                <label>إلى:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="form-group col-md-4 d-flex align-items-end" style="margin-top: 31px;">
                <button type="submit" class="btn btn-primary w-100">عرض التقرير</button>
            </div>
        </div>
    </form>

    @if(empty($startDate) || empty($endDate))
        <div class="alert alert-info text-center">
            الرجاء اختيار <strong>الفترة</strong> لعرض التقرير.
        </div>
    @elseif(count($data))
        {{-- زر PDF (لو عندك روت لتوليد PDF) --}}
        @php
            $query = http_build_query([
                'start_date' => request('start_date'),
                'end_date'   => request('end_date'),
            ]);
        @endphp
        {{-- مثال: <a href="{{ route('report.officeSummary.myCompany.pdf') . '?' . $query }}" class="btn btn-danger mb-3" target="_blank">طباعة PDF</a> --}}
  <div class="mb-3 text-left">
            @php
                $query = http_build_query([
                    'start_date' => request('start_date'),
                    'end_date' => request('end_date'),
                ]);
            @endphp
            <a href="{{ route('company/report/companySummary/pdf') . '?' . $query }}" class="btn btn-danger" target="_blank">
                طباعة التقرير PDF
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>المكتب</th>
                        <th>عدد البطاقات المصدرة</th>
                        <th>عدد البطاقات الملغية</th>
                        <th>صافي القسط</th>
                        <th>الضريبة</th>
                        <th>الدمغة</th>
                        <th>الإشراف</th>
                        <th>رسوم الإصدار</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
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
                    @endforeach
                </tbody>
                {{-- سطر الإجماليات --}}
                <tfoot>
                    @php
                        $sum = [
                            'active'      => $data->sum('active_count'),
                            'issued'      => $data->sum('issued_count'),
                            'canceled'    => $data->sum('canceled_count'),
                            'net'         => $data->sum('net_premium'),
                            'tax'         => $data->sum('tax'),
                            'stamp'       => $data->sum('stamp'),
                            'supervision' => $data->sum('supervision'),
                            'fee'         => $data->sum('issuing_fee'),
                            'total'       => $data->sum('total'),
                        ];
                    @endphp
                    <tr class="font-weight-bold">
                        <td>الإجمالي</td>
                        <td>{{ $sum['issued'] }}</td>
                        <td>{{ $sum['canceled'] }}</td>
                        <td>{{ number_format($sum['net'], 2) }}</td>
                        <td>{{ number_format($sum['tax'], 2) }}</td>
                        <td>{{ number_format($sum['stamp'], 2) }}</td>
                        <td>{{ number_format($sum['supervision'], 2) }}</td>
                        <td>{{ number_format($sum['fee'], 2) }}</td>
                        <td>{{ number_format($sum['total'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-warning text-center">
            لا توجد بيانات ضمن الفترة المحددة.
        </div>
    @endif
</div>
@endsection
