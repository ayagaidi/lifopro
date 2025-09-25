@extends('comapny.app')
@section('title', 'كافة البطاقة')

@section('content')
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    
     $(document).ready(function() {
       
     
          
          
           
             $('#offices_id').select2({
        placeholder: "اختر المكتب ...",
        allowClear: true,
        language: "ar"
      }); 
          
      
      
           
     }); 
        
    </script>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('company/card/search') }}">البطاقات</a>/ بحث بواسطة </h4>

            </div>
        </div>
        <div class="box-content">

            <form method="POST" enctype="multipart/form-data" action="">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="offices_id" class="control-label">المكتب</label>
                        <select name="offices_id" class="form-control @error('offices_id') is-invalid @enderror"
                            id="offices_id">
                            <option value="">اختر المكتب</option>
                            @forelse ($Offices as $Office)
                                <option value="{{ $Office->id }}">{{ $Office->name }}</option>
                            @empty
                                <option value="">لاتوجد مكاتب</option>
                            @endforelse
                        </select>
                        @error('offices_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group  col-md-3">
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
                    <div class="form-group  col-md-3">
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

                </div>
                <div class="form-group col-md-3">
                    <label>تاريخ </label>
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
                <div class="form-group  col-md-12" style="text-align: left;">
                    <button type="button" onclick="search()" class="btn btn-primary waves-effect waves-light">بحث</button>

                </div>
            </form>
        </div>
        <div class="row small-spacing">
            <div class="col-md-12">
                <div class="box-content ">
                    <h4 class="box-title">عرض البطاقات</h4>
                                          <div class="mb-2 text-right" id="printButtonWrapper" >
    <a id="printButton" href="#" target="_blank" class="btn btn-info btn-sm">
        <i class="fa fa-print"></i> طباعة النتائج PDF
    </a>
</div>
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

        // القيم المدخلة من النموذج
        const formData = {
            cardstautes_id: $('#cardstautes_id').val(),
            request_number: $('#request_number').val(),
            card_number: $('#card_number').val(),
            offices_id: $('#offices_id').val(),
            fromdate: $('#fromdate').val(),
            todate: $('#todate').val(),
        };

        // تحقق أن المستخدم أدخل على الأقل خيارًا واحدًا
        if (!hasAnyValue(formData)) {
            
            $('#loader-overlay').hide();
            Swal.fire("⚠️", "الرجاء قم باختيار خيار واحد على الأقل", "warning");
            $('#searchs').html("");
            return;
        }
        
        // ✅ تحقق: إذا تم إدخال رقم الطلب أو التاريخ، يجب تحديد حالة البطاقة
if (request_number !== "") {
    if (cardstautes_id === "") {
        Swal.fire("⚠️", "يرجى تحديد حالة البطاقة عند إدخال رقم الطلب.", "warning");
        return;
    }

    if (fromdate === "" || todate === "") {
        Swal.fire("⚠️", "يرجى تحديد الفترة الزمنية (من - إلى) عند إدخال رقم الطلب.", "warning");
        return;
    }

    const from = new Date(fromdate);
    const to = new Date(todate);
    const monthsDiff = (to.getFullYear() - from.getFullYear()) * 12 + (to.getMonth() - from.getMonth());

    if (to < from) {
        Swal.fire("⚠️", "تاريخ 'إلى' يجب أن يكون بعد تاريخ 'من'.", "warning");
        return;
    }

    if (monthsDiff > 3 || (monthsDiff === 3 && to.getDate() > from.getDate())) {
        Swal.fire("⚠️", "الرجاء تحديد فترة لا تتجاوز 3 أشهر بين التاريخين.", "warning");
        return;
    }
}


        // تحقق من إدخال التواريخ مع الحالة
        // if (formData.fromdate && formData.todate) {
        //     if (!formData.cardstautes_id) {
        //         $('#loader-overlay').hide();
        //         Swal.fire("⚠️", "يرجى اختيار حالة البطاقة عند استخدام التاريخ", "warning");
        //         return;
        //     }

        //     if (!validateDateRange(formData.fromdate, formData.todate)) {
        //         $('#loader-overlay').hide();
        //         return;
        //     }
        // }
          if (formData.fromdate && formData.todate) {
        if (!validateDateRange(formData.fromdate, formData.todate)) {
            $('#loader-overlay').hide();
            return;
        }
    }

        // تنفيذ الطلب
        $.ajax({
            url: '../../company/card/searchby',
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#loader-overlay').hide();

                if (response.code == 1) {
                    
                    renderTable(response.data);
                    // إعداد رابط الطباعة
const queryParams = new URLSearchParams({
    cardstautes_id: formData.cardstautes_id,
    request_number: formData.request_number,
    card_number: formData.card_number,
    offices_id: formData.offices_id,
    fromdate: formData.fromdate,
    todate: formData.todate
}).toString();
const printUrl = `../../company/card/searchby/pdf?${queryParams}`;
$('#printButton').attr('href', printUrl);
$('#printButtonWrapper').show();
                } else {
                    Swal.fire("📭", "لا توجد بطاقات مطابقة", "info");
                    $('#searchs').html('');
                    $('#printButtonWrapper').hide();
                }
            },
            error: function() {
                $('#loader-overlay').hide();
                Swal.fire("🚫", "حدث خطأ أثناء عملية البحث", "error")
                $('#printButtonWrapper').hide();;
            }
        });
    }

    function hasAnyValue(data) {
        return Object.values(data).some(val => val && val.trim() !== '');
    }

    function validateDateRange(from, to) {
        const fromDate = new Date(from);
        const toDate = new Date(to);

        if (toDate < fromDate) {
            Swal.fire("⚠️", "تاريخ 'إلى' يجب أن يكون بعد تاريخ 'من'", "warning");
            return false;
        }

        const monthsDiff = (toDate.getFullYear() - fromDate.getFullYear()) * 12 + (toDate.getMonth() - fromDate.getMonth());

        if (monthsDiff > 3 || (monthsDiff === 3 && toDate.getDate() > fromDate.getDate())) {
            Swal.fire("⚠️", "الرجاء تحديد فترة لا تتجاوز 3 أشهر بين التاريخين", "warning");
            return false;
        }

        return true;
    }

    function renderTable(data) {
        let rows = '';

        data.forEach(card => {
            const office = card.offices?.name ?? 'الرئيسي';
            const status = card.cardstautes?.name ?? '-';
            const reqNum = card.requests?.request_number ?? '-';
            let displayDate = '-';

   switch (parseInt(card.cardstautes_id)) {
            case 0:
                displayDate = card.created_at ?? '-';
                break;
            case 1:
                displayDate = card.requests?.uploded_datetime ?? '-';
                break;
            case 2:
                displayDate = card.issuing?.created_at ?? '-';
                break;
            case 3:
                displayDate = card.card_delete_date ?? '-';
                break;
            default:
                displayDate = card.created_at ?? '-';
                break;
}


            rows += `
                <tr>
                    <td>${card.card_number}</td>
                    <td>${status}</td>
                    <td>${reqNum}</td>
                    <td>${displayDate}</td>
                </tr>`;
        });

        $('#searchs').html(`
            <table id="datatable1" class="table table-bordered table-hover dataTable">
                <thead>
                    <tr>
                        <th>رقم البطاقة</th>
                        <th>حالة البطاقة</th>
                        <th>رقم الطلب</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        `);

        $('#datatable1').dataTable({
            language: {
                url: "{{ asset('Arabic.json') }}"
            },
            lengthMenu: [10, 20, 30, 50],
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
                    text: 'تصدير Excel'
                }
            ]
        });
    }
</script>

@endsection
