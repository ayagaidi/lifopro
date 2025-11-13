@extends('layouts.app')
@section('title', 'سجل النشاط - تغيير كلمات المرور')

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
                    <input type="text" id="activity_type" class="form-control" placeholder="نوع العملية">
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

            <div class="table-responsive" data-pattern="priority-columns">
                {{-- Report Table --}}
                <table id="activityLogsTable" class="table table-bordered table-hover dataTable table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المستخدم</th>
                            <th>نوع العملية</th>
                            <th>تاريخ ووقت العملية</th>
                            <th>حالة التنفيذ</th>
                            <th>السبب</th>
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
    var table = $('#activityLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("logs/activity") }}',
            data: function(d) {
                d.user_name = $('#user_name').val();
                d.activity_type = $('#activity_type').val();
                d.status = $('#status').val();
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_name' },
            { data: 'activity_type' },
            { data: 'activity_date' },
            { data: 'status', orderable: false },
            { data: 'reason' }
        ],
        "language": {
                                                "url": "{{asset('Arabic.json')}}" //arbaic lang

                                            },
        pageLength: 20,
        responsive: true
    });

    $('#searchBtn').on('click', function() {
        table.ajax.reload();
    });

    $('#user_name, #activity_type, #status, #start_date, #end_date').on('keyup change', function() {
        table.ajax.reload();
    });
});
</script>

@endsection
