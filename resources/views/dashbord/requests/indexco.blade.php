@extends('layouts.app')
@section('title', 'ادراة الطلبات')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // إظهار اللودر عند إرسال أي فورم
            $('form').on('submit', function (event) {
                $('#loader-overlay').show();
            });
        });
    </script>

    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title"><a href="{{ route('cardrequests/company') }}">طلبات بطاقات التامين</a>/ طلب شركات التأمين</h4>
            </div>
        </div>

        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض الطلبات</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                               class="table table-bordered table-hover js-basic-example dataTable table-custom"
                               style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>الشركة</th>
                                    <th>المستخدم</th>
                                    <th>عدد البطاقات</th>
                                    <th>حالة الطلب</th>
                                    <th>تاريخ الطلب</th>
                                    <th>قبول الطلب</th>
                                                                        <th>رفض الطلب</th>

                                    <th>تاريخ تنزيل البطاقات</th>
                                    <th>المستخدم الذي قام بالإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // تعريف الجدول في متغير لتتمكن من إعادة تحميله لاحقًا
            var table = $('#datatable1').DataTable({
                "language": {
                    "url": "{{ asset('Arabic.json') }}"
                },
                "serverSide": true,
                "ajax": '{!! route('cardrequests/all/company') !!}',
                "columns": [
                    { "data": "request_number" },
                    { "data": "companies_name" },
                    { "data": "requesby" },
                    { "data": "cards_number" },
                    { "data": "request_statuses_name" },
                    { "data": "created_at" },
                    { "data": "accept" },
                                        { "data": "reject" },

                    
                    { "data": "uploded_datetime" },
                    { "data": "action_user" }
                ],
                "processing": true,
                "paging": true,
                "ordering": false,
                "dom": 'Blfrtip',
                "buttons": [
                    { extend: 'copyHtml5', text: 'نسخ' },
                    { extend: 'excelHtml5', text: 'تصدير كـ Excel' },
                    { extend: 'colvis', text: 'إظهار الأعمدة' }
                ]
            });

            // حدث الضغط على زر قبول الطلب
            $(document).on('click', '.button', function () {
                var requestId = $(this).data('id');
                var rowData = table.row($(this).closest('tr')).data();
                var requestNumber = rowData.request_number;
                var companyName = rowData.companies_name;
                var cardsNumber = rowData.cards_number;

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `هل أنت متأكد من الموافقة على الطلب ${requestNumber} لشركة ${companyName} بعدد وثائق ${cardsNumber}؟`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، قم بالقبول',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $('#loader-overlay').show();

                    $.ajax({
                        url: "{{ url('cardrequests/acceptrequest') }}/" + requestId,
                        type: "GET",
                        success: function (response) {
                            $('#loader-overlay').hide();

                            let title, icon;
                            switch (response.status) {
                                case 'success':
                                    title = 'تم بنجاح!';
                                    icon = 'success';
                                    break;
                                case 'warning':
                                    title = 'تنبيه!';
                                    icon = 'warning';
                                    break;
                                case 'error':
                                default:
                                    title = 'خطأ!';
                                    icon = 'error';
                            }

                            Swal.fire({
                                title: title,
                                text: response.message,
                                icon: icon,
                                confirmButtonText: 'حسنًا'
                            }).then(() => {
                                // إعادة تحميل البيانات دون الرجوع للصفحة الأولى
                                table.ajax.reload(null, false);
                            });
                        },
                        error: function () {
                            $('#loader-overlay').hide();
                            Swal.fire({
                                title: 'خطأ!',
                                text: "حدث خطأ أثناء معالجة الطلب.",
                                icon: 'error',
                                confirmButtonText: 'حسنًا'
                            });
                        }
                    });
                });
            });

            // حدث الضغط على زر رفض الطلب
            $(document).on('click', '.reject-button', function () {
                var requestId = $(this).data('id');
                var rowData = table.row($(this).closest('tr')).data();
                var requestNumber = rowData.request_number;
                var companyName = rowData.companies_name;
                var cardsNumber = rowData.cards_number;

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `هل أنت متأكد من رفض الطلب ${requestNumber} لشركة ${companyName} بعدد وثائق ${cardsNumber}؟`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، قم بالرفض',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $('#loader-overlay').show();

                    $.ajax({
                        url: "{{ url('cardrequests/rejectrequest') }}/" + requestId,
                        type: "GET",
                        success: function (response) {
                            $('#loader-overlay').hide();

                            Swal.fire({
                                title: 'تم بنجاح!',
                                text: "تم رفض الطلب بنجاح.",
                                icon: 'success',
                                confirmButtonText: 'حسنًا'
                            }).then(() => {
                                // إعادة تحميل البيانات دون الرجوع للصفحة الأولى
                                table.ajax.reload(null, false);
                            });
                        },
                        error: function () {
                            $('#loader-overlay').hide();
                            Swal.fire({
                                title: 'خطأ!',
                                text: "حدث خطأ أثناء رفض الطلب.",
                                icon: 'error',
                                confirmButtonText: 'حسنًا'
                            });
                        }
                    });
                });
            });
        });
    </script>
@endsection
