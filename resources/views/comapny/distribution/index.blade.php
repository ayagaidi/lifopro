@extends('comapny.app')
@section('title', 'التوزيع')

@section('content')
<script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.delete-distribution', function(e) {
        e.preventDefault();
        let deleteUrl = $(this).data('url');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لا يمكنك التراجع بعد الحذف!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire('تم الحذف!', 'تم حذف التوزيعة بنجاح.', 'success');
                        $('.dataTable').DataTable().ajax.reload(); // تحديث الجدول بعد الحذف
                    },
                    error: function() {
                        Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف.', 'error');
                    }
                });
            }
        });
    });
</script>

</script>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <a type="button" href="{{ route('company/distribution/create') }}"
                    class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">اضافة عملية توزيع</a>
            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض التوزيعات </h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">

                            <thead>
                                <tr>
                                    <th> المكتب</th>


                                    <th>اجمالي عمليات التوزيع</th>
                                    <th>التفاصيل</th>
                                    <th>حذف</th>



                                   




                                </tr>
                            </thead>
                            <tbody>
                                <script>
                                    $(document).ready(function() {

                                        $('#datatable1').dataTable({
                                            "language": {
                                                "url": "../../Arabic.json" //arbaic lang

                                            },
                                            "lengthMenu": [10, 15, 20, 50, 100],
                                            "bLengthChange": true, //thought this line could hide the LengthMenu
                                            serverSide: false,
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            ajax: '{!! route('company/distribution/distribution') !!}',

                                            columns: [
                                               
                                                {
                                                    data: 'offices_name'
                                                },
                                                {
                                                    data: 'total_cards'
                                                },
                                                {
                                                    data: 'show'
                                                },
                                               {
                                                data:'delete'
                                               }




                                            ],

                                            dom: 'Blfrtip',

                                            buttons: [{
                                                    extend: 'copyHtml5',
                                                    exportOptions: {
                                                        columns: [':visible']
                                                    },
                                                    text: 'نسخ'
                                                },
                                                {
                                                    extend: 'excelHtml5',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    text: 'excel تصدير كـ '

                                                },
                                                {
                                                    extend: 'colvis',
                                                    text: 'الأعمدة'

                                                },
                                            ],

                                        });

                                    });
                                </script>
                            </tbody>

                        </table>
                    </div>
                </div>
                <!-- /.box-content -->
            </div>
            <!-- /.col-xs-12 -->

        </div>
    </div>

@endsection
