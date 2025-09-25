@extends('layouts.app')
@section('title', 'الصلاحيات')

@section('content')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.deleteitem', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            event.preventDefault();
            Swal.fire({
                title: "حدف الصلاحية",

                html: "هل انت متاكد من حدف الصلاحية",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonColor: "#FF0000",
                confirmButtonText: `تاكيد الحذف`,
                cancelButtonText: `الغاء`,
            }).then((result) => {
                if (result.isConfirmed) {


                    $.ajax({
                        type: "DELETE",
                        "_token": "{{ csrf_token() }}",
                        url: '../../roles/delete/' + id,

                        success: function(data) {
                            if (data.code == 1) {
                                swal.fire("عملية ناجحة", "تمت عمليةالحذف  بنجاح", "success");
                                // history.back()
                                // window.location.href =  + data.id;
                                window.location.reload()
                            } else {
                                swal.fire("عملية الحذف فشلت",
                                    "لا يمكن حدف الفرع لان تم استخدامه في جدول اخر ",
                                    "warning");
                                window.location.reload()
                                // history.back()
                                // window.location.href = + data.id;

                            }
                        }
                    });
                }

            });
        });
    </script>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <a href="{{ route('roles/create') }}" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">
                    اضافة صلاحية</a>
            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض صلاحية</h4>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>
                                    <td>الصلاحية</td>
                                    <td></td>



                                <tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>

                                        <td>{{ $role->name }}</td>

                                        <td>
                                            <a style="color: #f97424;" href="{{ route('roles/show', $role->id) }}"><i
                                                    class="fa  fa-file"> </i></a>

                                        </td>
                                        <td>
                                            <a style="color: #f97424;" href="{{ route('roles/edit', $role->id) }}"><i
                                                    class="fa  fa-edit"> </i></a>
                                        </td>

                                        


                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('#datatable1').dataTable({
                "language": {
                    "url": "../Arabic.json" //arbaic lang

                },
                "lengthMenu": [10, 15],
                "bLengthChange": true, //thought this line could hide the LengthMenu
                serverSide: false,
                paging: true,
                searching: true,
                ordering: true,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',


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

@endsection
