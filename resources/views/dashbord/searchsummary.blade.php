@extends('layouts.app')
@section('title', 'تقارير المبيعات [مختصر]')

@section('content')




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

    </div>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('select[name="companies_id"]').on('change', function() {
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
                $('#loader-overlay').show();

                var office_users_id;

                var companies_id
                companies_id = $('#companies_id').val();

                office_users_id = $('#office_users_id').val();
                var offices_id;
                offices_id = $('#offices_id').val();

                var company_users_id;

                company_users_id = $('#company_users_id').val();
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
                } else {
                    if (((companies_id || offices_id || office_users_id || card_number || insurance_name || plate_number ||
                            chassis_number || company_users_id) == 1) || (fromdate && todate)) {
                           $.ajax({
                        url: '../../report/issuing/searchby',
                        type: 'Get',
                        data: {
                            offices_id: $('#offices_id').val(),
                            companies_id: $('#companies_id').val(),
                            office_users_id: $('#office_users_id').val(),
                            insurance_name: $('#insurance_name').val(),
                            card_number: $('#card_number').val(),
                            chassis_number: $('#chassis_number').val(),
                            plate_number: $('#plate_number').val(),
                            company_users_id: $('#company_users_id').val(),
                            fromdate: $('#fromdate').val(),
                            todate: $('#todate').val(),
                        },
                        success: function(response) {
                            // console.log(response);
                            if (response.code == 1) {
                                let totalInstallment = 0,
                        totalTax         = 0,
                        totalStamp       = 0,
                        totalSupervision = 0,
                        totalVersion     = 0,
                        totalInsurance   = 0;

                                $('#loader-overlay').hide();

                                // Calculate total insurance
                                response.data.forEach(item => {
                                    totalInsurance += parseFloat(item.insurance_total) || 0;
                                });
//                                 $('#searchs').html(

//                                     '<table id="datatable4" class="table table-bordered table-hover js-basic-example dataTable table-custom "                    style="cursor: pointer;">' +

//                                     '<thead>' +
//                                     '<tr>' +
//                                     ' <th>' + "#" + ' </th>' +
//                                     ' <th>' + "رقم البطاقة" + ' </th>' +
//                                     ' <th>' + "المُصدر" + ' </th>' +
//                                     ' <th>' + "   الشركة   " + ' </th>' +
//                                     ' <th>' + "   المكتب   " + ' </th>' +
//                                     ' <th>' + "   المؤمن له    " + ' </th>' +
//                                     ' <th>' + "   تاريخ الاصدار " + ' </th>' +
//                                     ' <th>' + " صافي القسط   " + ' </th>' +
//                                     ' <th>' + " الضريبة    " + ' </th>' +
//                                     ' <th>' + " رسم الدمغة    " + ' </th>' +
//                                     ' <th>' + "  الإشراف     " + ' </th>' +
//                                     ' <th>' + "  الإصدار    " + ' </th>' +
//                                     ' <th>' + " الاجمالي    " + ' </th>' +
//                                     ' <th class="view">' + "     عرض الوثيقة     " + ' </th>' +
//                                     '</tr>' +
//                                     '</thead>' +
//                                     '<tbody id="rowsss">' +
//                                     '</tbody>' +
                                   
//                                     '<tfoot id="table-footer"></tfoot>' +
//                                     '</table>' +
//   '</div>' +
//                                         '</div>' +
//                                         '</div>'
//                                   );
                                    $('#searchs').html(

                                        '<table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom "                    style="cursor: pointer;">' +

                                        '<thead>' +

                                        ' <th>' + "#" + ' </th>' +
                                        ' <th>' + "رقم البطاقة" + ' </th>' +

                                        ' <th>' + "المُصدر" + ' </th>' +
                                        ' <th>' + "   الشركة   " + ' </th>' +
                                        ' <th>' + "   المكتب   " + ' </th>' +
                                        ' <th>' + "   المؤمن له    " + ' </th>' +
                                        ' <th>' + "   تاريخ الاصدار " + ' </th>' +
                                        ' <th>' + " صافي القسط   " + ' </th>' +
                                        ' <th>' + " الضريبة    " + ' </th>' +
                                        ' <th>' + " رسم الدمغة    " + ' </th>' +
                                        ' <th>' + "  الإشراف     " + ' </th>' +
                                        ' <th>' + "  الإصدار    " + ' </th>' +
                                        ' <th>' + " الاجمالي    " + ' </th>' +
                                        
                                        ' <th class="view">' + "     عرض الوثيقة     " + ' </th>' +

                                        '</tr>' +
                                        '</thead>' +
                                        '<tbody id="rowsss">' +

                                        '</tbody>' +
                                                                            '<tfoot id="table-footer"></tfoot>' +


                                        '</table>' +

                                        '</div>' +
                                        '</div>' +
                                        '</div>');


                                x = 0;
                                $('#footer').append('<p>' + totalInsurance.toFixed(3) + ' د.ل</p>');

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
                                        "{{ route('viewdocument', ['cardnumber' => 'PLACEHOLDER']) }}";


                                    var url = baseUrl.replace('PLACEHOLDER', encodeURIComponent(cardNumber));
                                    var v =
                                        '<a style="color: #f97424;" target="_blank" href="' + url + '">' +
                                        '<img src="{{ asset('contract.png') }}" style="width: 50%;">' +
                                        '</a>';


            


                                    if (response['data'][i].cards) {
                                        var card = response['data'][i].cards.card_number;
                                    } else {
                                        var card = response['data'][i].id;
                                    }


                                    $('#rowsss').append(
                                        '<tr>' +
                                        '<td>' + (x = x + 1) + '</td>' +
                                        '<td>' + card + '</td>' +

                                        '<td>' + user + '</td>' +
                                        '<td>' + companies + '</td>' +
                                        '<td>' + offices + '</td>' +
                                        '<td>' + response['data'][i].insurance_name + '</td>' +

                                        '<td>' + response['data'][i].issuing_date + '</td>' +
                                        '<td>' + response['data'][i].insurance_installment + '</td>' +
                                        '<td>' + response['data'][i].insurance_tax + '</td>' +
                                        '<td>' + response['data'][i].insurance_stamp + '</td>' +
                                        '<td>' + response['data'][i].insurance_supervision + '</td>' +
                                        '<td>' + response['data'][i].insurance_version + '</td>' +
                                        '<td>' + response['data'][i].insurance_total + '</td>' +

                                        '<td class="view">' +
                                        v + '</td>'+
                                         '</tr>' 

                                    );

                                    var footerHtml = '<tr>' +
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


                                }





                                // Ensure any previously initialized DataTable is destroyed
if ($.fn.DataTable.isDataTable('#datatable1')) {
    $('#datatable1').DataTable().destroy();
}

// Initialize a new DataTable instance using the newer API
$('#datatable1').DataTable({
    "language": { "url": "{{ asset('Arabic.json') }}" },
    "lengthMenu": [10, 20, 30, 50],
    "bLengthChange": true,
    serverSide: false,
    paging: true,
    searching: true,
    ordering: true,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
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
            title: 'تقرير المبيعات من ' + fromdate + ' إلى ' + todate
        },
   
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


                    swal.fire(" الرجاء قم باختيار خيار واحد علي الاقل   ");
                    $('#searchs').html("");
                    $('#loader-overlay').hide();

                }
            }}
        </script>



    @endsection
