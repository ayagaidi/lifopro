@extends('comapny.app')
@section('title', 'تقارير المبيعات ')

@section('content')
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

 <script>
   
    $(document).ready(function() {
              $('#companies_id').select2({
        allowClear: true,
        language: "ar"
      });
              $('#company_users_id').select2({
        placeholder: "اختر مستخدم شركة ...",
        allowClear: true,
        language: "ar"
      });
      
      
            $('#offices_id').select2({
        placeholder: "اختر مكتب ...",
        allowClear: true,
        language: "ar"
      });
      
      
        
            $('#office_users_id').select2({
        placeholder: "اختر مستخدم ...",
        allowClear: true,
        language: "ar"
      });
    });
            
            </script>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('company/report/issuing') }}">ادارة التقارير</a>/ تقاريرالمبيعات</h4>

            </div>

            <div class="box-content">

                <form method="GET" enctype="multipart/form-data" target="_blank"
                    action="{{ route('company/report/issuing/search/searchpdf') }}">
                    @csrf
                    <div class="row">
                        <div class="form-group  col-md-3">
                            <label for="inputName" class="control-label">الشركة</label>
                            <select name="companies_id" id="companies_id"
                                class="form-control @error('companies_id') is-invalid @enderror  select2  wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer" required>
                                <option value="0">الشركة </option>


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
                                <option value="">اختر مستخدم </option>

                                @forelse ($companyUser as $companyUer)
                                    <option value="{{ $companyUer->id }}"> {{ $companyUer->username }}</option>
                                @empty
                                    <option value="">لايوجد مستخدمين</option>
                                @endforelse
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
                                <option value="">اختر مكتب </option>

                                @forelse ($Office as $Offi)
                                    <option value="{{ $Offi->id }}"> {{ $Offi->name }}</option>
                                @empty
                                    <option value="">لايوجد مكتب</option>
                                @endforelse
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
                            <input name="fromdate" id="fromdate" type="date"
                                class="form-control @error('fromdate') is-invalid @enderror   wd-250" required />


                            @error('fromdate')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName" class="control-label"> الي </label>
                            <input name="todate" id="todate" type="date"
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

            // Event listener for search button (assuming a button with id="search-btn" exists)
            $('#search-btn').click(function() {
                search();
            });

            $('select[name="offices_id"]').on('change', function() {
                checkSession();
                var offices_id = $(this).val();
                $('select[name="office_users_id"]').empty();
                const companyUsersDropdown = document.getElementById('company_users_id');
                companyUsersDropdown.value = ''; // Reset selected value

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


            $('select[name="company_users_id"]').on('change', function() {
                checkSession();
                const officesdd_id = document.getElementById('offices_id');
                officesdd_id.value = '';

                const office_users_iddid = document.getElementById('office_users_id');
                office_users_iddid.value = '';
            });

            // function confirmCancel(cardNumber) {
            //     checkSession();
            //     // Show a confirmation alert using SweetAlert
            //     swal.fire({
            //         title: 'هل أنت متأكد من إلغاء الوثيقة؟',
            //         text: 'سيتم إلغاء الوثيقة. هل أنت متأكد؟',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'موافق',

            //         cancelButtonText: 'إلغاء العملية'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             // Disable the button to prevent multiple clicks
            //             $('button[data-cardnumber="' + cardNumber + '"]').prop('disabled', true);
            //             $('#loader-overlay').show();

            //             // Send the AJAX request
            //             $.ajax({
            //                 url: '/company/cancelplicy/' + cardNumber, // Adjust URL as necessary
            //                 type: 'GET',
            //                 data: {
            //                     // Add any additional data you need here
            //                     '_token': $('meta[name="csrf-token"]').attr('content')
            //                 },
            //                 success: function(response) {
            //                     // Show success message based on the response
            //                     $('#loader-overlay').hide();

            //                     swal.fire({
            //                         title: response.status,
            //                         text: response.message,
            //                         icon: response.status,
            //                         confirmButtonColor: '#3085d6'
            //                     }).then(() => {
            //                         // Optionally, reload the page or update the UI
            //                         location.reload();
            //                         $('#loader-overlay').hide();

            //                     });
            //                 },
            //                 error: function(xhr) {
            //                     // Handle errors and show error message
            //                     var message = xhr.responseJSON.message || 'حدث خطأ أثناء عملية الإلغاء';
            //                     $('#loader-overlay').hide();

            //                     swal.fire({
            //                         title: 'خطأ',
            //                         text: message,
            //                         icon: 'error',
            //                         confirmButtonColor: '#3085d6'
            //                     }).then(() => {
            //                         // Re-enable the button in case of error
            //                         $('button[data-cardnumber="' + cardNumber + '"]').prop(
            //                             'disabled', false);
            //                     });


            //                 }
            //             });
            //         }
            //     });
            // }
            function confirmCancel(cardNumber) {
  checkSession();

  const reasons = {
    'خطأ تقني': 'خطأ تقني',
    'مشكلة في الإنترنت': 'مشكلة في الإنترنت',
    'خطأ مدخل': 'خطأ مدخل'
  };

  Swal.fire({
    title: 'اختر سبب الإلغاء',
    input: 'select',
    inputOptions: reasons,
    inputPlaceholder: 'سبب الإلغاء',
    showCancelButton: true,
    confirmButtonText: 'التالي',
    cancelButtonText: 'إلغاء'
  }).then((step1) => {
    if (!step1.isConfirmed) return;

    const reason = step1.value;
    if (!reason) {
      Swal.fire('الرجاء اختيار سبب الإلغاء');
      return;
    }

    Swal.fire({
      title: 'تأكيد الإلغاء',
      text: `سيتم إلغاء الوثيقة بسبب: (${reason}). هل أنت متأكد؟`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'موافق',
      cancelButtonText: 'إلغاء'
    }).then((result) => {
      if (!result.isConfirmed) return;

      // تعطيل زر الإلغاء لهذه البطاقة (اختياري)
      $('button[data-cardnumber="' + cardNumber + '"]').prop('disabled', true);
      $('#loader-overlay').show();

      $.ajax({
        url: '/company/cancelplicy/' + cardNumber,
        type: 'POST',
        data: {
          _token: "{{ csrf_token() }}",
          cancel_reason: reason
        },
        success: function(response) {
          $('#loader-overlay').hide();

          Swal.fire({
            title: response.status,
            text: response.message,
            icon: response.status,
            confirmButtonColor: '#3085d6'
          }).then(() => {
            location.reload();
          });
        },
        error: function(xhr) {
          $('#loader-overlay').hide();
          const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'حدث خطأ أثناء عملية الإلغاء';
          Swal.fire('خطأ', msg, 'error').then(() => {
            $('button[data-cardnumber="' + cardNumber + '"]').prop('disabled', false);
          });
        }
      });
    });
  });
}


function search() {
    checkSession();
    $('#loader-overlay').show();

    var userTypeId = @json(auth()->user()->user_type_id);

    var companies_id = $('#companies_id').val();
    var office_users_id = $('#office_users_id').val();
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
        return;
    }

    const from = new Date(fromdate);
    const to = new Date(todate);
    const monthDiff = (to.getFullYear() - from.getFullYear()) * 12 + (to.getMonth() - from.getMonth());
    if (monthDiff > 3 || (monthDiff === 3 && to.getDate() > from.getDate())) {
        $('#loader-overlay').hide();
        Swal.fire("يجب أن تكون الفترة 3 أشهر أو أقل فقط.");
        return;
    }

    if (companies_id || offices_id || office_users_id || card_number || insurance_name || plate_number || chassis_number || company_users_id || (fromdate && todate)) {
        $.ajax({
            url: '../../company/report/issuing/searchby',
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
                $('#loader-overlay').hide();

                if (response.code == 1) {
                    var extraColumnHeader = '<th> الغاء الوثيقة </th>';

                    $('#searchs').html(
                        '<div class="table-responsive" data-pattern="priority-columns">' +
                        '<table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom" style="cursor: pointer;">' +
                        '<thead>' +
                        '<tr>' +
                        '<th>#</th>' +
                        '<th>رقم البطاقة</th>' +
                        '<th>المُصدر</th>' +
                        '<th>المكتب</th>' +
                        '<th>المؤمن له</th>' +
                        '<th>تاريخ الاصدار</th>' +
                        '<th>صافي القسط</th>' +
                        '<th>الضريبة</th>' +
                        '<th>رسم الدمغة</th>' +
                        '<th>الإشراف</th>' +
                        '<th>الإصدار</th>' +
                        '<th>الاجمالي</th>' +
                        '<th>مدة التأمين من</th>' +
                        '<th>مدة التأمين الى</th>' +
                        '<th>عدد الايام</th>' +
                        '<th>نوع المركبة</th>' +
                        '<th>رقم اللوحة</th>' +
                        '<th>رقم الهيكل</th>' +
                        '<th>رقم المحرك</th>' +
                        '<th>عرض الوثيقة</th>' +
                        extraColumnHeader +
                        '</tr>' +
                        '</thead>' +
                        '<tbody id="rowsss"></tbody>' +
                        '<tfoot id="table-footer"></tfoot>' +
                        '</table>' +
                        '</div>'
                    );

                    let x = 0;
                    let totalInstallment = 0,
                        totalTax = 0,
                        totalStamp = 0,
                        totalSupervision = 0,
                        totalVersion = 0,
                        totalInsurance = 0;

                    response.data.forEach((item, i) => {
                        totalInstallment += parseFloat(item.insurance_installment) || 0;
                        totalTax += parseFloat(item.insurance_tax) || 0;
                        totalStamp += parseFloat(item.insurance_stamp) || 0;
                        totalSupervision += parseFloat(item.insurance_supervision) || 0;
                        totalVersion += parseFloat(item.insurance_version) || 0;
                        totalInsurance += parseFloat(item.insurance_total) || 0;

                        const companies = item.companies ? item.companies.name : 'الإتحاد الليبي للتأمين';
                        const offices = item.offices ? item.offices.name : 'الفرع الرئيسي';
                        let user = '';
                        if (item.company_users_id) user = item.company_users ? item.company_users.username : '';
                        else if (item.office_users_id) user = item.office_users ? item.office_users.username : '';

                        const cardNumber = item.cards_id;
                        const url = "{{ route('company/viewdocument', ['cardnumber' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', encodeURIComponent(cardNumber));
                        const viewDocLink = '<a style="color: #f97424;" target="_blank" href="' + url + '">' +
                            '<img src="{{ asset('contract.png') }}" style="width: 50%;"></a>';

                        const cancelPolicyHtml = userTypeId == 1 ?
                            '<a style="color: #f97424; cursor:pointer;" onclick="return confirmCancel(' + cardNumber + ');">' +
                            '<img src="{{ asset('ccc.png') }}" style="width: 50%;"></a>' : '';

                        const card = item.cards ? item.cards.card_number : item.id;

                        $('#rowsss').append(
                            '<tr>' +
                            '<td>' + (++x) + '</td>' +
                            '<td>' + card + '</td>' +
                            '<td>' + user + '</td>' +
                            '<td>' + offices + '</td>' +
                            '<td>' + item.insurance_name + '</td>' +
                            '<td>' + item.issuing_date + '</td>' +
                            '<td>' + item.insurance_installment + '</td>' +
                            '<td>' + item.insurance_tax + '</td>' +
                            '<td>' + item.insurance_stamp + '</td>' +
                            '<td>' + item.insurance_supervision + '</td>' +
                            '<td>' + item.insurance_version + '</td>' +
                            '<td>' + item.insurance_total + '</td>' +
                            '<td>' + item.insurance_day_from + '</td>' +
                            '<td>' + item.nsurance_day_to + '</td>' +
                            '<td>' + item.insurance_days_number + '</td>' +
                            '<td>' + (item.cars ? item.cars.name : item.cars_id) + '</td>' +
                            '<td>' + item.plate_number + '</td>' +
                            '<td>' + item.chassis_number + '</td>' +
                            '<td>' + item.motor_number + '</td>' +
                            '<td>' + viewDocLink + '</td>' +
                            '<td>' + cancelPolicyHtml + '</td>' +
                            '</tr>'
                        );
                    });

                    const footerHtml = '<tr>' +
                        '<th colspan="6" style="text-align:center;">الإجمالي</th>' +
                        '<th>' + totalInstallment.toFixed(3) + '</th>' +
                        '<th>' + totalTax.toFixed(3) + '</th>' +
                        '<th>' + totalStamp.toFixed(3) + '</th>' +
                        '<th>' + totalSupervision.toFixed(3) + '</th>' +
                        '<th>' + totalVersion.toFixed(3) + '</th>' +
                        '<th>' + totalInsurance.toFixed(3) + '</th>' +
                        '<th colspan="8"></th>' +
                        '</tr>';
                    $('#table-footer').html(footerHtml);

                    $('#datatable1').DataTable({
                        language: { url: "{{ asset('Arabic.json') }}" },
                        lengthMenu: [10, 20, 30, 50],
                        paging: true,
                        searching: true,
                        ordering: true,
                        dom: 'Blfrtip',
                        buttons: [
                            { extend: 'copyHtml5', text: 'نسخ', exportOptions: { columns: ':visible' } },
                            { extend: 'excelHtml5', text: 'excel تصدير كـ ', exportOptions: { columns: ':visible' }, title: 'تقرير المبيعات من ' + fromdate + ' إلى ' + todate }
                        ]
                    });
                } else {
                    swal.fire("لايوجد مبيعات");
                    $('#searchs').html("");
                }
            },
            error: function() {
                $('#loader-overlay').hide();
                swal.fire("حدث خطأ في الاتصال بالخادم");
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
