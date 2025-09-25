@extends('layouts.app')
@section('title', 'كافة البطاقة المتبقية')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('card/inactive') }}">البطاقات</a> / كافة البطاقات المتبقية
            </h4>
        </div>
    </div>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <h4 class="box-title">عرض البطاقات</h4>
                 <a href="{{ route('card/inactiveall/pdf') }}" target="_blank" class="btn btn-round">
        <i class="fa fa-print"></i> طباعة تقرير PDF
    </a>
                <div class="table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr>
                                
                                <th>رقم البطاقة</th>
                                <th>إسم الشركة</th>
                                <th>حالة البطاقة</th>
                                <th>رقم الطلب</th>
                                <th>تاريخ ادراج البطاقة</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Show the loader before initializing the DataTable
        $('#loader-overlay').show();

        // Initialize DataTable
        $('#datatable1').DataTable({
            language: {
                url: "{{ asset('Arabic.json') }}" // Path to Arabic localization file
            },
            processing: true, // Enable processing indicator
            serverSide: true, // Enable server-side processing
            ajax: '{!! route('card/inactiveall') !!}', // Fetch data from the appropriate route
          columns: [
   
    { data: 'card_number' },
    { data: 'companies_id' },
    { data: 'cardstautesname' },
    { data: 'request_numberr' },
    { data: 'created_at' }
]
,
            lengthMenu: [10, 15, 20, 50, 100], // Pagination options
            dom: 'Blfrtip', // Table controls (Buttons, etc.)
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: { columns: ':visible' },
                    text: 'نسخ'
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: { columns: ':visible' },
                    text: 'تصدير كـ Excel'
                }
            ]
        });

        // Hide the loader after initializing DataTable
        $('#loader-overlay').hide();
    });
</script>
@endsection
