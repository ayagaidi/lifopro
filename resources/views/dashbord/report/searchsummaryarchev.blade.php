@extends('layouts.app')
@section('title', '[ارشيف]تقارير المبيعات [مختصر]') 
@section('content')




    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('report/issuing/summary/archives') }}">ادارة التقارير</a>/ تقريرالمبيعات[ارشيف] [مختصر ]</h4>

            </div>

            <div class="box-content">

                <form method="GET" enctype="multipart/form-data" target="_blank"
                    action="{{ route('report/issuing/search/summary/pdf') }}">
                    @csrf
                    <div class="row">
                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">الشركة</label>
                            <select name="companies_id" id="companies_id"
                                class="form-control @error('companies_id') is-invalid @enderror  select2  wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer">
                                <option value="">اختر </option>

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
                            <label for="inputName" class="control-label">مستخدم الشركة </label>
                            <select type="text" name="company_users_id" id="company_users_id"
                                class="form-control @error('company_users_id') is-invalid @enderror"
                                value="{{ old('company_users_id') }}" id="company_users_id">

                            </select>
                            @error('company_users_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">المكتب</label>
                            <select name="offices_id" id="offices_id"
                                class="form-control @error('offices_id') is-invalid @enderror  select2  wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer">


                            </select>
                            @error('offices_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">مستخدم المكتب </label>
                            <select type="text" name="office_users_id"
                                class="form-control @error('office_users_id') is-invalid @enderror"
                                value="{{ old('office_users_id') }}" id="office_users_id">

                            </select>
                            @error('office_users_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
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
                            <input name="fromdate" id="fromdate"    max="{{ \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d') }}" type="date"
                                class="form-control @error('fromdate') is-invalid @enderror   wd-250" required />


                            @error('fromdate')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName" class="control-label"> الي </label>
                            <input name="todate" id="todate" type="date"  max="{{ \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d') }}" 
                                class="form-control @error('todate') is-invalid @enderror   wd-250" required />


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

        <div id="session-status" data-session-expired="{{ Auth::check() ? 'false' : 'true' }}"></div>

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

            // Attach session check to dropdown change events
            $(document).on('change', 'select, input', function() {
                checkSession();
            });

            $('select[name="companies_id"]').on('change', function() {
                checkSession();
                var companies_id = $(this).val();
                $('select[name="company_users_id"]').empty();
                $('select[name="offices_id"]').empty();

                if (companies_id) {

                    $.ajax({
                        url: '../../report/companyuser/' + companies_id,

                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            $('select[name="company_users_id"]').append('<option value="">' +
                                'اختر مستخدم' + '</option>');
                            $.each(data, function(key, value) {
                                $('select[name="company_users_id"]').append('<option value="' +
                                    value.id + '">' + value.username + '</option>');
                            });

                        }
                    });


                    $.ajax({
                        url: '../../report/offices/' + companies_id,

                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            $('select[name="offices_id"]').append('<option value="">' +
                                'اختر مكتب' + '</option>');
                            $.each(data, function(key, value) {
                                $('select[name="offices_id"]').append('<option value="' +
                                    value.id + '">' + value.name + '</option>');
                            });

                        }
                    });

                } else {
                    $('select[name="office_users_id"]').empty();
                    $('select[name="offices_id"]').empty();

                }
            });

            $('select[name="offices_id"]').on('change', function() {
                checkSession();
                var offices_id = $(this).val();
                $('select[name="office_users_id"]').empty();
                const companyUsersDropdown = document.getElementById('company_users_id');
                companyUsersDropdown.value = '';
                if (offices_id) {

                    {
                        $.ajax({
                            url: '../../company/report/officesuser/' +
                                offices_id, // Correct URL with country ID
                            type: "GET",
                            dataType: "json",
                            success: function(data) {

                                $('select[name="office_users_id"]').append('<option value="">' +
                                    'اختر مستخدم' + '</option>');
                                $.each(data, function(key, value) {
                                    $('select[name="office_users_id"]').append('<option value="' +
                                        value.id + '">' + value.username + '</option>');
                                });

                            }
                        });

                    }
                } else {
                    $('select[name="office_users_id"]').empty();
                }
            });





            // 
          function search() {
    checkSession(); // Ensure session is valid before proceeding
    $('#loader-overlay').show();

    var companies_id = $('#companies_id').val();
    var offices_id = $('#offices_id').val();
    var office_users_id = $('#office_users_id').val();
    var company_users_id = $('#company_users_id').val();

    var insurance_name = document.getElementById('insurance_name').value;
    var plate_number = document.getElementById('plate_number').value;
    var chassis_number = document.getElementById('chassis_number').value;
    var card_number = document.getElementById('card_number').value;
    var fromdate = document.getElementById('fromdate').value;
    var todate = document.getElementById('todate').value;

    // ✅ تحقق من وجود التواريخ
    if (!fromdate || !todate) {
        $('#loader-overlay').hide();
        swal.fire("الرجاء اختيار تاريخ البدء وتاريخ النهاية");
        return;
    } else {
        // ✅ تحقق من أن التواريخ لا تشمل السنة الحالية أو القادمة
        let fromYear = new Date(fromdate).getFullYear();
        let toYear = new Date(todate).getFullYear();
        let currentYear = new Date().getFullYear();

        if (fromYear >= currentYear || toYear >= currentYear) {
            $('#loader-overlay').hide();
            swal.fire("يرجى اختيار تواريخ من سنوات سابقة فقط، ولا تشمل السنة الحالية أو القادمة.");
            return;
        }

        // ✅ تحقق من وجود أي فلاتر
        if (
            (companies_id || offices_id || office_users_id || card_number ||
                insurance_name || plate_number || chassis_number || company_users_id) || (fromdate && todate)
        ) {
            $.ajax({
                url: '../../../report/issuing/searchby/archives',
                type: 'GET',
                data: {
                    offices_id,
                    companies_id,
                    office_users_id,
                    insurance_name,
                    card_number,
                    chassis_number,
                    plate_number,
                    company_users_id,
                    fromdate,
                    todate,
                },
                success: function(response) {
                    if (response.code == 1) {
                        let totalInstallment = 0,
                            totalTax = 0,
                            totalStamp = 0,
                            totalSupervision = 0,
                            totalVersion = 0,
                            totalInsurance = 0;

                        $('#loader-overlay').hide();

                        response.data.forEach(item => {
                            totalInsurance += parseFloat(item.insurance_total) || 0;
                        });

                        $('#searchs').html(
                            '<table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>#</th>' +
                            '<th>رقم البطاقة</th>' +
                            '<th>المُصدر</th>' +
                            '<th>الشركة</th>' +
                            '<th>المكتب</th>' +
                            '<th>المؤمن له</th>' +
                            '<th>تاريخ الاصدار</th>' +
                            '<th>صافي القسط</th>' +
                            '<th>الضريبة</th>' +
                            '<th>رسم الدمغة</th>' +
                            '<th>الإشراف</th>' +
                            '<th>الإصدار</th>' +
                            '<th>الإجمالي</th>' +
                            '<th class="view">عرض الوثيقة</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody id="rowsss"></tbody>' +
                            '<tfoot id="table-footer"></tfoot>' +
                            '</table>'
                        );

                        let x = 0;
                        $('#footer').append('<p>' + totalInsurance.toFixed(3) + ' د.ل</p>');

                        response.data.forEach((item, i) => {
                            totalInstallment += parseFloat(item.insurance_installment) || 0;
                            totalTax += parseFloat(item.insurance_tax) || 0;
                            totalStamp += parseFloat(item.insurance_stamp) || 0;
                            totalSupervision += parseFloat(item.insurance_supervision) || 0;
                            totalVersion += parseFloat(item.insurance_version) || 0;

                            let companies = item.companies ? item.companies.name : 'الإتحاد الليبي للتأمين';
                            let offices = item.offices ? item.offices.name : 'الفرع الرئيسي';
                            if (item.offices && item.offices.companies) {
                                companies = item.offices.companies.name;
                            }

                            let user = item.office_users ? item.office_users.username :
                                       item.company_users ? item.company_users.username : '';

                            let card = item.cards ? item.cards.card_number : item.id;

                            let baseUrl = "{{ route('viewdocument', ['cardnumber' => 'PLACEHOLDER']) }}";
                            let url = baseUrl.replace('PLACEHOLDER', encodeURIComponent(item.cards_id));
                            let viewLink = '<a style="color: #f97424;" target="_blank" href="' + url + '">' +
                                '<img src="{{ asset('contract.png') }}" style="width: 50%;">' +
                                '</a>';

                            $('#rowsss').append(
                                '<tr>' +
                                '<td>' + (++x) + '</td>' +
                                '<td>' + card + '</td>' +
                                '<td>' + user + '</td>' +
                                '<td>' + companies + '</td>' +
                                '<td>' + offices + '</td>' +
                                '<td>' + item.insurance_name + '</td>' +
                                '<td>' + item.issuing_date + '</td>' +
                                '<td>' + item.insurance_installment + '</td>' +
                                '<td>' + item.insurance_tax + '</td>' +
                                '<td>' + item.insurance_stamp + '</td>' +
                                '<td>' + item.insurance_supervision + '</td>' +
                                '<td>' + item.insurance_version + '</td>' +
                                '<td>' + item.insurance_total + '</td>' +
                                '<td class="view">' + viewLink + '</td>' +
                                '</tr>'
                            );
                        });

                        let footerHtml = '<tr>' +
                            '<th colspan="7" style="text-align:center;">الإجمالي</th>' +
                            '<th>' + totalInstallment.toFixed(3) + '</th>' +
                            '<th>' + totalTax.toFixed(3) + '</th>' +
                            '<th>' + totalStamp.toFixed(3) + '</th>' +
                            '<th>' + totalSupervision.toFixed(3) + '</th>' +
                            '<th>' + totalVersion.toFixed(3) + '</th>' +
                            '<th>' + totalInsurance.toFixed(3) + '</th>' +
                            '</tr>';
                        $('#table-footer').html(footerHtml);

                        $('#datatable1').dataTable({
                            language: {
                                url: "{{ asset('Arabic.json') }}"
                            },
                            lengthMenu: [10, 20, 30, 50],
                            bLengthChange: true,
                            serverSide: false,
                            paging: true,
                            searching: true,
                            ordering: true,
                            dom: 'Blfrtip',
                            buttons: [
                                {
                                    extend: 'copyHtml5',
                                    exportOptions: { columns: [':visible'] },
                                    text: 'نسخ'
                                },
                                {
                                    extend: 'excelHtml5',
                                    exportOptions: { columns: ':visible' },
                                    text: 'excel تصدير كـ ',
                                    title: function () {
                                        return 'تقرير المبيعات من ' + fromdate + ' إلى ' + todate;
                                    }
                                }
                            ]
                        });

                    } else {
                        swal.fire("لايوجد مبيعات");
                        $('#searchs').html("");
                        $('#loader-overlay').hide();
                    }
                }
            });
        } else {
            swal.fire(" الرجاء قم باختيار خيار واحد على الأقل ");
            $('#searchs').html("");
            $('#loader-overlay').hide();
        }
    }
}

        </script>



    @endsection
