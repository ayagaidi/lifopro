@extends('layouts.app')
@section('title', 'سجل النشاط - تغيير كلمات المرور')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#company_name').select2({
        placeholder: "اختر الشركة",
        allowClear: true,
        language: "ar"
    });
    $('#office_name').select2({
        placeholder: "اختر المكتب",
        allowClear: true,
        language: "ar"
    });
    $('#status').select2({
        placeholder: "جميع الحالات",
        allowClear: true,
        language: "ar"
    });
});
</script>

{{-- Page Level Loader --}}
<div id="page-loader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: none; justify-content: center; align-items: center; flex-direction: column;">
    <div class="spinner-border text-light" role="status">
        <span class="sr-only">جاري التحميل...</span>
    </div>
    <p class="text-light mt-2">جاري تحميل البيانات...</p>
</div>

<div class="row small-spacing" style="margin-top: 50px">
    <div class="col-md-12">
        <div class="box-content">
            {{-- Search Filters --}}
            <div class="row mb-3">
                @php
                    $companies = App\Models\Company::all();
                @endphp

                <div class="col-md-2">
                    <input type="text" id="performed_by" class="form-control" placeholder="قام بالعملية">
                </div>
                <div class="col-md-2">
                    <input type="text" id="target_user" class="form-control" placeholder="المستخدم المستهدف">
                </div>
                <div class="col-md-2">
                    <select id="company_name" class="form-control select2">
                        <option value="">اختر الشركة</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->name }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="office_name" class="form-control select2">
                        <option value="">اختر المكتب</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" id="activity_type" class="form-control" placeholder="نوع العملية">
                </div>
                <div class="col-md-2">
                    <select id="status" class="form-control select2">
                        <option value="">جميع الحالات</option>
                        <option value="success">نجح</option>
                        <option value="failure">فشل</option>
                    </select>
                </div>

            </div>
            <br/>
            <div class="row mb-3">
                 <div class="col-md-3">
                    <input type="date" id="start_date" class="form-control" placeholder="تاريخ البداية">
                </div>
                <div class="col-md-3">
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
                <table id="activityLogsTable" class="table table-bordered table-hover dataTable table-custom" style="width: 100% ">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>قام بالعملية</th>
                            <th>المستخدم المستهدف</th>
                            <th>اسم الشركة</th>
                            <th>اسم المكتب</th>
                            <th>وصف العملية</th>
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
                d.performed_by = $('#performed_by').val();
                d.target_user = $('#target_user').val();
                d.company_name = $('#company_name').val();
                d.office_name = $('#office_name').val();
                d.activity_type = $('#activity_type').val();
                d.status = $('#status').val();
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'performed_by' },
            { data: 'target_user' },
            { data: 'company_name' },
            { data: 'office_name' },
            { data: 'detailed_description' },
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

    // hide the table container until user performs a search
    var tableContainer = $('#activityLogsTable').closest('.table-responsive');
    tableContainer.hide();

    $('#searchBtn').on('click', function() {
        var hasFilters = $('#performed_by').val().trim() !== '' || $('#target_user').val().trim() !== '' || $('#company_name').val().trim() !== '' || $('#office_name').val().trim() !== '' || $('#activity_type').val().trim() !== '' || $('#status').val() !== '' || $('#start_date').val() !== '' || $('#end_date').val() !== '';
        if (!hasFilters) {
            Swal.fire({
                title: 'تحذير',
                text: 'الرجاء تحديد معيار بحث واحد على الأقل.',
                icon: 'warning',
                confirmButtonText: 'حسناً'
            });
            return;
        }
        // show loader
        $('#page-loader').show();
        // show table area and load results
        tableContainer.show();
        table.ajax.reload();
    });

    // Hide page loader after AJAX request
    table.on('xhr', function() {
        $('#page-loader').hide();
    });

    // Dynamic office loading based on company selection
    $('#company_name').on('change', function() {
        var companyName = $(this).val();
        if (companyName) {
            $.ajax({
                url: '{{ route("logs/getOfficesByCompany") }}',
                method: 'GET',
                data: { company_name: companyName },
                success: function(data) {
                    var officeSelect = $('#office_name');
                    officeSelect.empty();
                    officeSelect.append('<option value="">اختر المكتب</option>');
                    $.each(data, function(index, office) {
                        officeSelect.append('<option value="' + office.name + '">' + office.name + '</option>');
                    });
                }
            });
        } else {
            $('#office_name').empty().append('<option value="">اختر المكتب</option>');
        }
    });
});
</script>

@endsection
