@extends('comapny.app')
@section('title', 'كافة البطاقات المعينة')

@section('content')

<!-- Loader Overlay -->
<div id="loader-overlay" style="
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    color: #333;">
    يرجى الانتظار... جاري تحميل البيانات
</div>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('company/card/active') }}">البطاقات</a> / كافة البطاقات المعينة
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">عرض البطاقات</h4>
            <a href="{{ url('company/card/activeall/pdf') }}" target="_blank" class="btn btn-primary no-print">طباعة   PDF</a>
            <div class="table-responsive">
                <table id="datatable1" class="table table-bordered table-hover table-custom">
                    <thead>
                        <tr>
                            <th>رقم البطاقة</th>
                            <th>حالة البطاقة</th>
                            <th>رقم الطلب</th>
                            <th>تاربخ قبول الطلب </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTable Script -->
<script>
$(document).ready(function () {
    $('#loader-overlay').show();

    $('#datatable1').DataTable({
    language: { url: "{{ asset('Arabic.json') }}" },
    lengthMenu: [10, 15, 20, 50, 100],
    serverSide: true,
    processing: true,
            ordering: false, // ⬅️ هنا تم تعطيل الترتيب تماماً
    ajax: '{{ route("company/card/activeall") }}',
    columns: [
        { data: 'card_number', name: 'card_number' },
        { data: 'cardstautes_name', name: 'cardstautes_name' },
        { data: 'request_number', name: 'request_number' },
                {
  data: 'requests.uploded_datetime',
  name: 'requests.uploded_datetime',
  render: function (data, type, row) {
    return data ? data : 'غير متوفر'; // أو يمكن تركها فارغة ''
  }
}


    ],
    dom: 'Blfrtip',
    buttons: [
        { extend: 'copyHtml5', text: 'نسخ', exportOptions: { columns: ':visible' } },
        { extend: 'excelHtml5', text: 'تصدير Excel', exportOptions: { columns: ':visible' } }
    ],
    columnDefs: [
        { targets: 0, orderable: false, searchable: false }
    ]
});
                                        $('#loader-overlay').hide();

});
</script>

@endsection
