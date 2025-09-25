@extends('layouts.app')
@section('title', 'تقرير المخزون')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('report/stock') }}">التقارير</a> / تقرير المخزون
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">عرض المخزون</h4>

            <div class="mb-3">
                <a href="{{ route('report/stockpdf') }}" target="_blank"
                   class="btn btn-primary btn-bordered waves-effect waves-light">
                    PDF
                </a>
            </div>

            <div class="table-responsive" data-pattern="priority-columns">
                <table id="datatable1"
                       class="table table-bordered table-hover js-basic-example dataTable table-custom"
                       style="cursor: pointer;">
                    <thead>
                        <tr>
                            <td colspan="2" class="text-white font-weight-bold"
                                style="background-color: #ba8173; font-size: larger;">
                                اجمالي مخزون الاتحاد
                            </td>
                            <td colspan="6" class="text-white font-weight-bold"
                                style="background-color: #ba8173; font-size: larger;">
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
                                <td>{{ $item->cancelled ?? 0 }} بطاقة</td>
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

{{-- DataTables init --}}
<script>
    $(document).ready(function () {
        $('#datatable1').DataTable({
            language: {
                url: "{{ asset('Arabic.json') }}"
            },
            lengthMenu: [10, 20, 30, 50],
            paging: true,
            ordering: true,
            searching: true,
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'نسخ',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'تصدير كـ Excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });
    });
</script>
@endsection
