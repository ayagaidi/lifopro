@extends('layouts.app')
@section('title', 'كافة البطاقات المصدرة')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('card/sold') }}">البطاقات</a> / كافة البطاقات المصدرة
            </h4>
        </div>
    </div>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <h4 class="box-title">عرض البطاقات</h4>
                <div class="mb-3 text-left">
    <a href="{{ route('card/soldall/pdf') }}" target="_blank" class="btn btn-round">
        <i class="fa fa-print"></i> طباعة تقرير PDF
    </a>
</div>

                <div class="table-responsive" data-pattern="priority-columns">
                    <table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr>
                                
                                <th>رقم البطاقة</th>
                                <th>إسم الشركة</th>
                                <th>حالة البطاقة</th>
                                <th>رقم الطلب</th>
                                <th>تاريخ الاصدار</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Show loader before initializing DataTable
        $('#loader-overlay').show();

        // Initialize DataTable
        $('#datatable1').DataTable({
            language: {
                url: "{{ asset('Arabic.json') }}" // Path to Arabic localization file
            },
            processing: true, // Display processing indicator
            serverSide: true, // Enable server-side processing
            ajax: '{!! route('card/allsold') !!}', // URL for fetching data
        columns: [
    { data: 'card_number' },
    { data: 'companies_name' },
    { data: 'cardstautes_name' },
    { data: 'request_number' },
                                    { data: 'issuing_datee' }

],

            lengthMenu: [10, 15, 20, 50, 100], // Pagination options
            dom: 'Blfrtip', // Layout with buttons
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: ':visible' // Copy visible columns only
                    },
                    text: 'نسخ'
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible' // Export visible columns only
                    },
                    text: 'تصدير كـ Excel'
                }
            ]
        });

        // Hide loader after initializing DataTable
        $('#loader-overlay').hide();
    });
</script>
@endsection
