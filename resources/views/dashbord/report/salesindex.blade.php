@extends('layouts.app')
@section('title', 'تقرير المبيعات')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('report/sales') }}">التقارير</a> / تقرير اجمالي المبيعات
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">عرض المبيعات</h4>

            <!-- Print PDF Button -->
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('report/sales/pdf') }}" target="_blank" class="btn btn-primary">
                    <i class="fa fa-print"></i> طباعة التقرير PDF
                </a>
            </div>

            <div class="table-responsive">
                <table id="datatable1" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td colspan="3" class="text-center text-white font-weight-bold"
                                style="font-size: larger; background-color: #ba8173;">
                                اجمالي قيمة المبيعات البطاقات: {{ number_format($insurance_total, 2) }} دينار
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>الشركة</th>
                            <th>اجمالي المبيعات (د.ل)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($insurance_totalt as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->companies_name }}</td>
                                <td>{{ number_format($item->total_insurance, 2) }} دينار</td>
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
            {
                extend: 'copyHtml5',
                exportOptions: { columns: [':visible'] },
                text: 'نسخ'
            },
            {
                extend: 'excelHtml5',
                exportOptions: { columns: ':visible' },
                text: 'تصدير Excel'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: { columns: ':visible' },
                text: 'تصدير PDF',
                customize: function (doc) {
                    doc.defaultStyle.font = 'Cairo';
                    doc.pageSize = 'A4';

                    doc.content.forEach(function (item) {
                        if (item.table) {
                            item.table.body.forEach(function (row) {
                                row.forEach(function (cell) {
                                    if (typeof cell.text === 'string') {
                                        cell.alignment = 'right';
                                        cell.direction = 'rtl';
                                    }
                                });
                            });
                        }
                    });

                    if (doc.styles) {
                        ['tableHeader', 'tableBodyEven', 'tableBodyOdd'].forEach(style => {
                            if (doc.styles[style]) {
                                doc.styles[style].alignment = 'right';
                                doc.styles[style].direction = 'rtl';
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
