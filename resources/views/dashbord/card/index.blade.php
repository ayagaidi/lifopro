@extends('layouts.app')
@section('title', 'كافة البطاقات')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title"><a href="{{ route('card') }}">البطاقات</a> / كافة البطاقات</h4>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">عرض البطاقات</h4>
            <div class="table-responsive">
                <table id="datatable1" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>رقم البطاقة</th>
                            <th>إسم الشركة</th>
                            <th>حالة البطاقة</th>
                            <th>رقم الطلب</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#datatable1').DataTable({
            language: {
                url: "{{ asset('Arabic.json') }}" // Path to Arabic localization file
            },
            processing: true, // Show loading indicator
            serverSide: true, // Enable server-side processing
            ajax: {
                url: '{!! route('card/all') !!}', // Laravel route for data
                type: 'GET'
            },
            columns: [
                
                { data: 'card_number', name: 'card_number' },
                { data: 'companies_id', name: 'companies_id' },
                { data: 'cardstautesname', name: 'cardstautesname' },
                { data: 'request_numberr', name: 'request_numberr' },
            ],
            lengthMenu: [10, 15, 20, 50, 100], // Define pagination options
            dom: 'Blfrtip', // Layout for buttons
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'نسخ',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'excelHtml5',
                    text: 'تصدير كـ Excel',
                    exportOptions: { columns: ':visible' }
                }
            ]
        });
    });
</script>
@endsection
