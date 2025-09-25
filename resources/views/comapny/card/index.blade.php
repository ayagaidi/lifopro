@extends('comapny.app')
@section('title', 'كافة البطاقة')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('company/card') }}">البطاقات</a>/ كافة البطاقات</h4>

            </div>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض البطاقات</h4>
 <a href="{{ url('company/card/all/pdf') }}" target="_blank" class="btn btn-round" style="margin-bottom: 10px;">
    طباعة PDF
</a>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="datatable1"
                            class="table table-bordered table-hover js-basic-example dataTable table-custom "
                            style="cursor: pointer;">
                            <thead>
                                <tr>

                                    <th>رقم البطاقة</th>
                                   
                                    <th>حالة البطاقة</th>
                                    <th>رقم الطلب </th>






                                </tr>
                            </thead>
                            <tbody>
                                <script>
                                 $(document).ready(function() {
    $('#loader-overlay').show();

    $('#datatable1').DataTable({
        language: {
            url: "{{ asset('Arabic.json') }}"
        },
        lengthMenu: [10, 15, 20, 50, 100],
        serverSide: true,
        processing: true,
        ajax: '{!! route("company/card/all") !!}',
        columns: [
            { data: 'card_number', name: 'card_number' },
           
            { data: 'cardstautes.name', name: 'cardstautes.name' },
            { data: 'requests.request_number', name: 'requests.request_number' },
        ],
        dom: 'Blfrtip',
        buttons: [
            { extend: 'copyHtml5', text: 'نسخ', exportOptions: { columns: ':visible' } },
            { extend: 'excelHtml5', text: 'تصدير Excel', exportOptions: { columns: ':visible' } }
        ],
        drawCallback: function() {
            $('#loader-overlay').hide();
        }
    });
});

                                </script>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
