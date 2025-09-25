@extends('layouts.app')
@section('title', 'تقارير المبيعات [مختصر]')

@section('content')

 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    
     $(document).ready(function() {
              $('#companies_id').select2({
        placeholder: "اختر الشركة ...",
        allowClear: true,
        language: "ar"
      });
      
             $('#company_users_id').select2({
        placeholder: "اختر مستخدم الشركة ...",
        allowClear: true,
        language: "ar"
      });
          
          
           
             $('#offices_id').select2({
        placeholder: "اختر المكتب ...",
        allowClear: true,
        language: "ar"
      }); 
          
             $('#office_users_id').select2({
        placeholder: "اختر مستخدم المكتب ...",
        allowClear: true,
        language: "ar"
      }); 
      
   }); 
</script>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('report/issuing/summary') }}">ادارة التقارير</a>/ تقاريرالمبيعات
                    [مختصر]</h4>

            </div>

            <div class="box-content">

                <form method="GET" enctype="multipart/form-data" target="_blank"
                    action="{{ route('report/issuing/search/summary') }}">
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
                        @php
    $currentYearStart = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'); // بداية السنة الحالية
    $nextYearEnd = \Carbon\Carbon::now()->addYear()->endOfYear()->format('Y-m-d'); // نهاية السنة القادمة
@endphp
                        <div class="form-group col-md-3">
                            <label for="inputName" class="control-label"> من </label>
                           <input name="fromdate" id="fromdate" type="date"
       min="{{ $currentYearStart }}"
       max="{{ $nextYearEnd }}"
       class="form-control @error('fromdate') is-invalid @enderror wd-250" required />



                            @error('fromdate')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName" class="control-label"> الي </label>
                           <input name="todate" id="todate" type="date"
       min="{{ $currentYearStart }}"
       max="{{ $nextYearEnd }}"
       class="form-control @error('todate') is-invalid @enderror wd-250" required />



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
                            url: '../../report/officesuser/' +
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

    var office_users_id = $('#office_users_id').val();
    var companies_id = $('#companies_id').val();
    var offices_id = $('#offices_id').val();
    var company_users_id = $('#company_users_id').val();
    var insurance_name = $('#insurance_name').val();
    var plate_number = $('#plate_number').val();
    var chassis_number = $('#chassis_number').val();
    var card_number = $('#card_number').val();
    var fromdate = $('#fromdate').val();
    var todate = $('#todate').val();

    if (!fromdate || !todate) {
        $('#loader-overlay').hide();
        swal.fire("الرجاء اختيار تاريخ البدء وتاريخ النهاية");
        return; // Exit the function if dates are missing
    }

    // تحويل التواريخ إلى كائنات Date
    var fromDateObj = new Date(fromdate);
    var toDateObj = new Date(todate);

    // بداية السنة الحالية
    var currentYear = new Date().getFullYear();
    var startOfYear = new Date(currentYear, 0, 1);  // 1 يناير السنة الحالية
    var endOfNextYear = new Date(currentYear + 1, 11, 31); // 31 ديسمبر السنة القادمة

    // التحقق أن التواريخ بين بداية السنة الحالية ونهاية السنة القادمة
    if (fromDateObj < startOfYear || toDateObj > endOfNextYear) {
        $('#loader-overlay').hide();
        swal.fire(`يجب أن تكون التواريخ بين ${startOfYear.toLocaleDateString()} و ${endOfNextYear.toLocaleDateString()}`);
        return;
    }

    // التحقق أن الفرق بين التاريخين لا يتجاوز 3 أشهر (90 يوم تقريبًا)
    var diffTime = toDateObj - fromDateObj;
    var maxDiffDays = 90; // 3 أشهر تقريبًا
    var diffDays = diffTime / (1000 * 60 * 60 * 24); // تحويل الفرق لأيام

    if (diffDays > maxDiffDays) {
        $('#loader-overlay').hide();
        swal.fire("مدة البحث يجب ألا تتجاوز 3 أشهر فقط");
        return;
    }

    if (((companies_id || offices_id || office_users_id || card_number || insurance_name || plate_number ||
        chassis_number || company_users_id) == 1) || (fromdate && todate)) {

        // ... باقي كود ajax والنجاح والفشل كما في الكود السابق

    } else {
        swal.fire("الرجاء قم باختيار خيار واحد على الأقل");
        $('#searchs').html("");
        $('#loader-overlay').hide();
    }
}

        </script>



    @endsection
