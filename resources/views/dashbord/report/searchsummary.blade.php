@extends('layouts.app')
@section('title', 'تقارير المبيعات [مختصر]')

@section('content')

 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/@linways/table-to-excel@1.0.4/dist/tableToExcel.min.js" defer></script>
  <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

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
                    [مختصر] @if(isset($year)) {{ $year }} @endif</h4>

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
                        <div class="form-group col-md-3">
    <label class="control-label">السنة</label>
    <select name="year" id="year" class="form-control">
        <option value="">اختر السنة</option>

        @for($year = 2020; $year <= 2051; $year++)
            <option value="{{ $year }}"
                {{ now()->year == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endfor
    </select>
</div>


            <div class="form-group col-md-3">
              <label class="control-label"> من </label>
              <input name="fromdate" id="fromdate" type="date"
                class="form-control @error('fromdate') is-invalid @enderror wd-250" required />
              @error('fromdate') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label"> إلى </label>
              <input name="todate" id="todate" type="date"
                class="form-control @error('todate') is-invalid @enderror wd-250" required />
              @error('todate') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>
                    </div>
                    <div class="form-group  col-md-12" style="text-align: left;">
                        <button type="button" onclick="search()"
                            class="btn btn-primary waves-effect waves-light">بحث</button>
                        @if(isset($year))
                        <button type="button" id="btnExportPdfYear" class="btn btn-primary waves-effect"> تصدير ك pdf </button>
                        @else
                        <button type="submit" class="btn btn-primary waves-effect"> تصدير ك pdf </button>
                        @endif

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
                $('select[name="company_users_id"]').empty();
                $('select[name="offices_id"]').empty();
                $('select[name="office_users_id"]').empty();
                var companies_id = $(this).val();

                if (companies_id) {

                    $.ajax({
                        url: '../../companyuser/' + companies_id,

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
                        url: '../../offices/' + companies_id,

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
                            url: '../../officesuser/' +
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
function search(page = 1) {
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

    // التحقق أن التواريخ ضمن السنة المختارة فقط (سنة واحدة)
    var selectedYear = $('#year').val();
    if (selectedYear) {
        var startOfYear = new Date(parseInt(selectedYear), 0, 1);
        var endOfYear = new Date(parseInt(selectedYear), 11, 31);
        if (fromDateObj < startOfYear || toDateObj > endOfYear ||
            fromDateObj.getFullYear() != parseInt(selectedYear) || toDateObj.getFullYear() != parseInt(selectedYear)) {
            $('#loader-overlay').hide();
            swal.fire(`يجب أن تكون التواريخ ضمن السنة ${selectedYear} فقط`);
            return;
        }
    }

    // التحقق أن الفرق بين التاريخين ≤ 3 أشهر (92 يوم كحد أقصى)
    var diffTime = toDateObj - fromDateObj;
    var diffDays = diffTime / (1000 * 60 * 60 * 24);

    if (diffDays > 92 || diffDays < 0) {
        $('#loader-overlay').hide();
        swal.fire("مدة البحث يجب ألا تتجاوز 3 أشهر فقط وتاريخ النهاية بعد البداية");
        return;
    }

    // At least one filter or dates must be provided
    const hasFilter = companies_id || offices_id || office_users_id || company_users_id ||
                      insurance_name || card_number || plate_number || chassis_number;

    if (!hasFilter && (!fromdate || !todate)) {
        $('#loader-overlay').hide();
        swal.fire("الرجاء قم باختيار خيار واحد على الأقل");
        $('#searchs').html("");
        return;
    }

    $.ajax({
        url: '../../report/issuing/search/summary',
        type: 'GET',
        data: {
            offices_id, companies_id, office_users_id, company_users_id,
            insurance_name, card_number, plate_number, chassis_number,
            fromdate, todate, year: $('#year').val(), page: page
        },
        success: function (response) {
            $('#loader-overlay').hide();
            $('#searchs').html(response);

            // Handle pagination clicks via AJAX
            $('#searchs .pagination a').on('click', function(e) {
                e.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                search(page);
            });
        },
        error: function (xhr) {
            $('#loader-overlay').hide();
            if (xhr.status === 404) {
                swal.fire("لايوجد بطاقات");
            } else {
                swal.fire("حدث خطأ أثناء جلب البيانات");
            }
        }
    });
}

        </script>

<script>
function fillDatesByYear() {
    const year = $('#year').val();
    if (!year) return;
    $('#fromdate').val(year + '-01-01');
    $('#todate').val(year + '-03-31');
}
$(document).ready(function () {
    fillDatesByYear();
    $('#year').on('change', function () { fillDatesByYear(); });
});
$('#year').on('change', function () {
    const year = $(this).val();
    if (!year) { $('#fromdate').val(''); $('#todate').val(''); return; }
    $('#fromdate').val(`${year}-01-01`);
    $('#todate').val(`${year}-03-31`);
});
$('#fromdate, #todate').on('change', function () {
    const selectedYear = $('#year').val();
    const fromDate = $('#fromdate').val();
    const toDate   = $('#todate').val();
    if (selectedYear) {
        const fromYear = fromDate ? fromDate.split('-')[0] : '';
        const toYear   = toDate ? toDate.split('-')[0] : '';
        if ((fromYear && fromYear != selectedYear) || (toYear && toYear != selectedYear)) {
            Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يجب أن تكون التواريخ ضمن نفس السنة المختارة', confirmButtonText: 'حسناً' });
            $('#fromdate').val(''); $('#todate').val(''); return;
        }
    }
    if (fromDate && toDate) {
        const start = new Date(fromDate); const end = new Date(toDate);
        const diffDays = (end - start) / (1000 * 60 * 60 * 24);
        if (diffDays > 92) {
            Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'الحد الأقصى للفترة هو 3 أشهر فقط', confirmButtonText: 'حسناً' });
            $('#todate').val('');
        }
        if (diffDays < 0) {
            Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية', confirmButtonText: 'حسناً' });
            $('#todate').val('');
        }
    }
});
</script>

    @if(isset($year))
    <script>
        $(document).on('click', '#btnExportPdfYear', function () {
            if (!$('#fromdate').val() || !$('#todate').val()) {
                Swal.fire("تنبيه", "الرجاء اختيار تاريخ البدء وتاريخ النهاية", "warning");
                return;
            }

            const q = $.param({
                offices_id:       $('#offices_id').val() || '',
                companies_id:     $('#companies_id').val() || '',
                office_users_id:  $('#office_users_id').val() || '',
                insurance_name:   $('#insurance_name').val() || '',
                card_number:      $('#card_number').val() || '',
                chassis_number:   $('#chassis_number').val() || '',
                plate_number:     $('#plate_number').val() || '',
                company_users_id: $('#company_users_id').val() || '',
                fromdate:         $('#fromdate').val(),
                todate:           $('#todate').val()
            });

            window.location.href = "{{ route('report.issuing.export-pdf-year', $year) }}?" + q;
        });
    </script>
    @endif
@endsection
