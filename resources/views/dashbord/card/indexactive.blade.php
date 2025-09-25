@extends('layouts.app')
@section('title', 'كافة البطاقة المعينة')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('card/active') }}">البطاقات</a> / كافة البطاقات المعينة
            </h4>
            <div class="text-left">
            </div>
        </div>
    </div>

    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <h4 class="box-title">عرض البطاقات</h4>
                                <a href="{{ route('card/activeall/pdf') }}" target="_blank" class="btn btn-rounded">🖨️ طباعة</a>

                <div class="table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                               
                                <th>رقم البطاقة</th>
                                <th>الشركة</th>
                                <th>الحالة</th>
                                <th>رقم الطلب</th>
                                <th>تاريخ الادارج في حساب الشركة</th>
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
    $(document).ready(function () {
        $('#loader-overlay').show();

        $('#datatable1').DataTable({
            language: { url: "{{ asset('Arabic.json') }}" },
            processing: true,
            serverSide: true,
            ajax: '{{ route("card/activeall") }}',
            columns: [
               
                { data: 'card_number' },
                { data: 'companies_name' },
                { data: 'cardstautes_name' },
                { data: 'request_number' },
                { data: 'uploded_datetime' }
            ],
            dom: 'Blfrtip',
            buttons: [
                { extend: 'copy', text: 'نسخ' },
                { extend: 'excel', text: 'تصدير كـ Excel' }
            ]
        });

        $('#loader-overlay').hide();
    });
</script>
@endsection
