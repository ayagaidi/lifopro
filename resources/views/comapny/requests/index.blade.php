@extends('comapny.app')
@section('title', 'إدارة الطلبات')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <a type="button" href="{{ route('company/cardrequests/create') }}"
                class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3">إضافة طلب</a>
        </div>
    </div>

    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <h4 class="box-title">عرض الطلبات</h4>
                <div class="table-responsive" data-pattern="priority-columns">
                    <table id="datatable1"
                        class="table table-bordered table-hover js-basic-example dataTable table-custom"
                        style="cursor: pointer;">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>الشركة</th>
                                <th>المستخدم</th>
                                <th>عدد البطاقات</th>
                                <th>حالة الطلب</th>
                                                                <th>تاريخ الطلب</th>

                                <th>تاريخ التنزيل</th>
                                <th>تنزيل البطاقات</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- سكريبت DataTable -->
<script>
    $(document).ready(function() {
        $('#datatable1').dataTable({
            "language": {
                "url": "{{ asset('Arabic.json') }}"
            },
            orderCellsTop: true,
            fixedHeader: true,
            "lengthMenu": [10, 15, 20, 50, 100],
            "bLengthChange": true,
            serverSide: false,
            paging: true,
            searching: true,
            ordering: false,
            order: [[5, 'desc']], // الترتيب حسب "تاريخ الإضافة" تنازليًا
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            ajax: '{!! route('company/cardrequests/all') !!}',

            columns: [
                { data: 'request_number' },
                { data: 'companies_name' },
                { data: 'requesby' },
                { data: 'cards_number' },
                { data: 'request_statuses.name' },
                                { data: 'created_at' },

                { data: 'uploded_datetime' },
                { data: 'uplode' },
            ],

            dom: 'Blfrtip',
            buttons: [
                {
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
                    text: 'تصدير Excel'
                },
                
            ],
        });
    });
</script>
@endsection
