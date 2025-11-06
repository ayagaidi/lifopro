@extends('layouts.app')
@section('title', 'تقارير المبيعات ')

@section('content')
  {{-- CSS/JS خارجية --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/@linways/table-to-excel@1.0.4/dist/tableToExcel.min.js" defer></script>
  <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

  <style>
    /* مُحمّل بسيط */
    #loader-overlay {
      position: fixed; inset: 0; background: rgba(255,255,255,0.7);
      display: none; z-index: 9999; align-items: center; justify-content: center;
      font-weight: bold;
    }
  </style>

  <div id="loader-overlay">جارِ المعالجة...</div>

  <script>
    // إعدادات أولية وربط الأزرار
    $(document).ready(function () {
      // Select2
      $('#companies_id,#company_users_id,#offices_id,#office_users_id').select2({
        placeholder: "اختر ...",
        allowClear: true,
        language: "ar"
      });

      // مراقبة الجلسة
      $(document).on('change', 'select, input', checkSession);

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
      // بحث
      $(document).on('click', '#search-btn', function () { search(1); });

      // طباعة الصفحة الحالية (لو عندك زر .print_table في مكان آخر)
      $(document).on('click', 'button.print_table', function () {
        $("#datatable1 tr").css("display", "table-row");
        $("#datatable1 td, #datatable1 th").css("display", "table-cell");
        printCurrentTable();
      });

      // طباعة كل النتائج
      $(document).on('click', '#btnPrintAll', handlePrintAll);

      // تصدير Excel للصفحة الحالية
      $(document).on('click', '#btnExportXls', function () {
        const table = document.getElementById('datatable1');
        if (!table) {
          Swal.fire("تنبيه", "لا يوجد جدول لعرضه/تصديره.", "info");
          return;
        }
        const cloned = table.cloneNode(true);
        hideViewColumnInTable(cloned);

        const from = ($('#fromdate').val() || '').replace(/-/g, '');
        const to   = ($('#todate').val()   || '').replace(/-/g, '');
        const fname = from && to ? `sales_report_${from}_${to}.xlsx` : 'sales_report.xlsx';

        if (window.TableToExcel?.convert) {
          TableToExcel.convert(cloned, { name: fname, sheet: { name: 'Sheet 1' } });
        } else {
          Swal.fire("خطأ", "مكوّن التصدير إلى Excel غير متاح. تأكد من تحميل @linways/table-to-excel.", "error");
        }
      });

      // تصدير Excel (كل النتائج) - اسم الراوت الصحيح
      $(document).on('click', '#btnExportAllXls', function () {
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

        if (!$('#fromdate').val() || !$('#todate').val()) {
          Swal.fire("تنبيه", "الرجاء اختيار تاريخ البدء وتاريخ النهاية", "warning");
          return;
        }

        window.location.href = "{{ route('report.issuing.export-xlsx') }}?" + q;
      });
    });

    // حالة الجلسة
    function redirectToLogin() {
      const loginForm = document.createElement('form');
      loginForm.method = 'POST';
      loginForm.action = "{{ route('login') }}";
      const csrfInput = document.createElement('input');
      csrfInput.type = 'hidden';
      csrfInput.name = '_token';
      csrfInput.value = "{{ csrf_token() }}";
      loginForm.appendChild(csrfInput);
      document.body.appendChild(loginForm);
      loginForm.submit();
    }
    function checkSession() {
      const sessionExpired = document.getElementById('session-status')?.getAttribute('data-session-expired');
      if (sessionExpired === 'true') redirectToLogin();
    }
  </script>

  <div id="session-status" data-session-expired="{{ Auth::check() ? 'false' : 'true' }}"></div>

  <div class="row small-spacing">
    <div class="col-md-12">
      <div class="box-content">
        <h4 class="box-title"><a href="{{ route('report/issuing') }}">ادارة التقارير</a>/ تقاريرالمبيعات @if(isset($year)) {{ $year }} @endif</h4>
      </div>

      <div class="box-content">
        <form method="GET" enctype="multipart/form-data" target="_blank" action="{{ route('report/issuing/search') }}">
          @csrf
          <div class="row">
            <div class="form-group col-md-3">
              <label class="control-label">الشركة</label>
              <select name="companies_id" id="companies_id"
                class="form-control @error('companies_id') is-invalid @enderror select2 wd-250"
                data-placeholder="Choose one" required>
                <option value=" ">اختر </option>
                @forelse ($Company as $com)
                  <option value="{{ $com->id }}"> {{ $com->name }}</option>
                @empty
                  <option value="">لايوجد شركات</option>
                @endforelse
              </select>
              @error('companies_id') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label">مستخدم الشركة </label>
              <select name="company_users_id" id="company_users_id"
                class="form-control @error('company_users_id') is-invalid @enderror"></select>
              @error('company_users_id') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label">المكتب</label>
              <select name="offices_id" id="offices_id"
                class="form-control @error('offices_id') is-invalid @enderror select2 wd-250"></select>
              @error('offices_id') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label">مستخدم المكتب </label>
              <select name="office_users_id" id="office_users_id"
                class="form-control @error('office_users_id') is-invalid @enderror"></select>
              @error('office_users_id') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label">اسم العميل </label>
              <input type="text" name="insurance_name" id="insurance_name"
                class="form-control @error('insurance_name') is-invalid @enderror" placeholder="اسم العميل ">
              @error('insurance_name') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label">رقم البطاقة </label>
              <input type="text" name="card_number" id="card_number"
                class="form-control @error('card_number') is-invalid @enderror" placeholder="رقم البطاقة">
              @error('card_number') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label"> رقم اللوحة </label>
              <input type="text" name="plate_number" id="plate_number"
                class="form-control @error('plate_number') is-invalid @enderror" placeholder="اللوحة المعدنية">
              @error('plate_number') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="control-label"> رقم الهيكل </label>
              <input type="text" name="chassis_number" id="chassis_number"
                class="form-control @error('chassis_number') is-invalid @enderror" placeholder="رقم الهيكل">
              @error('chassis_number') <span class="invalid-feedback" style="color:red">{{ $message }}</span> @enderror
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

          <div class="form-group col-md-12" style="text-align:left;">
            <button type="button" onclick="search(1)" id="search-btn" class="btn btn-primary waves-effect">بحث</button>
            <!--<button type="button" id="btnPrintAll" class="btn btn-warning waves-effect">طباعة كل النتائج</button>-->
           
            <button type="button" id="btnExportAllXls" class="btn btn-success waves-effect">تصدير Excel (كل النتائج)</button>
            <!--{{-- زر PDF الأصلي --}}-->
            <!--<button type="submit" class="btn btn-primary waves-effect">تصدير ك PDF</button>-->
            <button type="button" id="btnExportAllPdf" class="btn btn-danger waves-effect">تصدير PDF (كل النتائج)</button>
            @if(isset($year))
            <script>
                $(document).on('click', '#btnExportAllPdf', function () {
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

          </div>
        </form>
      </div>

      <div class="row small-spacing">
        <div class="col-md-12">
          <div class="box-content ">
            <h4 class="box-title">عرض الكل</h4>

            {{-- رأس التقرير للطباعة --}}
            <div id="report-header" style="display:none">
              <div class="row">
                <div class="col-md-4">
                  <p style="font-weight:bold">العنوان: الإتحاد الليبي للتأمين</p>
                  <p style="font-weight:bold"><a href="mailto:info@insurancefed.ly">info@insurancefed.ly :البريد الالكتروني</a></p>
                  <p style="font-weight:bold"><a href="http://www.insurancefed.ly">www.insurancefed.ly: الموقع الالكتروني</a></p>
                </div>
                <div class="col-md-4 text-center" style="text-align:center">
                  <img src="{{ asset('logo.png') }}" alt="Report Image"
                       style="display:block;margin:auto;max-width:80px;text-align:center !important;">
                  <h4 style="font-weight:bold">دولــة لـيـبـيـا</h4>
                  <h4 style="font-weight:bold">الاتـــحـاد الليبي للتأمين</h4>
                </div>
                <div class="col-md-4">
                  <p style="font-weight:bold">التاريخ: <span id="report-date"></span></p>
                  <p style="font-weight:bold"><span id="report-creator">{{ Auth::user()->username }}: تم إنشاؤه من قبل</span></p>
                </div>
              </div>
            </div>

            {{-- جدول الصفحة الحالية --}}
            <div class="table-responsive" data-pattern="priority-columns" id="searchs"></div>

            {{-- جدول مخفي لتجميع كل النتائج للطباعة/التصدير --}}
            <div id="allResultsContainer" style="display:none">
              <table id="datatableAll" class="table table-bordered table-hover table-custom" style="cursor:pointer;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>رقم البطاقة</th>
                    <th>المُصدر</th>
                    <th>الشركة</th>
                    <th>المكتب</th>
                    <th>المؤمن له</th>
                    <th>تاريخ الاصدار</th>
                    <th>صافي القسط</th>
                    <th>الضريبة</th>
                    <th>رسم الدمغة</th>
                    <th>الإشراف</th>
                    <th>الإصدار</th>
                    <th>الإجمالي</th>
                    <th>مدة التأمين من</th>
                    <th>مدة التأمين إلى</th>
                    <th>عدد الأيام</th>
                    <th>نوع المركبة</th>
                    <th>رقم اللوحة</th>
                    <th>رقم الهيكل</th>
                    <th>رقم المحرك</th>
                    <th class="view">عرض الوثيقة</th>
                  </tr>
                </thead>
                <tbody id="allRows"></tbody>
                <tfoot id="allFooter"></tfoot>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentPage = 1;
    let lastPage    = 1;
    let perPage     = 20;

    function search(page = 1) {
      checkSession();
      $('#loader-overlay').show();

      const payload = {
        offices_id:       $('#offices_id').val() || '',
        companies_id:     $('#companies_id').val() || '',
        office_users_id:  $('#office_users_id').val() || '',
        insurance_name:   $('#insurance_name').val() || '',
        card_number:      $('#card_number').val() || '',
        chassis_number:   $('#chassis_number').val() || '',
        plate_number:     $('#plate_number').val() || '',
        company_users_id: $('#company_users_id').val() || '',
        fromdate:         $('#fromdate').val(),
        todate:           $('#todate').val(),
        page:             page,
        per_page:         perPage
      };

      if (!payload.fromdate || !payload.todate) {
        $('#loader-overlay').hide();
        Swal.fire("تنبيه", "الرجاء اختيار تاريخ البدء وتاريخ النهاية", "warning");
        return;
      }

      var searchUrl = '../../report/issuing/searchby';
      @if(isset($year))
        searchUrl = '../../report/issuing/{{ $year }}/searchby';
      @endif

      $.ajax({
        url: searchUrl,
        type: 'GET',
        data: payload,
        success: function (response) {
          $('#loader-overlay').hide();

          if (response.code === 1 && Array.isArray(response.data) && response.data.length) {
            currentPage = response.pagination?.current_page || 1;
            lastPage    = response.pagination?.last_page || 1;

            let totalInstallment = 0,
                totalTax         = 0,
                totalStamp       = 0,
                totalSupervision = 0,
                totalVersion     = 0,
                totalInsurance   = 0;

            // بناء هيكل الجدول
            $('#searchs').html(
              '<table id="datatable1" class="table table-bordered table-hover table-custom" style="cursor:pointer;">' +
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
                    '<th>مدة التأمين من</th>' +
                    '<th>مدة التأمين إلى</th>' +
                    '<th>عدد الأيام</th>' +
                    '<th>نوع المركبة</th>' +
                    '<th>رقم اللوحة</th>' +
                    '<th>رقم الهيكل</th>' +
                    '<th>رقم المحرك</th>' +
                    '<th class="view">عرض الوثيقة</th>' +
                  '</tr>' +
                '</thead>' +
                '<tbody id="rowsss"></tbody>' +
                '<tfoot id="table-footer"></tfoot>' +
              '</table>'
            );

            let rowIndex = 0;
            response.data.forEach(function (item) {
              const { rowHtml, sums } = buildRowAndSum(item, ++rowIndex);
              $('#rowsss').append(rowHtml);

              totalInstallment += sums.installment;
              totalTax         += sums.tax;
              totalStamp       += sums.stamp;
              totalSupervision += sums.supervision;
              totalVersion     += sums.version;
              totalInsurance   += sums.total;
            });

            // فوتر إجمالي الصفحة الحالية
            const footerHtml =
              '<tr>' +
                '<th colspan="7" style="text-align:center;">الإجمالي</th>' +
                '<th>' + totalInstallment.toFixed(3) + '</th>' +
                '<th>' + totalTax.toFixed(3) + '</th>' +
                '<th>' + totalStamp.toFixed(3) + '</th>' +
                '<th>' + totalSupervision.toFixed(3) + '</th>' +
                '<th>' + totalVersion.toFixed(3) + '</th>' +
                '<th>' + totalInsurance.toFixed(3) + '</th>' +
                '<th colspan="8"></th>' +
              '</tr>';
            $('#table-footer').html(footerHtml);

            // ترقيم الصفحات
            const paginationHtml =
              '<div class="text-center my-2">' +
                '<button class="btn btn-primary mx-1" onclick="goToPage('+(currentPage - 1)+')" '+(currentPage === 1 ? 'disabled' : '')+'>السابق</button>' +
                '<span>صفحة '+currentPage+' من '+lastPage+'</span>' +
                '<button class="btn btn-primary mx-1" onclick="goToPage('+(currentPage + 1)+')" '+(currentPage === lastPage ? 'disabled' : '')+'>التالي</button>' +
              '</div>';
            $('#searchs').append(paginationHtml);

            // إجمالي كل النتائج (من السيرفر)
            $('#grand-total-section').remove();
            const tot = response.totals || {};
            const grandTotalsHtml =
              '<div id="grand-total-section" class="text-center my-2">' +
                '<h5 style="font-weight:bold">الإجمالي الكلي للبحث (كل النتائج)</h5>' +
                '<table class="table table-bordered" style="max-width:600px;margin:auto">' +
                  '<tr><th>صافي القسط</th><td>' + (parseFloat(tot.total_installment || 0).toFixed(3)) + '</td></tr>' +
                  '<tr><th>الضريبة</th><td>'        + (parseFloat(tot.total_tax || 0).toFixed(3)) + '</td></tr>' +
                  '<tr><th>رسم الدمغة</th><td>'     + (parseFloat(tot.total_stamp || 0).toFixed(3)) + '</td></tr>' +
                  '<tr><th>الإشراف</th><td>'        + (parseFloat(tot.total_supervision || 0).toFixed(3)) + '</td></tr>' +
                  '<tr><th>الإصدار</th><td>'        + (parseFloat(tot.total_version || 0).toFixed(3)) + '</td></tr>' +
                  '<tr><th>الإجمالي الكلي</th><td>' + (parseFloat(tot.total_insurance || 0).toFixed(3)) + '</td></tr>' +
                '</table>' +
              '</div>';
            $('#searchs').append(grandTotalsHtml);

            if (response.warning) Swal.fire("تنبيه", response.warning, "info");

          } else if (response.code === 4) {
            Swal.fire("تحذير", response.message || "عدد النتائج كبير جداً، الرجاء تقليص الفترة.", "warning");
            $('#searchs').html("");
          } else {
            Swal.fire("لايوجد مبيعات", "لم يتم العثور على نتائج.", "info");
            $('#searchs').html("");
          }
        },
        error: function (xhr) {
          $('#loader-overlay').hide();
          const msg = xhr?.responseJSON?.message || "حدث خطأ أثناء البحث.";
          Swal.fire("خطأ", msg, "error");
          $('#searchs').html("");
        }
      });
    }

    function goToPage(page) {
      if (page >= 1 && page <= lastPage) search(page);
    }

    // يبني صف + يُرجع قيم المجاميع لتسهيل الجمع
    function buildRowAndSum(item, index) {
      let companies = 'الإتحاد الليبي للتأمين';
      let offices   = 'الفرع الرئيسي';
      let user      = '-';

      if (item.offices) {
        offices   = item.offices?.name ?? offices;
        companies = item.offices?.companies?.name ?? companies;
      } else if (item.companies) {
        companies = item.companies?.name ?? companies;
      }

      if (item.office_users_id && item.office_users) {
        user = item.office_users?.username ?? user;
      } else if (item.company_users_id && item.company_users) {
        user = item.company_users?.username ?? user;
      }

      const card = item.cards ? (item.cards.card_number ?? item.id) : item.id;
      const cars = item.cars ? (item.cars.name ?? item.cars_id) : (item.cars_id ?? '-');

      const cardNumberForRoute = item.cards_id; // غيّرها لو مسارك يعتمد card_number
      const baseUrl = "{{ route('viewdocument', ['cardnumber' => 'PLACEHOLDER']) }}";
      const url     = baseUrl.replace('PLACEHOLDER', encodeURIComponent(cardNumberForRoute));
      const docLink =
        '<a style="color:#f97424;" target="_blank" href="'+url+'">' +
          '<img src="{{ asset('contract.png') }}" style="width:50%;">' +
        '</a>';

      const rowHtml =
        '<tr>' +
          '<td>' + index + '</td>' +
          '<td>' + card + '</td>' +
          '<td>' + user + '</td>' +
          '<td>' + companies + '</td>' +
          '<td>' + offices + '</td>' +
          '<td>' + (item.insurance_name ?? '-') + '</td>' +
          '<td>' + (item.issuing_date ?? '-') + '</td>' +
          '<td>' + (item.insurance_installment ?? 0) + '</td>' +
          '<td>' + (item.insurance_tax ?? 0) + '</td>' +
          '<td>' + (item.insurance_stamp ?? 0) + '</td>' +
          '<td>' + (item.insurance_supervision ?? 0) + '</td>' +
          '<td>' + (item.insurance_version ?? 0) + '</td>' +
          '<td>' + (item.insurance_total ?? 0) + '</td>' +
          '<td>' + (item.insurance_day_from ?? '-') + '</td>' +
          '<td>' + (item.insurance_day_to ?? '-') + '</td>' + // تم الإصلاح هنا (إزالة تعليق HTML)
          '<td>' + (item.insurance_days_number ?? '-') + '</td>' +
          '<td>' + cars + '</td>' +
          '<td>' + (item.plate_number ?? '-') + '</td>' +
          '<td>' + (item.chassis_number ?? '-') + '</td>' +
          '<td>' + (item.motor_number ?? '-') + '</td>' +
          '<td class="view">' + docLink + '</td>' +
        '</tr>';

      return {
        rowHtml,
        sums: {
          installment: parseFloat(item.insurance_installment) || 0,
          tax:         parseFloat(item.insurance_tax) || 0,
          stamp:       parseFloat(item.insurance_stamp) || 0,
          supervision: parseFloat(item.insurance_supervision) || 0,
          version:     parseFloat(item.insurance_version) || 0,
          total:       parseFloat(item.insurance_total) || 0
        }
      };
    }

    // جلب صفحة واحدة بنفس فلاتر البحث
    function fetchPage(page, per_page) {
      const payload = {
        offices_id:       $('#offices_id').val() || '',
        companies_id:     $('#companies_id').val() || '',
        office_users_id:  $('#office_users_id').val() || '',
        insurance_name:   $('#insurance_name').val() || '',
        card_number:      $('#card_number').val() || '',
        chassis_number:   $('#chassis_number').val() || '',
        plate_number:     $('#plate_number').val() || '',
        company_users_id: $('#company_users_id').val() || '',
        fromdate:         $('#fromdate').val(),
        todate:           $('#todate').val(),
        page:             page,
        per_page:         per_page
      };

      var searchUrl = '../../report/issuing/searchby';
      @if(isset($year))
        searchUrl = '../../report/issuing/{{ $year }}/searchby';
      @endif

      return $.ajax({ url: searchUrl, type: 'GET', data: payload });
    }

    // طباعة HTML
    function printHtmlTable(html) {
      const today = new Date().toLocaleDateString('ar-LY');
      const reportDate = document.getElementById('report-date');
      if (reportDate) reportDate.textContent = today;

      const headerHtml = document.getElementById('report-header') ? document.getElementById('report-header').outerHTML : '';
      const printHtml  = headerHtml + html;

      printJS({
        printable: printHtml,
        type: 'raw-html',
        style: `
          #datatableAll, #datatable1 { width:100%; border-collapse:collapse; direction:rtl; }
          #datatableAll td, #datatableAll th,
          #datatable1  td, #datatable1  th { border:1px solid #000; font-size:12px; padding:4px; text-align:center; }
          #report-header { margin-bottom:20px; }
          #report-header .col-md-4 { display:inline-block; vertical-align:top; width:30%; padding:0 10px; text-align:right; }
          @media print {
            .view, .no-print { display:none; }
            #report-header { display:block; }
          }
        `,
        scanStyles: true,
        header: ''
      });
    }

    // إخفاء عمود "عرض الوثيقة" داخل جدول مستنسخ
    function hideViewColumnInTable(tableEl) {
      const ths = tableEl.querySelectorAll('thead th');
      ths.forEach((th, idx) => {
        if (th.classList.contains('view')) {
          th.style.display = 'none';
          tableEl.querySelectorAll('tbody tr').forEach(tr => {
            const td = tr.children[idx];
            if (td) td.style.display = 'none';
          });
          const tf = tableEl.querySelector('tfoot');
          if (tf) {
            tf.querySelectorAll('tr').forEach(tr => {
              const td = tr.children[idx];
              if (td) td.style.display = 'none';
            });
          }
        }
      });
    }

    // طباعة كل النتائج (تجميع كل الصفحات)
    async function handlePrintAll() {
      if (!$('#fromdate').val() || !$('#todate').val()) {
        Swal.fire("تنبيه", "الرجاء اختيار تاريخ البدء وتاريخ النهاية", "warning");
        return;
      }

      $('#loader-overlay').show();
      try {
        // الصفحة الأولى لمعرفة عدد الصفحات
        const first = await fetchPage(1, perPage);
        if (!(first && first.code === 1 && Array.isArray(first.data))) {
          $('#loader-overlay').hide();
          Swal.fire("لايوجد بيانات", "لم يتم العثور على نتائج.", "info");
          return;
        }

        const totalPages = first.pagination?.last_page || 1;

        if (totalPages > 50) {
          $('#loader-overlay').hide();
          const res = await Swal.fire({
            title: "تحذير",
            text: `عدد الصفحات ${totalPages} — قد يستغرق الطباعة وقتًا ويكون الملف كبيرًا. هل تريد المتابعة؟`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "نعم",
            cancelButtonText: "إلغاء"
          });
          if (!res.isConfirmed) { return; }
          $('#loader-overlay').show();
        }

        // إعادة ضبط الجدول الموحّد
        $('#allRows').html('');
        $('#allFooter').html('');

        let totalInstallment = 0,
            totalTax         = 0,
            totalStamp       = 0,
            totalSupervision = 0,
            totalVersion     = 0,
            totalInsurance   = 0;

        // أضف الصفحة الأولى
        let idx = 0;
        first.data.forEach(item => {
          const { rowHtml, sums } = buildRowAndSum(item, ++idx);
          $('#allRows').append(rowHtml);
          totalInstallment += sums.installment;
          totalTax         += sums.tax;
          totalStamp       += sums.stamp;
          totalSupervision += sums.supervision;
          totalVersion     += sums.version;
          totalInsurance   += sums.total;
        });

        // بقية الصفحات
        for (let p = 2; p <= totalPages; p++) {
          const resp = await fetchPage(p, perPage);
          if (resp && resp.code === 1 && Array.isArray(resp.data)) {
            resp.data.forEach(item => {
              const { rowHtml, sums } = buildRowAndSum(item, ++idx);
              $('#allRows').append(rowHtml);
              totalInstallment += sums.installment;
              totalTax         += sums.tax;
              totalStamp       += sums.stamp;
              totalSupervision += sums.supervision;
              totalVersion     += sums.version;
              totalInsurance   += sums.total;
            });
          }
        }

        const footerHtml =
          '<tr>' +
            '<th colspan="7" style="text-align:center;">الإجمالي (كل النتائج المطبوعة)</th>' +
            '<th>' + totalInstallment.toFixed(3) + '</th>' +
            '<th>' + totalTax.toFixed(3) + '</th>' +
            '<th>' + totalStamp.toFixed(3) + '</th>' +
            '<th>' + totalSupervision.toFixed(3) + '</th>' +
            '<th>' + totalVersion.toFixed(3) + '</th>' +
            '<th>' + totalInsurance.toFixed(3) + '</th>' +
            '<th colspan="8"></th>' +
          '</tr>';
        $('#allFooter').html(footerHtml);

        const allHtml = document.getElementById('datatableAll').outerHTML;
        printHtmlTable(allHtml);

      } catch (e) {
        console.error(e);
        Swal.fire("خطأ", "تعذّر تجميع كل النتائج للطباعة.", "error");
      } finally {
        $('#loader-overlay').hide();
      }
    }

    // تصدير كل النتائج إلى Excel (دمج كل الصفحات في ورقة واحدة) - (إبقاءها للنسخة المتصفح إن رغبت)
    async function handleExportAllXls() {
      // ملاحظة: يفضّل استخدام التصدير الخادمي عبر الراوت أعلاه.
      if (!$('#fromdate').val() || !$('#todate').val()) {
        Swal.fire("تنبيه", "الرجاء اختيار تاريخ البدء وتاريخ النهاية", "warning");
        return;
      }

      if (!window.TableToExcel?.convert) {
        Swal.fire("خطأ", "مكوّن التصدير إلى Excel غير متاح. تأكد من تحميل @linways/table-to-excel.", "error");
        return;
      }

      $('#loader-overlay').show();
      try {
        const first = await fetchPage(1, perPage);
        if (!(first && first.code === 1 && Array.isArray(first.data) && first.data.length)) {
          $('#loader-overlay').hide();
          Swal.fire("لايوجد بيانات", "لم يتم العثور على نتائج.", "info");
          return;
        }

        const totalPages = first.pagination?.last_page || 1;

        if (totalPages > 50) {
          $('#loader-overlay').hide();
          const res = await Swal.fire({
            title: "تحذير",
            html: `عدد الصفحات <b>${totalPages}</b> — قد يستغرق إنشاء الملف وقتًا ويكون حجمه كبيرًا. المتابعة؟`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "نعم",
            cancelButtonText: "إلغاء"
          });
          if (!res.isConfirmed) return;
          $('#loader-overlay').show();
        }

        // إعادة ضبط الجدول الموحّد
        $('#allRows').html('');
        $('#allFooter').html('');

        let totalInstallment = 0,
            totalTax         = 0,
            totalStamp       = 0,
            totalSupervision = 0,
            totalVersion     = 0,
            totalInsurance   = 0;

        let idx = 0;

        // الصفحة الأولى
        first.data.forEach(item => {
          const { rowHtml, sums } = buildRowAndSum(item, ++idx);
          $('#allRows').append(rowHtml);
          totalInstallment += sums.installment;
          totalTax         += sums.tax;
          totalStamp       += sums.stamp;
          totalSupervision += sums.supervision;
          totalVersion     += sums.version;
          totalInsurance   += sums.total;
        });

        // بقية الصفحات
        for (let p = 2; p <= totalPages; p++) {
          const resp = await fetchPage(p, perPage);
          if (resp && resp.code === 1 && Array.isArray(resp.data)) {
            resp.data.forEach(item => {
              const { rowHtml, sums } = buildRowAndSum(item, ++idx);
              $('#allRows').append(rowHtml);
              totalInstallment += sums.installment;
              totalTax         += sums.tax;
              totalStamp       += sums.stamp;
              totalSupervision += sums.supervision;
              totalVersion     += sums.version;
              totalInsurance   += sums.total;
            });
          }
        }

        // فوتر الإجماليات للملف الموحّد
        const footerHtml =
          '<tr>' +
            '<th colspan="7" style="text-align:center;">الإجمالي (كل النتائج)</th>' +
            '<th>' + totalInstallment.toFixed(3) + '</th>' +
            '<th>' + totalTax.toFixed(3) + '</th>' +
            '<th>' + totalStamp.toFixed(3) + '</th>' +
            '<th>' + totalSupervision.toFixed(3) + '</th>' +
            '<th>' + totalVersion.toFixed(3) + '</th>' +
            '<th>' + totalInsurance.toFixed(3) + '</th>' +
            '<th colspan="8"></th>' +
          '</tr>';
        $('#allFooter').html(footerHtml);

        // تجهيز نسخة للتصدير: إخفاء عمود "عرض الوثيقة"
        const tableAll = document.getElementById('datatableAll').cloneNode(true);
        hideViewColumnInTable(tableAll);

        // تسمية الملف
        const from = ($('#fromdate').val() || '').replace(/-/g, '');
        const to   = ($('#todate').val()   || '').replace(/-/g, '');
        const fname = from && to ? `sales_all_${from}_${to}.xlsx` : 'sales_all.xlsx';

        // التصدير
        TableToExcel.convert(tableAll, {
          name: fname,
          sheet: { name: 'All Results' }
        });

      } catch (e) {
        console.error(e);
        Swal.fire("خطأ", "تعذّر تصدير كل النتائج إلى Excel.", "error");
      } finally {
        $('#loader-overlay').hide();
      }
    }

    // طباعة الصفحة الحالية
    function printCurrentTable() {
      const today = new Date().toLocaleDateString('ar-LY');
      const reportDate = document.getElementById('report-date');
      if (reportDate) reportDate.textContent = today;

      $("#report-header").css("display", "block");

      const headerHtml = document.getElementById('report-header') ? document.getElementById('report-header').outerHTML : '';
      const tableHtml  = document.getElementById('datatable1')    ? document.getElementById('datatable1').outerHTML    : '';
      const printHtml  = headerHtml + tableHtml;

      printJS({
        printable: printHtml,
        type: 'raw-html',
        style: `
          #datatable1 { width:100%; border-collapse:collapse; direction:rtl; }
          #datatable1 td, #datatable1 th { border:1px solid #000; font-size:12px; padding:4px; text-align:center; }
          #report-header { margin-bottom:20px; }
          #report-header .col-md-4 { display:inline-block; vertical-align:top; width:30%; padding:0 10px; text-align:right; }
          @media print {
            .view, .no-print { display:none; }
            #report-header { display:block; }
          }
        `,
        scanStyles: true,
        header: ''
      });
      
      
      
    }

$(document).on('click', '#btnExportAllPdf', function () {
  if (!$('#fromdate').val() || !$('#todate').val()) {
    Swal.fire("تنبيه", "الرجاء اختيار تاريخ البدء وتاريخ النهاية", "warning");
    return;
  }

  // إظهار اللودر قبل بدء العملية
//   $('#loader-overlay').show();

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

  window.location.href = "{{ route('report.issuing.export-pdf') }}?" + q;
});

  </script>
@endsection
