@extends('office.app')
@section('title', 'تقارير المبيعات [مختصر]')

@section('content')
    <script></script>
    <div id="session-status" data-session-expired="{{ Auth::check() ? 'false' : 'true' }}"></div>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('office/report/issuing') }}">ادارة التقارير</a>/ تقاريرالمبيعات[مختصر]
                </h4>

            </div>

            <div class="box-content">

                <form method="GET" enctype="multipart/form-data" target="_blank"
                    action="{{ route('office/report/issuing/summary/pdf') }}">
                    @csrf <div id="user-type-id" data-user-type-id="{{ Auth::user()->user_type_id }}"></div>
                    <div id="office-users-id" data-office-users-id="{{ Auth::user()->id }}"></div>
                    @csrf
                    <div class="row">

                        @if (Auth::user()->user_type_id == 1)
                            <div class="form-group  col-md-3">
                                <label for="inputName" class="control-label">مستخدم المكتب </label>
                                <select type="text" name="office_users_id"
                                    class="form-control @error('office_users_id') is-invalid @enderror"
                                    value="{{ old('card_number') }}" id="office_users_id">
                                    <option value="">اختر المستخدم</option>
                                    <option value="0">الكل </option>

                                    @foreach ($OfficeUsers as $OfficeUse)
                                        <option value="{{ $OfficeUse->id }} ">{{ $OfficeUse->username }} </option>
                                    @endforeach
                                </select>
                                @error('office_users_id')
                                    <span class="invalid-feedback" style="color: red" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">اسم العميل </label>
                            <input type="text" name="insurance_name"
                                class="form-control @error('insurance_name') is-invalid @enderror"
                                value="{{ old('insurance_name') }}" id="insurance_name" placeholder="اسم العميل ">
                            @error('insurance_name')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">رقم البطاقة </label>
                            <input type="text" name="card_number"
                                class="form-control @error('card_number') is-invalid @enderror"
                                value="{{ old('card_number') }}" id="card_number" placeholder="رقم البطاقة">
                            @error('card_number')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName" class="control-label"> رقم اللوحة </label>
                            <input type="text" name="plate_number"
                                class="form-control @error('plate_number') is-invalid @enderror"
                                value="{{ old('plate_number') }}" id="plate_number"
                                placeholder="   اللوحة المعدنية       ">
                            @error('plate_number')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName" class="control-label"> رقم الهيكل </label>
                            <input type="text" name="chassis_number"
                                class="form-control @error('chassis_number') is-invalid @enderror"
                                value="{{ old('chassis_number') }}" id="chassis_number" placeholder="  رقم الهيكل     ">
                            @error('chassis_number')
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
                        <button type="button" onclick="search()"
                            class="btn btn-primary waves-effect waves-light">بحث</button>
                        <button type="submit" class="btn btn-primary waves-effect"> تصدير ك pdf </button>

                    </div>
                </form>
            </div>
            <div class="row small-spacing">
                <div class="col-md-12">
                    <div class="box-content ">
                        <h4 class="box-title">عرض الكل</h4>
                        <div class="table-responsive" data-pattern="priority-columns" id="searchs">

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function redirectToLogin() {
                // Create a form dynamically to send a POST request to the login route
                const loginForm = document.createElement('form');
                loginForm.method = 'POST';
                loginForm.action = "{{ route('login') }}";

                // Add CSRF token to the form
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                loginForm.appendChild(csrfInput);

                // Append the form to the body and submit it
                document.body.appendChild(loginForm);
                loginForm.submit();
            }

            function checkSession() {
                const sessionExpired = document.getElementById('session-status').getAttribute('data-session-expired');
                if (sessionExpired === 'true') {
                    redirectToLogin(); // Redirect to login if session is expired
                }
            }

            $(document).on('change', 'select, input', function() {
                checkSession();
            });

            function search() {
                checkSession(); // Ensure session is valid before proceeding
                $('#loader-overlay').show();

                var office_users_id;
                var userType = $('#user-type-id').data('user-type-id');
                if (userType == 1) {
                    office_users_id = $('#office_users_id').val();
                } else {
                    var office_users_id = $('#office-users-id').data('office-users-id');

                }

                var insurance_name = document.getElementById('insurance_name').value;
                var plate_number = document.getElementById('plate_number').value;
                var chassis_number = document.getElementById('chassis_number').value;

                var card_number = document.getElementById('card_number').value;
                var fromdate = document.getElementById('fromdate').value;

                var todate = document.getElementById('todate').value;


                if (!fromdate || !todate) {
                    $('#loader-overlay').hide();

                    swal.fire("الرجاء اختيار تاريخ البدء وتاريخ النهاية");
                    return; // Exit the function if dates are missing
                } 
                                                const from = new Date(fromdate);
    const to = new Date(todate);
    const monthDiff = (to.getFullYear() - from.getFullYear()) * 12 + (to.getMonth() - from.getMonth());
    if (monthDiff > 3 || (monthDiff === 3 && to.getDate() > from.getDate())) {
        $('#loader-overlay').hide();
        Swal.fire("يجب أن تكون الفترة 3 أشهر أو أقل فقط.");
        return;
    }
                if ((office_users_id != '') || (card_number != '') ||
                    (insurance_name != '') || (plate_number != '') ||
                    (chassis_number != '') || (fromdate != '') || (todate != '')) {
                    $.ajax({
                        url: '../../../office/report/issuing/searchby',
                        type: 'Get',
                        data: {
                            office_users_id: $('#office_users_id').val(),
                            insurance_name: $('#insurance_name').val(),
                            card_number: $('#card_number').val(),
                            chassis_number: $('#plate_number').val(),
                            plate_number: $('#plate_number').val(),

                            fromdate: $('#fromdate').val(),
                            todate: $('#todate').val(),

                        },
                        success: function(response) {


                            // console.log(response);
                            if (response.code == 1) {
                                $('#loader-overlay').hide();
           let totalInstallment = 0,
                        totalTax         = 0,
                        totalStamp       = 0,
                        totalSupervision = 0,
                        totalVersion     = 0,
                        totalInsurance   = 0;
                                // Calculate total insurance
                                response.data.forEach(item => {
                                    totalInsurance += parseFloat(item.insurance_total) || 0;
                                });
                                $('#searchs').html(
                                    '<div class="table-responsive" data-pattern="priority-columns">' +
                                    '<table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom "                    style="cursor: pointer;">' +
                                    '<thead>' +

                                    '<tr>' +
                                    ' <th>' + "#" + ' </th>' +
                                    ' <th>' + "رقم البطاقة" + ' </th>' +

                                    ' <th>' + "المُصدر" + ' </th>' +
                                    ' <th>' + "   المؤمن له    " + ' </th>' +
                                    ' <th>' + "   تاريخ الاصدار " + ' </th>' +
                                    ' <th>' + " صافي القسط   " + ' </th>' +
                                    ' <th>' + " الضريبة    " + ' </th>' +
                                    ' <th>' + " رسم الدمغة    " + ' </th>' +
                                    ' <th>' + "  الإشراف     " + ' </th>' +
                                    ' <th>' + "  الإصدار    " + ' </th>' +
                                    ' <th>' + " الاجمالي    " + ' </th>' +

                                    ' <th>' + "     عرض الوثيقة     " + ' </th>' +

                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody id="rowsss">' +
                                    '</tbody>' +
                                                                            '<tfoot id="table-footer">' +

                                    '</table>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>');
                                $('#footer').append('<p>' + totalInsurance.toFixed(3) + ' د.ل</p>');


                                x = 0;
                                for (var i = 0; i < response['data'].length; i++) {
 totalInstallment += parseFloat(response.data[i].insurance_installment) || 0;
                        totalTax         += parseFloat(response.data[i].insurance_tax) || 0;
                        totalStamp       += parseFloat(response.data[i].insurance_stamp) || 0;
                        totalSupervision += parseFloat(response.data[i].insurance_supervision) || 0;
                        totalVersion     += parseFloat(response.data[i].insurance_version) || 0;



                                    if (response['data'][i].companies != null) {
                                        companies = response['data'][i].companies.name;


                                    } else {
                                        companies = 'الإتحاد الليبي للتأمين';
                                    }
                                    if (response['data'][i].offices != null) {
                                        offices = response['data'][i].offices.name;
                                        companies = response['data'][i].offices.companies.name;

                                    } else {
                                        offices = ' الفرع الرئيسي  ';
                                    }
                                    if (response['data'][i].office_users_id != null) {
                                        user = response['data'][i].office_users.username;



                                    }
                                    if (response['data'][i].company_users_id != null) {
                                        user = response['data'][i].company_users.username;
                                    }
                                    var cardNumber = response.data[i].cards_id;

                                    var baseUrl =
                                        "{{ route('office/viewdocument', ['cardnumber' => 'PLACEHOLDER']) }}";


                                    var url = baseUrl.replace('PLACEHOLDER', encodeURIComponent(cardNumber));
                                    var v =
                                        '<a style="color: #f97424;" target="_blank" href="' + url + '">' +
                                        '<img src="{{ asset('contract.png') }}" style="width: 50%;">' +
                                        '</a>';


                                    $('#rowsss').append(
                                        '<tr>' +
                                        '<td>' + (x = x + 1) + '</td>' +
                                        '<td>' + response['data'][i].cards.card_number + '</td>' +

                                        '<td>' + user + '</td>' +
                                        '<td>' + response['data'][i].insurance_name + '</td>' +

                                        '<td>' + response['data'][i].issuing_date + '</td>' +
                                        '<td>' + response['data'][i].insurance_installment + '</td>' +
                                        '<td>' + response['data'][i].insurance_tax + '</td>' +
                                        '<td>' + response['data'][i].insurance_stamp + '</td>' +
                                        '<td>' + response['data'][i].insurance_supervision + '</td>' +
                                        '<td>' + response['data'][i].insurance_version + '</td>' +
                                        '<td>' + response['data'][i].insurance_total + '</td>' +


                                        '<td>' +
                                        v + '</td>' +

                                        '</tr>'
                                    );

    var footerHtml = '<tr>' +
                        '<th colspan="5" style="text-align:center;">الإجمالي</th>' +
                        '<th>' + totalInstallment.toFixed(3) + '</th>' +
                        '<th>' + totalTax.toFixed(3) + '</th>' +
                        '<th>' + totalStamp.toFixed(3) + '</th>' +
                        '<th>' + totalSupervision.toFixed(3) + '</th>' +
                        '<th>' + totalVersion.toFixed(3) + '</th>' +
                        '<th>' + totalInsurance.toFixed(3) + '</th>' +
                        '<th colspan="8"></th>' +
                    '</tr>';
                    $('#table-footer').html(footerHtml);
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
                                                text: 'excel تصدير كـ ',
                                                title: function() {
                                                    // Get the "from" and "to" date values from your form or data
                                                   // Replace with your actual selector

                                                    // Construct the title using the date values
                                                    return 'تقرير المبيعات من ' + fromdate +
                                                        ' إلى ' + todate;
                                                }
                                            },

                                    ],

                                });





                            } else {
                                swal.fire("لايوجد مبيعات");
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
