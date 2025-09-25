@extends('comapny.app')
@section('title', 'كافة البطاقات الملغية')

@section('content')
<!-- Loader -->
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
                <a href="{{ route('company/card/cancel') }}">البطاقات</a> / كافة البطاقات الملغية
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">عرض البطاقات</h4
>
                        <a href="{{ url('company/card/cancel/pdf') }}" target="_blank" class="btn btn-primary no-print">طباعة   PDF</a>

            <div class="table-responsive">
                <table id="datatable1" class="table table-bordered table-hover table-custom">
                    <thead>
                        <tr>
                            <th>رقم البطاقة</th>
                            <th>حالة البطاقة</th>
                            <th>رقم الطلب  </th>
                                                        <th>تاريخ اصدار البطاقة </th>

                            <th>تاريخ إلغاء البطاقة</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables & Export Scripts -->


<script>

$(document).ready(function () {
    $('#loader-overlay').show();

    let table = $('#datatable1').DataTable({
        language: {
            url: "{{ asset('Arabic.json') }}"
        },
        lengthMenu: [10, 15, 20, 50, 100],
        serverSide: false,
        processing: true,
                                        ordering: false,

        ajax: {
            url: '{{ route("company/card/allcancel") }}',
            type: 'GET',
            beforeSend: function () {
                $('#loader-overlay').show();
            },
            complete: function () {
                $('#loader-overlay').hide();
            },
            error: function () {
                $('#loader-overlay').hide();
                alert('حدث خطأ أثناء تحميل البيانات.');
            }
        },
        columns: [
            { data: 'card_number' },
            { data: 'cardstautes_name' },
            { data: 'request_number' },
            { data: 'issuing_date' },
                        { data: 'card_delete_date' }

        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: 'نسخ',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    modifier: { page: 'all' }
                }
            },
            {
                extend: 'excelHtml5',
                text: 'تصدير الكل',
                action: function (e, dt, button, config) {
                    let self = this;
                    let oldStart = dt.settings()[0]._iDisplayStart;

                    $('#loader-overlay').show();

                    dt.one('preXhr', function (e, s, data) {
                        data.start = 0;
                        data.length = -1; // fetch all records, if your backend supports it
                    });

                    dt.one('preDraw', function (e, settings) {
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);

                        dt.one('preXhr', function (e, s, data) {
                            data.start = oldStart;
                            data.length = dt.page.len(); // reset to original
                        });

                        setTimeout(function () {
                            dt.ajax.reload();
                            $('#loader-overlay').hide();
                        }, 100);

                        return false;
                    });

                    dt.ajax.reload();
                }
            }
        ]
    });
});

</script>
@endsection
