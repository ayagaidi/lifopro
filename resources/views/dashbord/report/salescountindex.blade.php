@extends('layouts.app')

@section('title', 'تقرير عدد البطاقات المباعة')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('report/salescount') }}">التقارير</a> / تقرير اجمالي عدد البطاقات المباعة
            </h4>
        </div>
    </div>
    

    <div class="col-md-12">
        <div class="box-content">

            <form method="GET" action="{{ route('report/salescount') }}">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="control-label">الشركة</label>
                        <select name="companies_id" id="companies_id"
                                class="form-control select2 wd-250" data-placeholder="Choose one">
                            <option value="">اختر</option>
                            @forelse ($companies as $com)
                                <option value="{{ $com->id }}"
                                    {{ (isset($filters['companies_id']) && (int)$filters['companies_id'] === (int)$com->id) ? 'selected' : '' }}>
                                    {{ $com->name }}
                                </option>
                            @empty
                                <option value="">لايوجد شركات</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label">من</label>
                        <input name="fromdate" id="fromdate" type="date"
                               class="form-control wd-250"
                               value="{{ $filters['fromdate'] ?? request('fromdate') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label">إلى</label>
                        <input name="todate" id="todate" type="date"
                               class="form-control wd-250"
                               value="{{ $filters['todate'] ?? request('todate') }}">
                    </div>
                </div>

                <div class="form-group col-md-12" style="text-align: left;">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        بحث
                    </button>
                </div>
            </form>
        </div>

        <div class="box-content">
            <h4 class="box-title"> عدد البطاقات المباعة</h4>

            <div class="table-responsive">
                <table id="datatable1" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td colspan="3" class="text-center text-white font-weight-bold"
                                style="font-size: larger; background-color: #ba8173;">
                                 عدد البطاقات المباعة : {{ $insurance_total }}
                                @if(!empty($filters['fromdate']) && !empty($filters['todate']))
                                    <div style="font-size: small">للفترة:
                                        {{ $filters['fromdate'] }} – {{ $filters['todate'] }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>الشركة</th>
                            <th>اجمالي عدد البطاقات </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($insurance_totalt as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->companies_name }}</td>
                                <td>{{ $item->counts }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">لا يوجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Init -->
<script>
$(document).ready(function () {
    $('#datatable1').DataTable({
        language: {
            url: "{{ asset('Arabic.json') }}"
        },
        dom: 'Blfrtip',
        buttons: [
            { extend: 'copyHtml5',  exportOptions: { columns: [':visible'] }, text: 'نسخ' },
            { extend: 'excelHtml5', exportOptions: { columns: ':visible'   }, text: 'تصدير Excel' },
            {
                extend: 'pdfHtml5',
                exportOptions: { columns: ':visible' },
                text: 'تصدير PDF',
                customize: function (doc) {
                    doc.defaultStyle.font = 'Cairo';
                    doc.pageSize = 'A4';
                    // محاذاة RTL
                    doc.content.forEach(function (item) {
                        if (item.table && item.table.body) {
                            item.table.body.forEach(function (row) {
                                row.forEach(function (cell) {
                                    if (typeof cell.text === 'string') {
                                        cell.alignment = 'right';
                                        cell.direction  = 'rtl';
                                    }
                                });
                            });
                        }
                    });
                    if (doc.styles) {
                        ['tableHeader', 'tableBodyEven', 'tableBodyOdd'].forEach(function (style) {
                            if (doc.styles[style]) {
                                doc.styles[style].alignment = 'right';
                                doc.styles[style].direction  = 'rtl';
                            }
                        });
                    }
                }
            }
        ]
    });
});
</script>
@endsection
