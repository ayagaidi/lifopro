@extends('layouts.app')
@section('title', 'كافة البطاقة')

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

        $('#cardstautes_id').select2({
            placeholder: "اختر الحالة ...",
            allowClear: true,
            language: "ar"
        });

    });
</script>
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">

            <h4 class="box-title"><a href="{{ route('card/search') }}">البطاقات</a>/ بحث بواسطة </h4>

        </div>
    </div>
    <div class="box-content">

        <form method="POST" enctype="multipart/form-data" action="">
            @csrf
            <div class="row">
                <div class="form-group  col-md-3">
                    <label for="inputName" class="control-label">الشركة</label>
                    <select name="companies_id" id="companies_id"
                        class="form-control @error('companies_id') is-invalid @enderror  select2  wd-250"
                        data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                        data-parsley-errors-container="#slErrorContainer" required>
                        <option value="">اختر الشركة </option>
                        <option value="0">الإتحاد الليبي للتأمين </option>

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
                <label>تاريخ الادراج</label>
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
                <div class="mb-2" id="printButtonWrapper" style="display: none; text-align: right;">
                    <a id="printButton" href="#" target="_blank" class="btn btn-round">طباعة النتائج PDF</a>
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

    function hasSelectedFilter(companiesId, cardstautesId, requestNumber, cardNumber, fromDate, toDate) {
        // Implement logic to check if at least one filter has a value
        return (companiesId !== "" || cardstautesId !== "" || requestNumber !== "" || cardNumber !== "" || fromDate !== "" || toDate !== "");
    }


    function search() {
        var companies_id = document.getElementById('companies_id').value;
        var cardstautes_id = document.getElementById('cardstautes_id').value;
        var request_number = document.getElementById('request_number').value;
        var card_number = document.getElementById('card_number').value;
        var fromdate = document.getElementById('fromdate').value;
        var todate = document.getElementById('todate').value;


  // ✅ التحقق الأساسي: إذا تم تحديد حالة البطاقة
    if (cardstautes_id !== "") {
        const hasDates = fromdate !== "" && todate !== "";
        const hasRequestNumber = request_number !== "";

        if (!hasDates && !hasRequestNumber) {
            Swal.fire("⚠️", "عند اختيار حالة البطاقة، يجب إدخال رقم الطلب أو تحديد الفترة الزمنية (من - إلى).", "warning");
            return;
        }

        if (hasDates) {
            const from = new Date(fromdate);
            const to = new Date(todate);
            const monthsDiff = (to.getFullYear() - from.getFullYear()) * 12 + (to.getMonth() - from.getMonth());

            if (fromdate === "" || todate === "") {
                Swal.fire("⚠️", "يرجى تحديد كل من التاريخ (من) و (إلى).", "warning");
                return;
            }

            if (to < from) {
                Swal.fire("⚠️", "تاريخ 'إلى' يجب أن يكون بعد تاريخ 'من'.", "warning");
                return;
            }

            if (monthsDiff > 3 || (monthsDiff === 3 && to.getDate() > from.getDate())) {
                Swal.fire("⚠️", "الرجاء تحديد فترة لا تتجاوز 3 أشهر بين التاريخين.", "warning");
                return;
            }
        }
    }

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


        // التحقق من فرق التاريخ إذا تم تعبئتهما
        // if (fromdate !== "" && todate !== "") {
        //     var from = new Date(fromdate);
        //     var to = new Date(todate);

        //     var monthsDiff = (to.getFullYear() - from.getFullYear()) * 12 + (to.getMonth() - from.getMonth());

        //     if (monthsDiff > 3 || (monthsDiff === 3 && to.getDate() > from.getDate())) {
        //         Swal.fire("⚠️", "الرجاء تحديد فترة لا تتجاوز 3 أشهر بين التاريخين.", "warning");
        //         return;
        //     }

        //     if (to < from) {
        //         Swal.fire("⚠️", "تاريخ 'إلى' يجب أن يكون بعد تاريخ 'من'.", "warning");
        //         return;
        //     }
        // }

        // // تحقق من التاريخ المطلوب لكل حالة
        // if ((cardstautes_id === "2" || cardstautes_id === "3" || cardstautes_id === "1" || cardstautes_id === "0") &&
        //     (fromdate === "" || todate === "")) {
        //     let dateField = '';
        //     if (cardstautes_id === "2") dateField = "تاريخ الإصدار";
        //     else if (cardstautes_id === "3") dateField = "تاريخ الإلغاء";
        //     else if (cardstautes_id === "1") dateField = "تاريخ الإدراج";
        //     else if (cardstautes_id === "0") dateField = "تاريخ الإنشاء";

        //     Swal.fire("⚠️", "يرجى تحديد " + dateField + " (من - إلى)", "warning");
        //     return;
        // }

       
        $('#loader-overlay').show();

        if (hasSelectedFilter(companies_id, cardstautes_id, request_number, card_number, fromdate, todate)) {
            $.ajax({
                url: '../card/searchby',
                type: 'GET',
                data: {
                    companies_id: companies_id,
                    cardstautes_id: cardstautes_id,
                    request_number: request_number,
                    card_number: card_number,
                    fromdate: fromdate,
                    todate: todate
                },
                success: function(response) {
                    $('#loader-overlay').hide();

                    if (response.code == 1) {
                        let cards = response.data;
                        let tableRows = "";
                        let x = 0;

                        cards.forEach(function(card) {
                            let company = card.companies ? card.companies.name : 'الإتحاد الليبي للتأمين';
                            let status = card.cardstautes ? card.cardstautes.name : '-';
                            let reqNum = card.requests ? card.requests.request_number : '-';

                            let displayDate = '-';
                            switch (card.cardstautes_id) {
                                case 0:
                                    displayDate = card.created_at ?? '-';
                                    break;
                                case 1:
                                    displayDate = card.requests.uploded_datetime ?? '-';
                                    break;
                                case 2:
                                    displayDate = card.issuing_date ?? '-';
                                    break;
                                case 3:
                                    displayDate = card.card_delete_date ?? '-';
                                    break;
                            }

                            tableRows += `
                            <tr>
                                <td>${++x}</td>
                                <td>${card.card_number ?? '-'}</td>
                                <td>${company}</td>
                                <td>${status}</td>
                                <td>${reqNum}</td>
                                      <td>${displayDate}</td>
                            </tr>`;
                        });

                        $('#searchs').html(`
                        <div class="table-responsive">
                            <table id="datatable1" class="table table-bordered table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رقم البطاقة</th>
                                        <th>الشركة</th>
                                        <th>الحالة</th>
                                        <th>رقم الطلب</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>${tableRows}</tbody>
                            </table>
                        </div>`);

                        let printUrl = `../card/searchby/pdf?companies_id=${companies_id}&cardstautes_id=${cardstautes_id}&request_number=${request_number}&card_number=${card_number}&fromdate=${fromdate}&todate=${todate}`;
                        $('#printButton').attr('href', printUrl);
                        $('#printButtonWrapper').show();

                        $('#datatable1').DataTable({
                            language: {
                                url: "{{ asset('Arabic.json') }}"
                            },
                            dom: 'Blfrtip',
                            buttons: [{
                                    extend: 'copyHtml5',
                                    text: 'نسخ'
                                },
                                {
                                    extend: 'excelHtml5',
                                    text: 'Excel'
                                }
                            ]
                        });

                    } else {
                        Swal.fire("🔍", "لا توجد بيانات مطابقة للبحث.", "info");
                        $('#searchs').html('');
                        $('#printButtonWrapper').hide();
                    }
                },
                error: function() {
                    $('#loader-overlay').hide();
                    Swal.fire("حدث خطأ", "تعذر تنفيذ عملية البحث. يرجى المحاولة لاحقاً.", "error");
                    $('#searchs').html('');
                    $('#printButtonWrapper').hide();
                }
            });
        } else {
            $('#loader-overlay').hide();
            $('#printButtonWrapper').hide();
            Swal.fire("⚠️", "الرجاء اختيار خيار واحد على الأقل", "warning");
            $('#searchs').html("");
        }
    }
</script>
@endsection