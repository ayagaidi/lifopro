@extends('layouts.app')
@section('title', 'التقرير المجمع للشركات')

@section('content')
<div class="box-content">
    <h4 class="box-title">التقرير المجمع حسب الشركات</h4>

    {{-- فلتر البحث --}}
    <form method="GET" action="{{ route('report/companySummary') }}" class="mb-4">
        <div class="row">
            <div class="form-group col-md-3">
                <label>من:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="form-group col-md-3">
                <label>إلى:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="form-group col-md-3">
                <label>الشركة:</label>
                <select name="company_id" class="form-control">
                    <option value="">-- اختر الشركة --</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3 d-flex align-items-end" style="margin-top: 31px;">
                <button type="submit" class="btn btn-primary w-100">عرض التقرير</button>
            </div>
        </div>
    </form>

    {{-- جدول النتائج --}}
    @if(count($data))
        <div class="mb-3 text-left">
            @php
                $query = http_build_query([
                    'start_date' => request('start_date'),
                    'end_date' => request('end_date'),
                    'company_id' => request('company_id')
                ]);
            @endphp
            <a href="{{ route('report/companySummary/pdf') . '?' . $query }}" class="btn btn-danger" target="_blank">
                طباعة التقرير PDF
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>الشركة</th>
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
                            <td>{{ $row->company_name }}</td>
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
                <tfoot>
                    <tr>
                        <th>الإجمالي</th>
                        <th>{{ $data->sum('issued_count') }}</th>
                        <th>{{ $data->sum('canceled_count') }}</th>
                        <th>{{ number_format($data->sum('net_premium'), 2) }}</th>
                        <th>{{ number_format($data->sum('tax'), 2) }}</th>
                        <th>{{ number_format($data->sum('stamp'), 2) }}</th>
                        <th>{{ number_format($data->sum('supervision'), 2) }}</th>
                        <th>{{ number_format($data->sum('issuing_fee'), 2) }}</th>
                        <th>{{ number_format($data->sum('total'), 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-warning text-center">
            الرجاء اختيار <strong>الفترة</strong> أو <strong>الشركة</strong> أو كلاهما لعرض التقرير.
        </div>
    @endif
</div>
@endsection
