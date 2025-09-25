@extends('layouts.app')
@section('title', ' تقرير طلبات شركات التآمين ')

@section('content')

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">

            <h4 class="box-title"><a href="{{ route('report/issuing') }}">ادارة التقارير</a>/ تقرير طلبات شركات التآمين
            </h4>

        </div>

        <div class="box-content">

            <form method="GET" enctype="multipart/form-data" target="_blank" action="{{ route('report/issuing/search') }}">
                @csrf
                <div class="row">
                    <div class="form-group  col-md-3">
                        <label for="inputName" class="control-label">الشركة</label>
                        <select name="companies_id" id="companies_id"
                            class="form-control @error('companies_id') is-invalid @enderror  select2  wd-250"
                            data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                            data-parsley-errors-container="#slErrorContainer" required>
                            <option value="0">اختر </option>

                            @forelse ($Company as $com)
                                <option value="{{ $com->id }}"> {{ $com->name }}</option>
                            @empty
                                <option value="">لايوجد شركات</option>
                            @endforelse

                        </select>
                        @error('companies_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group  col-md-3">
                        <label for="inputName" class="control-label">حالة الطلب</label>
                        <select name="request_statuses_id" id="request_statuses_id"
                            class="form-control @error('request_statuses_id') is-invalid @enderror  select2  wd-250"
                            data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                            data-parsley-errors-container="#slErrorContainer">

                            <option value="0">اختر </option>

                            @forelse ($request_statuses as $com)
                                <option value="{{ $com->id }}"> {{ $com->name }}</option>
                            @empty
                                <option value="">لايوجد حالات</option>
                            @endforelse

                        </select>
                        @error('request_statuses_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group  col-md-3">
                        <label for="inputName" class="control-label">عدد البطاقات </label>
                        <input type="text" name="cards_number"
                            class="form-control @error('cards_number') is-invalid @enderror"
                            value="{{ old('cards_number') }}" id="cards_number" placeholder="عدد البطاقات">
                        @error('cards_number')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group  col-md-3">
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

                    <div class="form-group col-md-3">
                        <label for="inputName" class="control-label"> من </label>
                        <input name="fromdate" id="fromdate" type="date"
                            class="form-control @error('fromdate') is-invalid @enderror   wd-250" />
                        @error('fromdate')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
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
                    <button type="button" onclick="search()" id="search-btn"
                        class="btn btn-primary waves-effect waves-light">بحث</button>
                </div>
            </form>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض الكل</h4>

                    <a href="{{ route('report/requestcompany/pdf') }}" target="_blank" class="btn btn-primary" id="printPdfBtn" style="display:none;">طباعة PDF</a>

                    <div class="table-responsive" data-pattern="priority-columns" id="searchs">

                    </div>
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

    function isAtLeastOneSelected(...values) {
        return values.some(value => value !== '0' && value);
    }

    function search() {
        $('#loader-overlay').show();

        const companiesId = $('#companies_id').val();
        const requestStatusesId = $('#request_statuses_id').val();
        const cardsNumber = $('#cards_number').val();
        const requestNumber = $('#request_number').val();
        const fromdate = $('#fromdate').val();
        const todate = $('#todate').val();

        if (isAtLeastOneSelected(companiesId, requestStatusesId, cardsNumber, requestNumber, fromdate, todate)) {
            $.ajax({
                url: '../../report/searchrequest',
                type: 'GET',
                data: {
                    request_number: requestNumber,
                    companies_id: companiesId,
                    request_statuses_id: requestStatusesId,
                    fromdate: fromdate,
                    todate: todate,
                    cards_number: cardsNumber,
                },
                success: function(response) {
                    $('#loader-overlay').hide();

                    if (response.code == 1) {
                        $('#printPdfBtn').show();

                        // Build the PDF URL dynamically with current search params
                        let pdfUrl = `{{ route('report/requestcompany/pdf') }}?` +
                            `request_number=${encodeURIComponent(requestNumber)}` +
                            `&companies_id=${encodeURIComponent(companiesId)}` +
                            `&request_statuses_id=${encodeURIComponent(requestStatusesId)}` +
                            `&fromdate=${encodeURIComponent(fromdate)}` +
                            `&todate=${encodeURIComponent(todate)}`;

                        $('#printPdfBtn').attr('href', pdfUrl);

                        $('#searchs').html(
                            '<table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom" style="cursor: pointer;">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>#</th>' +
                            '<th>رقم الطلب</th>' +
                            '<th>الشركة</th>' +
                            '<th>المستخدم</th>' +
                            '<th>عدد البطاقات</th>' +
                            '<th>حالة الطلب</th>' +
                            '<th>تاريخ الطلب</th>' +
                            '<th>قبول الطلب</th>' +
                            '<th>تاريخ تنزيل البطاقات</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody id="rowsss">' +
                            '</tbody>' +
                            '</table>'
                        );

                        let x = 0;

                        for (let i = 0; i < response['data'].length; i++) {
                            let item = response['data'][i];
                            let vcancelplicy = '';

                            if (item.request_statuses_id == 2) {
                                if (item.uploded == 0) {
                                    let baseUrlcancelplicy = "{{ route('cardrequests/uplodecards', ['id' => 'PLACEHOLDERcancelplicy']) }}";
                                    let reqq = baseUrlcancelplicy.replace('PLACEHOLDERcancelplicy', encodeURIComponent(item.id));
                                    vcancelplicy = '<a style="color: #f97424;" href="' + reqq + '">' +
                                        '<img src="{{ asset('uplode.png') }}" style="width: 50%;">' +
                                        '</a>';
                                } else {
                                    vcancelplicy = 'تم التنزيل';
                                }
                            }

                            $('#rowsss').append(
                                '<tr>' +
                                '<td>' + (++x) + '</td>' +
                                '<td>' + item.request_number + '</td>' +
    '<td>' + (item.companies ? item.companies.name : '') + '</td>' +      
     '<td>' + (item.company_users ? item.company_users.username : '') + '</td>' +  
                                '<td>' + item.cards_number + '</td>' +
                                '<td>' + item.request_statuses.name + '</td>' +
                                '<td>' + item.created_at + '</td>' +
                                '<td>' + vcancelplicy + '</td>' +
                                '<td>' + item.uploded_datetime + '</td>' +
                                '</tr>'
                            );
                        }

                        $('#datatable1').dataTable({
                            "language": {
                                "url": "{{ asset('Arabic.json') }}" // Arabic language
                            },
                            "lengthMenu": [10, 20, 30, 50],
                            "bLengthChange": true,
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
                        swal.fire("لايوجد طلبات");
                        $('#searchs').html("");
                    }
                },
                error: function() {
                    $('#loader-overlay').hide();
                    swal.fire("حدث خطأ أثناء البحث.");
                    $('#searchs').html("");
                }
            });
        } else {
            $('#loader-overlay').hide();
            swal.fire(" الرجاء قم باختيار خيار واحد علي الاقل   ");
            $('#searchs').html("");
        }
    }
</script>

@endsection
