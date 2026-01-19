@extends('layouts.app')
@section('title', 'تقرير البطاقات الملغية ')

@section('content')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <h4 class="box-title"><a href="{{ route('report/cancelcards') }}">التقارير</a>/ تقارير البطاقات الملغية  </h4>
            </div>

            <div class="box-content">
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="companies_id" class="control-label">الشركة</label>
                            <select name="companies_id" id="companies_id"
                                class="form-control @error('companies_id') is-invalid @enderror select2 wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer" required>
                                <option value="">اختر الشركة </option>
                                @forelse ($Company as $Compan)
                                    <option value="{{ $Compan->id }}"> {{ $Compan->name }}</option>
                                @empty
                                    <option value="">لايوجد الشركة</option>
                                @endforelse
                            </select>
                            @error('companies_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="offices_id" class="control-label">المكتب</label>
                            <select name="offices_id" id="offices_id"
                                class="form-control @error('offices_id') is-invalid @enderror select2 wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer">
                                <option value="">اختر المكتب </option>
                            </select>
                            @error('offices_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="company_users_id" class="control-label">مستخدم الشركة</label>
                            <select name="company_users_id" id="company_users_id"
                                class="form-control @error('company_users_id') is-invalid @enderror select2 wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer">
                                <option value="">اختر مستخدم الشركة </option>
                            </select>
                            @error('company_users_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="office_users_id" class="control-label">مستخدم المكتب</label>
                            <select name="office_users_id" id="office_users_id"
                                class="form-control @error('office_users_id') is-invalid @enderror select2 wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer">
                                <option value="">اختر مستخدم المكتب </option>
                            </select>
                            @error('office_users_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="request_number" class="control-label">رقم الطلب </label>
                            <input type="text" name="request_number" class="form-control @error('request_number') is-invalid @enderror"
                                value="{{ old('request_number') }}" id="request_number" placeholder="رقم الطلب">
                            @error('request_number')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="card_number" class="control-label">رقم البطاقة </label>
                            <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror"
                                value="{{ old('card_number') }}" id="card_number" placeholder="رقم البطاقة">
                            @error('card_number')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>تاريخ الالغاء</label>
                            <label for="fromdate" class="control-label"> من </label>
                            <input name="fromdate" id="fromdate" type="date"
                                class="form-control @error('fromdate') is-invalid @enderror wd-250" />
                            @error('fromdate')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                             <label for="todate" class="control-label"> الي </label>
                             <input name="todate" id="todate" type="date"
                                 class="form-control @error('todate') is-invalid @enderror wd-250" />
                             @error('todate')
                                 <span class="invalid-feedback" style="color: red" role="alert">
                                     {{ $message }}
                                 </span>
                             @enderror
                         </div>

                         <div class="form-group col-md-3">
                             <label for="res" class="control-label">سبب الإلغاء</label>
                             <select name="res" id="res"
                                 class="form-control @error('res') is-invalid @enderror select2 wd-250"
                                 data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                 data-parsley-errors-container="#slErrorContainer">
                                 <option value="">اختر سبب الإلغاء</option>
                                 <option value="انتهاء الصلاحية">انتهاء الصلاحية</option>
                                 <option value="فقدان البطاقة">فقدان البطاقة</option>
                                 <option value="تلف البطاقة">تلف البطاقة</option>
                                 <option value="طلب العميل">طلب العميل</option>
                                 <option value="أخرى">أخرى</option>
                             </select>
                             @error('res')
                                 <span class="invalid-feedback" style="color: red" role="alert">
                                     {{ $message }}
                                 </span>
                             @enderror
                         </div>

                         <div class="form-group col-md-12" style="text-align: left;">
                             <button type="button" onclick="search()" class="btn btn-primary waves-effect waves-light">بحث</button>
                         </div>
                    </div>
                </form>
            </div>

            <div class="row small-spacing">
                <div class="col-md-12">
                    <div class="box-content">
                        <h4 class="box-title">عرض البطاقات</h4>
                        <a href="{{ route('report/cancelcards/pdf') }}" class="btn btn-danger" id="pdfLink" target="_blank" style="display:none;">
                            طباعة التقرير PDF
                        </a>
                        <div class="table-responsive" data-pattern="priority-columns" id="searchs"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for dropdowns with search functionality
            $('#companies_id, #offices_id, #company_users_id, #office_users_id, #res').select2({
                placeholder: "اختر ...",
                allowClear: true,
                language: "ar"
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Update PDF link with current filters
        function updatePdfLink() {
            const params = new URLSearchParams({
                companies_id: $('#companies_id').val(),
                offices_id: $('#offices_id').val(),
                company_users_id: $('#company_users_id').val(),
                office_users_id: $('#office_users_id').val(),
                request_number: $('#request_number').val(),
                card_number: $('#card_number').val(),
                fromdate: $('#fromdate').val(),
                todate: $('#todate').val(),
                res: $('#res').val()
            }).toString();

            $('#pdfLink').attr('href', '{{ route("report/cancelcards/pdf") }}' + '?' + params);
        }

        // Load offices when company is selected
        $('#companies_id').change(function() {
            var companyId = $(this).val();
            if (companyId) {
                $.ajax({
                    url: '../report/offices/' + companyId,
                    type: 'GET',
                    success: function(data) {
                        $('#offices_id').html('<option value="">اختر المكتب</option>');
                        $('#company_users_id').html('<option value="">اختر مستخدم الشركة</option>');
                        $('#office_users_id').html('<option value="">اختر مستخدم المكتب</option>');
                        data.forEach(function(office) {
                            $('#offices_id').append('<option value="' + office.id + '">' + office.name + '</option>');
                        });
                        // Load company users
                        $.ajax({
                            url: '../report/companyuser/' + companyId,
                            type: 'GET',
                            success: function(users) {
                                users.forEach(function(user) {
                                    $('#company_users_id').append('<option value="' + user.id + '">' + user.username + '</option>');
                                });
                            }
                        });
                    }
                });
            } else {
                $('#offices_id').html('<option value="">اختر المكتب</option>');
                $('#company_users_id').html('<option value="">اختر مستخدم الشركة</option>');
                $('#office_users_id').html('<option value="">اختر مستخدم المكتب</option>');
            }
        });

        // Load office users when office is selected
        $('#offices_id').change(function() {
            var officeId = $(this).val();
            if (officeId) {
                $.ajax({
                    url: '../report/officesuser/' + officeId,
                    type: 'GET',
                    success: function(users) {
                        $('#office_users_id').html('<option value="">اختر مستخدم المكتب</option>');
                        users.forEach(function(user) {
                            $('#office_users_id').append('<option value="' + user.id + '">' + user.username + '</option>');
                        });
                    }
                });
            } else {
                $('#office_users_id').html('<option value="">اختر مستخدم المكتب</option>');
            }
        });

        function search() {
            $('#loader-overlay').show();

            var companies_id = $('#companies_id').val();
            var offices_id = $('#offices_id').val();
            var company_users_id = $('#company_users_id').val();
            var office_users_id = $('#office_users_id').val();
            var request_number = $('#request_number').val();
            var card_number = $('#card_number').val();
            var fromdate = $('#fromdate').val();
            var todate = $('#todate').val();
            var res = $('#res').val();

            if (companies_id || offices_id || company_users_id || office_users_id || fromdate || todate || request_number || card_number || res) {
                $.ajax({
                    url: '../report/searchcacel',
                    type: 'GET',
                    data: {
                        companies_id: companies_id,
                        offices_id: offices_id,
                        company_users_id: company_users_id,
                        office_users_id: office_users_id,
                        request_number: request_number,
                        card_number: card_number,
                        fromdate: fromdate,
                        todate: todate,
                        res: res,
                    },
                    success: function(response) {
                        $('#loader-overlay').hide();

                        if (response.code == 1) {
                            // Show the PDF button and update its link
                            $('#pdfLink').show();
                            updatePdfLink();

                            $('#searchs').html(
                                `<div class="table-responsive" data-pattern="priority-columns">
                                    <table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom" style="cursor: pointer;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                               
                                                <th>رقم البطاقة</th>
                                                
                                                <th>الشركة</th>
                                                <th>المكتب</th>
                                                <th>مستخدم الشركة</th>
                                                <th>مستخدم المكتب</th>

                                                <th>حالة البطاقة</th>
                                                <th>رقم الطلب</th>
                                                <th>تاريخ اصدار البطاقة</th>
                                                <th>تاريخ الغاء البطاقة</th>
                                                <th>من قام بالإلغاء</th>
                                                <th>سبب الإلغاء</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rowsss"></tbody>
                                    </table>
                                </div>`
                            );

                            var x = 0;
                            response.data.forEach(item => {
                                let companies = item.companies ? item.companies.name : 'الإتحاد الليبي للتأمين';
                                let office = item.issuing && item.issuing.offices ? item.issuing.offices.name : '';
                                let companyUser = item.issuing && item.issuing.company_users ? item.issuing.company_users.username : '';
                                let officeUser = item.issuing && item.issuing.office_users ? item.issuing.office_users.username : '';

                                $('#rowsss').append(
                                    `<tr>
                                        <td>${++x}</td>

                                        <td>${item.card_number}</td>

                                        <td>${companies}</td>
                                        <td>${office}</td>
                                        <td>${companyUser}</td>
                                        <td>${officeUser}</td>
                                        <td>${item.cardstautes.name}</td>
                                        <td>${item.requests.request_number}</td>

                                        <td>${item.issuing ? item.issuing.issuing_date : ''}</td>
                                        <td>${item.card_delete_date}</td>
                                        <td>${item.cancel_by ?? '-'}</td>
                                        <td>${item.res ?? '-'}</td>
                                    </tr>`
                                );
                            });

                            $('#datatable1').dataTable({
                                language: { url: "{{ asset('Arabic.json') }}" },
                                lengthMenu: [10, 20, 30, 50],
                                bLengthChange: true,
                                serverSide: false,
                                paging: true,
                                searching: true,
                                ordering: true,
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                dom: 'Blfrtip',
                                buttons: [
                                    { extend: 'copyHtml5', exportOptions: { columns: [':visible'] }, text: 'نسخ' },
                                    { extend: 'excelHtml5', exportOptions: { columns: ':visible' }, text: 'excel تصدير كـ' }
                                ],
                            });
                        } else {
                            swal.fire("لايوجد بطاقات");
                            $('#searchs').html("");
                            $('#pdfLink').hide();
                        }
                    },
                    error: function() {
                        $('#loader-overlay').hide();
                        swal.fire("حدث خطأ أثناء البحث.");
                        $('#searchs').html("");
                        $('#pdfLink').hide();
                    }
                });
            } else {
                $('#loader-overlay').hide();
                swal.fire(" الرجاء قم باختيار خيار واحد علي الاقل ");
                $('#searchs').html("");
                $('#pdfLink').hide();
            }
        }
    </script>
@endsection
