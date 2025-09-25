@extends('office.app')
@section('title', 'كافة البطاقة')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('office/card/search') }}">البطاقات</a>/ بحث بواسطة </h4>

            </div>
        </div>
        <div class="box-content">

            <form method="POST" enctype="multipart/form-data" action="">
                @csrf
                <div class="row">

                    <div class="form-group  col-md-2">
                        <label for="inputName" class="control-label">حالة البطاقة</label>
                        <select name="cardstautes_id" id="cardstautes_id"
                            class="form-control @error('cardstautes_id') is-invalid @enderror  select2  wd-250"
                            data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                            data-parsley-errors-container="#slErrorContainer" required>
                            <option value="">اختر الحالة </option>

                            @forelse ($Cardstautes as $Cardstaute)
                                <option value="{{ $Cardstaute->id }}"> {{ $Cardstaute->name }}</option>
                            @empty
                                <option value="">لايوجد حالات</option>
                            @endforelse
                        </select>
                        @error('cardstautes_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group  col-md-2">
                        <label for="inputName" class="control-label">رقم الطلب </label>
                        <input type="text" name="request_number"
                            class="form-control @error('request_number') is-invalid @enderror"
                            value="{{ old('request_number') }}" id="request_number" placeholder="رقم الطلب">
                        @error('request_number')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group  col-md-2">
                        <label for="inputName" class="control-label">رقم البطاقة </label>
                        <input type="text" name="card_number"
                            class="form-control @error('card_number') is-invalid @enderror" value="{{ old('card_number') }}"
                            id="card_number" placeholder="رقم البطاقة">
                        @error('card_number')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> من </label>
                        <input name="fromdate" id="fromdate" type="date"
                            class="form-control @error('fromdate') is-invalid @enderror   wd-250" />


                        @error('fromdate')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> الي </label>
                        <input name="todate" id="todate" type="date"
                            class="form-control @error('todate') is-invalid @enderror   wd-250" />


                        @error('todate')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group  col-md-12" style="text-align: left;">
                    <button type="button" onclick="search()" class="btn btn-primary waves-effect waves-light">بحث</button>

                </div>
            </form>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض البطاقات</h4>
                    <div class="table-responsive" data-pattern="priority-columns" id="searchs">

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function search() {
            $('#loader-overlay').show();

            var cardstautes_id = document.getElementById('cardstautes_id').value;
            var request_number = document.getElementById('request_number').value;
            var card_number = document.getElementById('card_number').value;
            var fromdate = document.getElementById('fromdate').value;

            var todate = document.getElementById('todate').value;


            if ((cardstautes_id != '') || (request_number != '') ||
                (card_number != '') || (fromdate != '') || (todate != '')) {
                $.ajax({
                    url: '../../office/card/searchby',
                    type: 'Get',
                    data: {
                        cardstautes_id: $('#cardstautes_id').val(),
                        request_number: $('#request_number').val(),
                        card_number: $('#card_number').val(),
                        fromdate: $('#fromdate').val(),
                        todate: $('#todate').val(),

                    },
                    success: function(response) {
                        // console.log(response);
                        if (response.code == 1) {
                            $('#loader-overlay').hide();

                            $('#searchs').html(
                                '<div class="table-responsive" data-pattern="priority-columns">' +
                                '<table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom "                    style="cursor: pointer;">' +
                                '<thead>' +
                                '<t>' +
                                ' <th>' + "#" + ' </th>' +
                                ' <th>' + " الرقم التسلسلي " + ' </th>' +
                                ' <th>' + "   رقم البطاقة   " + ' </th>' +
                                ' <th>' + "   book_id   " + ' </th>' +
                                ' <th>' + "   الشركة   " + ' </th>' +
                                ' <th>' + "   المكتب   " + ' </th>' +

                                ' <th>' + "  حالة البطاقة " + ' </th>' +
                                ' <th>' + " رقم الطلب  " + ' </th>' +
                                ' <th>' + " تاريخ ادراج البطاقة " + ' </th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody id="rowsss">' +
                                '</tbody>' +
                                '</table>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
x=0
                            for (var i = 0; i < response['data'].length; i++) {

                                if (response['data'][i].companies != null) {
                                    companies = response['data'][i].companies.name;
                                } else {
                                    companies = 'الإتحاد الليبي للتأمين';
                                }
                                if (response['data'][i].offices != null) {
                                    offices = response['data'][i].offices.name;
                                } else {
                                    offices = 'لدي الشركة  ';
                                }
 

                                $('#rowsss').append(
                                    '<tr>' +
                                        '<td>' + (x=x+1) + '</td>'+
                                    '<td>' + response['data'][i].card_serial + '</td>' +
                                    '<td>' + response['data'][i].card_number + '</td>' +

                                    '<td>' + response['data'][i].book_id + '</td>' +
                                    '<td>' + companies + '</td>' +
                                    '<td>' + offices + '</td>' +

                                    '<td>' + response['data'][i].cardstautes.name + '</td>' +

                                    '<td>' + response['data'][i].requests.request_number + '</td>' +
                                    '<td>' + response['data'][i].created_at + '</td>' +

                                    '</tr>'
                                );

                            }





                            $('#datatable1').dataTable({
                                "language": {
                                    "url": "{{ asset('Arabic.json') }}" //arbaic lang

                                },
                                "lengthMenu": [10, 20, 30, 50],
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

                                    }

                                ],

                            });





                        } else {
                            swal.fire("لايوجد بطاقات");
                            $('#searchs').html("");
                            $('#loader-overlay').hide();


                        }

                    }

                });



            } else {


                swal.fire(" الرجاء قم باختيار خيار واحد علي الاقل   ");
                $('#searchs').html("");
                $('#loader-overlay').hide();

            }
        }
    </script>
@endsection
