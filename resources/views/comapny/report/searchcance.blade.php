@extends('comapny.app')
@section('title', 'تقرير البطاقات الملغية ')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title"><a href="{{ route('company/report/cancelcards') }}">إدارة التقارير</a>/ تقارير البطاقات الملغية</h4>
        </div>

        <div class="box-content">
            <form method="POST" enctype="multipart/form-data" action="">
                @csrf
                <div class="row">
                 

                    <div class="form-group col-md-3">
                        <label for="offices_id" class="control-label">المكتب</label>
                        <select name="offices_id" id="offices_id"
                            class="form-control @error('offices_id') is-invalid @enderror select2 wd-250"
                            data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                            data-parsley-errors-container="#slErrorContainer">
                            <option value="">اختر المكتب</option>
                            @foreach ($offices as $office)
                            <option value="{{ $office->id }}"
                                {{ old('offices_id') == $office->id ? 'selected' : ''   }}>
                                {{ $office->name }}</option>  
                                @endforeach                                  
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
                            <option value="">اختر مستخدم الشركة</option>
                            @foreach ($companyUsers as $user)   
                            <option value="{{ $user->id }}"
                                {{ old('company_users_id') == $user->id ? 'selected' : ''   }}>
                                {{ $user->username }}</option>  
                                @endforeach

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
                            <option value="">اختر مستخدم المكتب</option>
                        </select>
                        @error('office_users_id')
                        <span class="invalid-feedback" style="color: red" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label">رقم الطلب</label>
                        <input type="text" name="request_number" id="request_number"
                            class="form-control @error('request_number') is-invalid @enderror"
                            value="{{ old('request_number') }}" placeholder="رقم الطلب">
                        @error('request_number')
                        <span class="invalid-feedback" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label">رقم البطاقة</label>
                        <input type="text" name="card_number" id="card_number"
                            class="form-control @error('card_number') is-invalid @enderror"
                            value="{{ old('card_number') }}" placeholder="رقم البطاقة">
                        @error('card_number')
                        <span class="invalid-feedback" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label>تاريخ الإلغاء - من</label>
                        <input type="date" name="fromdate" id="fromdate"
                            class="form-control @error('fromdate') is-invalid @enderror">
                        @error('fromdate')
                        <span class="invalid-feedback" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label>إلى</label>
                        <input type="date" name="todate" id="todate"
                            class="form-control @error('todate') is-invalid @enderror">
                        @error('todate')
                        <span class="invalid-feedback" style="color: red">{{ $message }}</span>
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
                    <a href="#" target="_blank" class="btn btn-primary" id="printPdfBtn" style="display:none;">طباعة PDF</a>
                    <div class="table-responsive" id="searchs"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
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

        let request_number = $('#request_number').val();
        let card_number = $('#card_number').val();
        let offices_id = $('#offices_id').val();
        let company_users_id = $('#company_users_id').val();
        let office_users_id = $('#office_users_id').val();
        let fromdate = $('#fromdate').val();
        let todate = $('#todate').val();

        if (request_number || card_number || offices_id || company_users_id || office_users_id || fromdate || todate) {
            $.ajax({
                url: '../report/searchcacel',
                type: 'GET',
                data: {
                    request_number,
                    card_number,
                    offices_id,
                    company_users_id,
                    fromdate,
                    todate
                },
                success: function(response) {
                    $('#loader-overlay').hide();

                    if (response.code === 1) {
                        let table = `
                        <table id="datatable1" class="table table-bordered table-hover">
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
                                    <th>تاريخ إصدار البطاقة</th>
                                    <th>تاريخ إلغاء البطاقة</th>
                                    <th>سبب الإلغاء</th>
                                    <th>من قام بالإلغاء</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        response.data.forEach((card, index) => {
                            let company = card.companies?.name ?? 'الإتحاد الليبي للتأمين';
                            let office = card.issuing?.offices?.name ?? '-';
                            let companyUser = card.issuing?.company_users?.username ?? '-';
                            let issuingUser = card.issuing?.office_users?.username ?? card.issuing?.company_users?.username ?? '-';
                            table += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${card.card_number}</td>
                                <td>${company}</td>
                                <td>${office}</td>
                                <td>${issuingUser}</td>
                                <td>${card.issuing?.office_users?.username ?? '-'}</td>
                                <td>${card.cardstautes?.name ?? '-'}</td>
                                <td>${card.requests?.request_number ?? '-'}</td>
                                <td>${card.issuing?.issuing_date ?? '-'}</td>
                                <td>${card.card_delete_date ?? '-'}</td>
                                <td>${card.res ?? '-'}</td>
                                <td>${card.cancel_by ?? '-'}</td>
                            </tr>`;
                        });

                        table += '</tbody></table>';
                        $('#searchs').html(table);

                        $('#datatable1').DataTable({
                            language: { url: "{{ asset('Arabic.json') }}" },
                            lengthMenu: [10, 20, 30, 50],
                            dom: 'Blfrtip',
                            buttons: [
                                { extend: 'copyHtml5', text: 'نسخ', exportOptions: { columns: [':visible'] } },
                                { extend: 'excelHtml5', text: 'تصدير Excel', exportOptions: { columns: ':visible' } }
                            ]
                        });

                        // تحديث رابط PDF
                        const params = {};
                        if (request_number) params.request_number = request_number;
                        if (card_number) params.card_number = card_number;
                        if (offices_id) params.offices_id = offices_id;
                        if (company_users_id) params.company_users_id = company_users_id;
                        if (office_users_id) params.office_users_id = office_users_id;
                        if (fromdate) params.fromdate = fromdate;
                        if (todate) params.todate = todate;
                        const queryParams = new URLSearchParams(params).toString();

                        const printUrl = `{{ route('company/report/cancelcardspdf') }}?${queryParams}`;
                        $('#printPdfBtn').attr('href', printUrl).show();

                    } else {
                        Swal.fire("لا توجد بطاقات");
                        $('#searchs').html('');
                        $('#printPdfBtn').hide();
                    }
                },
                error: function(xhr) {
                    $('#loader-overlay').hide();
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON.message,
                        });
                    } else {
                        Swal.fire("حدث خطأ أثناء البحث");
                    }
                    $('#searchs').html('');
                    $('#printPdfBtn').hide();
                }
            });
        } else {
            $('#loader-overlay').hide();
            Swal.fire("يرجى إدخال خيار واحد على الأقل للبحث");
            $('#searchs').html('');
            $('#printPdfBtn').hide();
        }
    }
</script>
@endsection
