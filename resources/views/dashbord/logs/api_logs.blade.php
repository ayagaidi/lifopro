@extends('layouts.app')
@section('title', 'سجل واجهات الربط - API LOG')

@section('content')

<div class="row small-spacing" style="margin-top: 50px">
    <div class="col-md-12">
        <div class="box-content">
            {{-- Search Filters --}}
            <div class="row mb-3">
                <div class="col-md-2">
                    <input type="text" id="user_name" class="form-control" placeholder="اسم المستخدم">
                </div>
                <div class="col-md-2">
                    <input type="text" id="operation_type" class="form-control" placeholder="نوع العملية">
                </div>
                <div class="col-md-2">
                    <select id="status" class="form-control">
                        <option value="">جميع الحالات</option>
                        <option value="success">نجح</option>
                        <option value="failure">فشل</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" id="start_date" class="form-control" placeholder="تاريخ البداية">
                </div>
                <div class="col-md-2">
                    <input type="date" id="end_date" class="form-control" placeholder="تاريخ النهاية">
                </div>
                <div class="col-md-2">
                    <button id="searchBtn" class="btn btn-primary btn-block">بحث</button>
                </div>
            </div>

            {{-- Loader --}}
            <div id="loader" class="text-center" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">جاري التحميل...</span>
                </div>
                <p>جاري تحميل البيانات...</p>
            </div>

            <div class="table-responsive" data-pattern="priority-columns">
                {{-- Report Table --}}
                <table id="apiLogsTable" class="table table-bordered table-hover dataTable table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المستخدم</th>
                            <th>نوع العملية</th>
                            <th>تاريخ ووقت التنفيذ</th>
                            <th>حالة العملية</th>
                            <th>البيانات المرسلة</th>
                            <th>البيانات المستقبلة</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#apiLogsTable').DataTable({
        processing: true,
        serverSide: true,
        deferLoading: 0,
        ajax: {
            url: '{{ route("logs/api") }}',
            data: function(d) {
                d.user_name = $('#user_name').val();
                d.operation_type = $('#operation_type').val();
                d.status = $('#status').val();
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_name' },
            { data: 'operation_type' },
            { data: 'execution_date' },
            { data: 'status', orderable: false },
            { data: 'sent_data', orderable: false },
            { data: 'received_data', orderable: false }
        ],
       "language": {
                                                "url": "{{asset('Arabic.json')}}" //arbaic lang

                                            },
        pageLength: 20,
        responsive: true
    });
    // hide the table container until user performs a search
    var tableContainer = $('#apiLogsTable').closest('.table-responsive');
    tableContainer.hide();

    $('#searchBtn').on('click', function() {
        var hasFilters = $('#user_name').val().trim() !== '' || $('#operation_type').val().trim() !== '' || $('#status').val() !== '' || $('#start_date').val() !== '' || $('#end_date').val() !== '';
        if (!hasFilters) {
            alert('الرجاء تحديد معيار بحث واحد على الأقل.');
            return;
        }
        // show table area and load results
        tableContainer.show();
        table.ajax.reload();
    });
});
</script>

@endsection
