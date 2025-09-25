@extends('comapny.app')
@section('title', 'كافة البطاقات المصدرة')

@section('content')
<div id="loader-overlays" style="
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    color: #333;
    display: none;">
    يرجى الانتظار... جاري تحميل البيانات
</div>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('company/card/sold') }}">البطاقات</a> / المصدرة كافة البطاقات
            </h4>
        </div>
    </div>

    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <h4 class="box-title">عرض البطاقات</h4>
            <a href="{{ url('company/card/sold/pdf') }}" target="_blank" class="btn btn-primary no-print">طباعة   PDF</a>

                <div class="table-responsive" data-pattern="priority-columns">
                    <table id="datatable1"
                           class="table table-bordered table-hover js-basic-example dataTable table-custom"
                           style="cursor: pointer;">
                        <thead>
                            <tr>
                                <th>رقم البطاقة</th>
                                <!--<th>اسم المكتب</th>-->
                                <th>حالة البطاقة</th>
                                <th>رقم الطلب</th>
                                <th>تاريخ الاصدار</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- يتم تعبئة البيانات ديناميكياً عبر Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ نقل الـ script إلى خارج الجدول --}}
<script>
    $(document).ready(function () {
    $('#loader-overlays').show();

        let table = $('#datatable1').DataTable({
            language: {
                url: "{{ asset('Arabic.json') }}"
            },
            lengthMenu: [10, 15, 20, 50, 100],
            bLengthChange: true,
            serverSide: true,
            processing: true,
            paging: true,
            searching: true,
            ordering: false,
            ajax: '{!! route("company/card/allsold") !!}',

            columns: [
                { data: 'card_number' },
                // { data: 'offices' },
                { data: 'cardstautes.name' },
                { data: 'requests.request_number' },
                                { data: 'solddate' }

            ],

            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3],
                        modifier: {
                            page: 'all'
                        }
                    },
                    text: 'نسخ'
                },
                {
                    extend: 'excelHtml5',
                    text: 'تصدير الكل',
                    action: function (e, dt, button, config) {
                        let oldStart = dt.settings()[0]._iDisplayStart;

                        dt.one('preXhr', function (e, s, data) {
                            data.start = 0;
                            data.length = 2147483647;

                            dt.one('preDraw', function (e, settings) {
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);

                                dt.one('preXhr', function (e, s, data) {
                                    data.start = oldStart;
                                });

                                setTimeout(dt.ajax.reload, 0);

                                return false;
                            });
                        });

                        dt.ajax.reload();
                    }
                }
            ]
        });

        $('#loader-overlays').hide();
    });
</script>

@endsection
