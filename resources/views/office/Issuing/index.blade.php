@extends('office.app')
@section('title', 'اصدار وثيقة')

@section('content')
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

   <script>
   
    $(document).ready(function() {
              $('#cars_id').select2({
        placeholder: "اختر السيارة ...",
        allowClear: true,
        language: "ar"
      });
            
                    $('#car_made_date').select2({
        placeholder: " اختر السنة...",
        allowClear: true,
        language: "ar"
      });
    });
function disableEnterKey(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
  }
}
     
function disableEnterKey(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
  }
}

document.addEventListener('keydown', disableEnterKey);
    function calculateInsurance() {

        var insurance_clauses_id = $('#insurance_clauses_id').val();
        var insurance_days_number = parseInt($('#insurance_days_number').val()) || 0;
        var countryCount = parseInt($('#insurance_country_number').val()) || 1;
        var countries_id = $('#countries_id').val();
        $('#loader-overlay').show();
        $.ajax({
            url: '../office/issuing/tax',
            type: 'GET',
            success: function(response) {
                if (!response) {
                    console.error('لم يتم العثور على البيانات المطلوبة.');
                    return;

                }

                var dailyRate = (insurance_clauses_id === "PV") ?
                    (countryCount == 2 ? 10 : countries_id == 4 ? 3 : response
                        .installment_daily_1) :
                    (countryCount == 2 ? 11 : countries_id == 4 ? 3 : response
                        .installment_daily_2);

                $("#insurance_installment_daily").val(dailyRate);

                var netInstallment = dailyRate * insurance_days_number;
                $("#insurance_installment").val(netInstallment.toFixed(2));

                // حساب الضريبة مع التحقق من الجزء العشري
                var nettaxx = netInstallment * (1 / 100);
                var integerPart = Math.floor(nettaxx); // الجزء الصحيح
                var decimalPart = nettaxx - integerPart; // الجزء العشري

                
if (decimalPart == 0) {
    // لا تغيير
    nettaxx = integerPart;
}else if (decimalPart < 0.5) {
                        // إذا كان الجزء العشري أقل من 0.5، نجعلها xx.5
                        nettaxx = integerPart + 0.5;
                    }else if (decimalPart == 0.5) {
                        nettaxx = nettaxx;
                    }
                    
                    else {
                        // إذا كان الجزء العشري 0.5 أو أكثر، نقربها للعدد الصحيح التالي
                        nettaxx = Math.ceil(nettaxx);
                    }

                $("#insurance_tax").val(nettaxx.toFixed(2));

                var netsupervision = (netInstallment * 0.005).toFixed(3);
                $("#insurance_supervision").val(netsupervision);

                // حساب المجموع النهائي
                var insurance_total =
                    parseFloat($('#insurance_installment').val()) +
                    parseFloat($('#insurance_supervision').val()) +
                    parseFloat($('#insurance_tax').val()) +
                    parseFloat($('#insurance_version').val()) +
                    parseFloat($('#insurance_stamp').val());

                $("#insurance_total").val(insurance_total.toFixed(3));
                $('#loader-overlay').hide();
            },
            error: function(xhr, status, error) {
                console.error('خطأ في جلب بيانات الضريبة:', error);
                $('#loader-overlay').hide();
            }
        });
    }

    function visitedcountry(countryId) {

        const minDays = ['1', '2', '3'].includes(countryId) ? 7 : 15;
        $('#insurance_days_number').attr('min', minDays).val(Math.max($('#insurance_days_number').val(), minDays));

        fetch(`../office/issuing/country/${countryId}`)
            .then(response => response.json())
            .then(data => {
                if (!data) throw new Error("لم يتم العثور على بيانات الدولة");
                const countryCount = data.name.split(" ").length;
                $("#insurance_country_number").val(countryCount);
                calculateInsurance();
            })
            .catch(error => console.error('خطأ في جلب بيانات الدولة:', error));
    }
    $(document).ready(function() {
        $('button[type="submit"]').prop('disabled', true);
        updateInsuranceEndDate();
        // السماح فقط بالأحرف والأرقام في حقول رقم المحرك ورقم الشاسيه
        $('#motor_number, #chassis_number').on('keypress', function(event) {
            var keycode = event.which || event.keyCode;
            var regex = /^[a-zA-Z0-9]$/; // يسمح فقط بالأحرف والأرقام
            if (!regex.test(String.fromCharCode(keycode))) {
                return false;
            }
        });

        // Auto-fill form when plate_number or chassis_number changes
        $('#plate_number, #chassis_number').on('blur', function() {
            var plateNumber = $('#plate_number').val().trim();
            var chassisNumber = $('#chassis_number').val().trim();

            if (plateNumber || chassisNumber) {
                $.ajax({
                    url: '../office/issuing/search-vehicle',
                    type: 'GET',
                    data: {
                        plate_number: plateNumber,
                        chassis_number: chassisNumber
                    },
                    success: function(response) {
                        if (response.found) {
                            // Auto-fill the form fields
                            $('#insurance_name').val(response.data.insurance_name);
                            $('#insurance_location').val(response.data.insurance_location);
                            $('#insurance_phone').val(response.data.insurance_phone);
                            $('#motor_number').val(response.data.motor_number);
                            $('#chassis_number').val(response.data.chassis_number);
                            $('#plate_number').val(response.data.plate_number);
                            $('#car_made_date').val(response.data.car_made_date);
                            $('#cars_id').val(response.data.cars_id).trigger('change');
                            $('#vehicle_nationalities_id').val(response.data.vehicle_nationalities_id).trigger('change');
                            $('#countries_id').val(response.data.countries_id).trigger('change');
                            $('#insurance_clauses_id').val(response.data.insurance_clauses_id).trigger('change');

                            // Trigger calculations
                            calculateInsurance();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error searching vehicle:', error);
                    }
                });
            }
        });

        // منع اختيار تواريخ سابقة لليوم الحالي في التأمين
      //  const today = new Date().toISOString().split('T')[0];
    //    $('#insurance_day_from').attr('min', today).val(today);

        // حساب تاريخ انتهاء التأمين
        function updateInsuranceEndDate() {
            var startDate = new Date($('#insurance_day_from').val());
            var daysToAdd = parseInt($('#insurance_days_number').val()) || 0;

            // Add hours equivalent to days (days * 24 hours)
            startDate.setTime(startDate.getTime() + (daysToAdd * 24 * 60 * 60 * 1000));

            // Format the date as YYYY-MM-DD
            var formattedDate = startDate.toISOString().split('T')[0];

            $('#insurance_day_to').val(formattedDate);
        }

        $('#insurance_day_from, #insurance_days_number').on('change', updateInsuranceEndDate);

        // التأكد من أن عدد الأيام لا يتجاوز 90 يومًا
        $('#insurance_days_number').on('blur', function() {
            var inputValue = parseInt($(this).val()) || 0;
            if (inputValue > 90) {
                Swal.fire("أقصى مدة مسموح بها هي 90 يوم");
                $(this).val('');
            } else if (inputValue < 7) {
                Swal.fire("يجب أن يكون الحد الأدنى 7 أيام");
                $(this).val('');
            }
            updateInsuranceEndDate();
        });

        // تحديث الحسابات عند تغيير أي من القيم
        $('#insurance_clauses_id, #insurance_days_number,#insurance_day_from').on('change', function() {
            calculateInsurance();
        });

        function calculateInsurance() {

            var insurance_clauses_id = $('#insurance_clauses_id').val();
            var insurance_days_number = parseInt($('#insurance_days_number').val()) || 0;
            var countryCount = parseInt($('#insurance_country_number').val()) || 1;
            var countries_id = $('#countries_id').val();
            $('#loader-overlay').show();
            $.ajax({
                url: '../office/issuing/tax',
                type: 'GET',
                success: function(response) {
                    if (!response) {
                        console.error('لم يتم العثور على البيانات المطلوبة.');
                        return;

                    }

                    var dailyRate = (insurance_clauses_id === "PV") ?
                        (countryCount == 2 ? 10 : countries_id == 4 ? 3 : response
                            .installment_daily_1) :
                        (countryCount == 2 ? 11 : countries_id == 4 ? 3 : response
                            .installment_daily_2);

                    $("#insurance_installment_daily").val(dailyRate);

                    var netInstallment = dailyRate * insurance_days_number;
                    $("#insurance_installment").val(netInstallment.toFixed(2));

                    // حساب الضريبة مع التحقق من الجزء العشري
                    var nettaxx = netInstallment * (1 / 100);
                    var integerPart = Math.floor(nettaxx); // الجزء الصحيح
                    var decimalPart = nettaxx - integerPart; // الجزء العشري

                   
if (decimalPart == 0) {
    // لا تغيير
    nettaxx = integerPart;
}else if (decimalPart < 0.5) {
                        // إذا كان الجزء العشري أقل من 0.5، نجعلها xx.5
                        nettaxx = integerPart + 0.5;
                    }else if (decimalPart == 0.5) {
                        nettaxx = nettaxx;
                    }
                    
                    else {
                        // إذا كان الجزء العشري 0.5 أو أكثر، نقربها للعدد الصحيح التالي
                        nettaxx = Math.ceil(nettaxx);
                    }

                    $("#insurance_tax").val(nettaxx.toFixed(2));

                    var netsupervision = (netInstallment * 0.005).toFixed(3);
                    $("#insurance_supervision").val(netsupervision);

                    // حساب المجموع النهائي
                    var insurance_total =
                        parseFloat($('#insurance_installment').val()) +
                        parseFloat($('#insurance_supervision').val()) +
                        parseFloat($('#insurance_tax').val()) +
                        parseFloat($('#insurance_version').val()) +
                        parseFloat($('#insurance_stamp').val());

                    $("#insurance_total").val(insurance_total.toFixed(2));
                    $('#loader-overlay').hide();
                },
                error: function(xhr, status, error) {
                    console.error('خطأ في جلب بيانات الضريبة:', error);
                    $('#loader-overlay').hide();
                }
            });
        }

        // function visitedcountry(countryId) {
        //     const insuranceDaysInput = document.getElementById('insurance_days_number');
        //     const minDays = countryId === '1' || countryId === '2' || countryId === '3' ? 7 : 15;
        //     insuranceDaysInput.min = minDays;
        //     insuranceDaysInput.value = Math.max(insuranceDaysInput.value, minDays);

        //     // Fetch country data using Fetch API (assuming modern browser support)
        //     fetch('../office/issuing/country/' + countryId)
        //         .then(response => response.json()) // Parse JSON response
        //         .then(data => {
        //             if (data) {
        //                 // Assuming the response now contains a 'country_count' property
        //                 const countryCount = data.name.split(" ").length;
        //                 $("#insurance_country_number").val(countryCount);

        //                 var insurance_clauses_id = $('#insurance_clauses_id').val();
        //                 // /القسط اليومي
        //                 var insurance_installment_daily = $('#insurance_installment_daily').val();
        //                 // /القسط 
        //                 var insurance_installment = $('#insurance_installment').val();
        //                 // /الاشراف 
        //                 var insurance_days_number = $('#insurance_days_number').val();

        //                 var insurance_supervision = $('#insurance_supervision').val();
        //                 var insurance_tax = $('#insurance_tax').val();
        //                 var insurance_version = $('#insurance_version').val();
        //                 var insurance_stamp = $('#insurance_stamp').val();

        //                 var insurance_total = $('#insurance_total').val();
        //                 var countryCountt = $('#insurance_country_number').val();

        //                 $.ajax({
        //                     url: '../office/issuing/tax', // Correct URL with country ID
        //                     type: 'GET',
        //                     success: function(response) {
        //                         // Handle the response

        //                         if (response) {

        //                             if (insurance_clauses_id == "PV") // 1//
        //                             {
        //                                 if (countryCountt == 2) {
        //                                     $("#insurance_installment_daily").val(10);
        //                                 } else if (countryId == 4) {
        //                                     $("#insurance_installment_daily").val(3);
        //                                 } else {

        //                                     $("#insurance_installment_daily").val(response
        //                                         .installment_daily_1);

        //                                 }

        //                                 // صافي القسط هو عدد الايام في القسط 
        //                                 var netInstallment = insurance_days_number * $(
        //                                     "#insurance_installment_daily").val();
        //                                 $("#insurance_installment").val(netInstallment);
        //                                 // الضربية      صافي القسط *١٪ 
        //                                 var nettaxx = netInstallment * (1 / 100);
        //                                 // nettax = nettaxx >= 0.5 ? 1 : nettaxx;
        //                                 $("#insurance_tax").val(nettaxx);
        //                                 // صافي الاشراف هو    ابضربية *5% 

        //                                 var netsupervision = nettaxx * (0.5 / 100);
        //                                 const roundedNumber = (netsupervision * 100).toFixed(
        //                                     3); // Multiply by 1000 to shift decimal point

        //                                 $("#insurance_supervision").val(roundedNumber);

        //                                 var insurance_installment = parseFloat($(
        //                                     '#insurance_installment').val());
        //                                 var insurance_supervision = parseFloat($(
        //                                     '#insurance_supervision').val());
        //                                 var insurance_tax = parseFloat($('#insurance_tax')
        //                                     .val());
        //                                 var insurance_version = parseFloat($(
        //                                         '#insurance_version')
        //                                     .val());
        //                                 var insurance_stamp = parseFloat($('#insurance_stamp')
        //                                     .val());

        //                                 var insurance_total = insurance_installment +
        //                                     insurance_supervision + insurance_tax +
        //                                     insurance_version +
        //                                     insurance_stamp;


        //                                 $("#insurance_total").val(insurance_total);
        //                             } else {
        //                                 if (countryCountt == 2) {
        //                                     $("#insurance_installment_daily").val(11);
        //                                 } else if (countryId == 4) {
        //                                     $("#insurance_installment_daily").val(3);
        //                                 } else {

        //                                     $("#insurance_installment_daily").val(response
        //                                         .installment_daily_2);

        //                                 }

        //                                 var netInstallment = $("#insurance_installment_daily")
        //                                     .val() *
        //                                     insurance_days_number
        //                                 $("#insurance_installment").val(netInstallment.toFixed(
        //                                     2));

        //                                 // صافي القسط هو عدد الايام في القسط 


        //                                 var nettaxx = netInstallment * (1 / 100);
        //                                 // nettax = nettaxx >= 0.5 ? 1 : nettaxx;
        //                                 $("#insurance_tax").val(nettaxx);
        //                                 // صافي الاشراف هو    ابضربية *5% 

        //                                 var netsupervision = nettaxx * (0.5 / 100);
        //                                 const roundedNumber = (netsupervision * 100).toFixed(
        //                                     3); // Multiply by 1000 to shift decimal point

        //                                 $("#insurance_supervision").val(roundedNumber);

        //                                 var insurance_installment = parseFloat($(
        //                                     '#insurance_installment').val());
        //                                 var insurance_supervision = parseFloat($(
        //                                     '#insurance_supervision').val());
        //                                 var insurance_tax = parseFloat($('#insurance_tax')
        //                                     .val());
        //                                 var insurance_version = parseFloat($(
        //                                         '#insurance_version')
        //                                     .val());
        //                                 var insurance_stamp = parseFloat($('#insurance_stamp')
        //                                     .val());

        //                                 var insurance_total = insurance_installment +
        //                                     insurance_supervision + insurance_tax +
        //                                     insurance_version +
        //                                     insurance_stamp;

        //                                 $("#insurance_total").val(insurance_total);




        //                             }
        //                             // alert(insurance_clauses_id);
        //                         } else {
        //                             console.error('Country count not found in response');
        //                         }
        //                     },
        //                     error: function(xhr, status, error) {
        //                         console.error('Error fetching country data:', error);
        //                     }
        //                 });


        //             } else {
        //                 console.error('Country count not found in response');
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error fetching country data:', error);
        //         });
        // }
        function visitedcountry(countryId) {
            const minDays = ['1', '2', '3'].includes(countryId) ? 7 : 15;
            $('#insurance_days_number').attr('min', minDays).val(Math.max($('#insurance_days_number').val(),
                minDays));

            fetch(`../office/issuing/country/${countryId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data) throw new Error("لم يتم العثور على بيانات الدولة");
                    const countryCount = data.name.split(" ").length;
                    $("#insurance_country_number").val(countryCount);
                    calculateInsurance();
                })
                .catch(error => console.error('خطأ في جلب بيانات الدولة:', error));
        }
        $('button[type="submit"]').prop('disabled', false);

    });
</script>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title" style="margin-bottom:0px"><a href="">اصدار وثيقة</a></h4>
                <h4 style="color: #d68e64;font-weight: bold;"> تأمين عربي موحد
                </h4>
            </div>
        </div>
        <div class="col-md-12">

            <div class="box-content">

                <form method="POST" class="" action="">
                    @csrf
                    <label class="form-group col-md-12 box-title" style="color: #773f2d;">بيانات المؤمن له </label>

<div class="row">
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label"> اسم المؤمن </label>
                        <input type="text" name="insurance_name"
                            class="form-control @error('insurance_name') is-invalid @enderror"
                            value="{{ old('insurance_name') }}" id="insurance_name" placeholder=" اسم المؤمن ">
                        @error('insurance_name')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label">العنوان</label>
                        <input type="text" name="insurance_location"
                            class="form-control @error('insurance_location') is-invalid @enderror"
                            value="{{ old('insurance_location') }}" id="insurance_location" placeholder="العنوان">
                        @error('insurance_location')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label">الهاتف</label>
                        <input type="text" name="insurance_phone"
                            class="form-control @error('insurance_phone') is-invalid @enderror"
                            value="{{ old('insurance_phone') }}" id="insurance_phone" placeholder="الهاتف">
                        @error('insurance_phone')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    </div>
                    {{-- <label class="form-group col-md-12 box-title" style="color: #773f2d;">بيانات السيارة </label> --}}
                    {{-- <div class="form-group col-md-3">
                    <label for="inputName" class="control-label"> رقم المحرك </label>
                    <input type="text" name="motor_number"
                        class="form-control @error('motor_number') is-invalid @enderror"
                        value="{{ old('motor_number') }}" id="motor_number" placeholder=" رقم المحرك   ">
                    @error('motor_number')
                        <span class="invalid-feedback" style="color: red" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div> --}}
                <div class="row">

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
                        <label for="inputName" class="control-label"> اللوحة المعدنية </label>
                        <input type="text" name="plate_number"
                            class="form-control @error('plate_number') is-invalid @enderror"
                            value="{{ old('plate_number') }}" id="plate_number" placeholder="   اللوحة المعدنية       ">
                        @error('plate_number')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputName" class="control-label"> تاريخ الصنع </label>
                        <select name="car_made_date" id="car_made_date"
                            class="form-control  @error('car_made_date') is-invalid @enderror"
                            aria-label="Default select example" required="">
                            <option value=""> اختر السنة </option>

                            <option value="1950"> 1950 </option>

                            <option value="1951"> 1951 </option>
                            <option value="1952"> 1952 </option>
                            <option value="1953"> 1953 </option>
                            <option value="1954"> 1954 </option>
                            <option value="1955"> 1955 </option>
                            <option value="1956"> 1956 </option>
                            <option value="1957"> 1957 </option>
                            <option value="1958"> 1958 </option>
                            <option value="1959"> 1959 </option>
                            <option value="1960"> 1960 </option>
                            <option value="1961"> 1961 </option>
                            <option value="1962"> 1962 </option>
                            <option value="1963"> 1963 </option>
                            <option value="1964"> 1964 </option>
                            <option value="1965"> 1965 </option>
                            <option value="1966"> 1966 </option>
                            <option value="1967"> 1967 </option>
                            <option value="1968"> 1968 </option>
                            <option value="1969"> 1969 </option>
                            <option value="1970"> 1970 </option>
                            <option value="1971"> 1971 </option>
                            <option value="1972"> 1972 </option>
                            <option value="1973"> 1973 </option>
                            <option value="1974"> 1974 </option>
                            <option value="1975"> 1975 </option>
                            <option value="1976"> 1976 </option>
                            <option value="1977"> 1977 </option>
                            <option value="1978"> 1978 </option>
                            <option value="1979"> 1979 </option>
                            <option value="1980"> 1980 </option>
                            <option value="1981"> 1981 </option>
                            <option value="1982"> 1982 </option>
                            <option value="1983"> 1983 </option>
                            <option value="1984"> 1984 </option>
                            <option value="1985"> 1985 </option>
                            <option value="1986"> 1986 </option>
                            <option value="1987"> 1987 </option>
                            <option value="1988"> 1988 </option>
                            <option value="1989"> 1989 </option>
                            <option value="1990"> 1990 </option>
                            <option value="1991"> 1991 </option>
                            <option value="1992"> 1992 </option>
                            <option value="1993"> 1993 </option>
                            <option value="1994"> 1994 </option>
                            <option value="1995"> 1995 </option>
                            <option value="1996"> 1996 </option>
                            <option value="1997"> 1997 </option>
                            <option value="1998"> 1998 </option>
                            <option value="1999"> 1999 </option>
                            <option value="2000"> 2000 </option>
                            <option value="2001"> 2001 </option>
                            <option value="2002"> 2002 </option>
                            <option value="2003"> 2003 </option>
                            <option value="2004"> 2004 </option>
                            <option value="2005"> 2005 </option>
                            <option value="2006"> 2006 </option>
                            <option value="2007"> 2007 </option>
                            <option value="2008"> 2008 </option>
                            <option value="2009"> 2009 </option>
                            <option value="2010"> 2010 </option>
                            <option value="2011"> 2011 </option>
                            <option value="2012"> 2012 </option>
                            <option value="2013"> 2013 </option>
                            <option value="2014"> 2014 </option>
                            <option value="2015"> 2015 </option>
                            <option value="2016"> 2016 </option>
                            <option value="2017"> 2017 </option>
                            <option value="2018"> 2018 </option>
                            <option value="2019"> 2019 </option>
                            <option value="2020"> 2020 </option>
                            <option value="2021"> 2021 </option>
                            <option value="2022"> 2022 </option>
                            <option value="2023"> 2023 </option>
                            <option value="2024"> 2024 </option>
                                                                                    <option value="2025"> 2025 </option>

                        </select>
                        @error('car_made_date')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputName" class="control-label"> النوع </label>

                        <select id="cars_id" class="form-control  @error('cars_id') is-invalid @enderror"
                            aria-label="Default select example" name="cars_id" required="">
                            <option value="">اختر النوع</option>
                            @foreach ($cars as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('cars_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                   </div>
                   <div class="row">

                    <div class="form-group col-md-3">
                        <label for="inputName" class="control-label"> جنسية المركبة </label>
                        <select id="vehicle_nationalities_id"
                            class="form-control  @error('vehicle_nationalities_id') is-invalid @enderror"
                            aria-label="Default select example" name="vehicle_nationalities_id" required="">
                            <option value="">اختر جنسية</option>
                            @foreach ($VehicleNationality as $item)
                                <option value="{{ $item->id }}" @if ($loop->first) selected @endif>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_nationalities_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="inputName" class="control-label"> البلد المزار </label>
                        <select id="countries_id" onchange="visitedcountry(this.value)"
                            class="visited_country form-control @error('countries_id') is-invalid @enderror"
                            aria-label="Default select example" name="countries_id" required="">
                            <option value="">اختر البلد</option>
                            @foreach ($Countries as $item)
                                <option value="{{ $item->id }}" @if ($loop->first) selected @endif>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_nationalities_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror

                        </select>
                        @error('countries_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    </div>
                    <label class="form-group col-md-12 box-title" style="color: #773f2d;"> مدة التامين </label>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label"> من يوم </label>
                       <input type="date" name="insurance_day_from" min="{{$todatdate}}"
                            class="insurance_day_from form-control @error('insurance_day_from') is-invalid @enderror"
                            value="{{ $todatdate }}" id="insurance_day_from" placeholder="        ">
                        @error('insurance_day_from')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label"> عدد الايام </label>
                        <input type="number" name="insurance_days_number" value="7"
                            class="insurance_days_number form-control @error('insurance_days_number') is-invalid @enderror"
                            value="{{ old('insurance_days_number') }}" max="90" id="insurance_days_number"
                            placeholder="    ">
                        @error('insurance_days_number')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label"> الي يوم </label>
                        <input type="date" name="nsurance_day_to"
                            class="nsurance_day_to form-control @error('nsurance_day_to') is-invalid @enderror"
                            value="{{ old('nsurance_day_to') }}" id="insurance_day_to" placeholder="        " readonly>
                        @error('nsurance_day_to')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <label class="form-group col-md-12 box-title" style="color: #773f2d;"> إحتساب القسط </label>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label"> البند </label>
                        <select name="insurance_clauses_id" id="insurance_clauses_id"
                            class="form-control @error('insurance_clauses_id') is-invalid @enderror"
                            aria-label="Default select example" name="insurance_clauses_id" required="">

                            <option value="">اختر البند</option>
                            @foreach ($insurance_clauses as $item)
                                <option value="{{ $item->type }}" @if ($loop->first) selected @endif>
                                    {{ $item->type_id }}-{{ $item->slug }}</option>
                            @endforeach

                        </select>
                        @error('insurance_clauses_id')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label"> عدد الدول </label>
                        <input type="text" name="insurance_country_number" value="1"
                            class="insurance_country_number form-control @error('insurance_country_number') is-invalid @enderror"
                            value="{{ old('insurance_country_number') }}" id="insurance_country_number" placeholder=" "
                            readonly>

                        @error('insurance_country_number')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputName" class="control-label"> القسط اليومي </label>
                        <input type="text" name="insurance_installment_daily"
                            class="insurance_installment_daily form-control @error('insurance_installment_daily') is-invalid @enderror"
                            value="{{ $price->installment_daily_1 }}" id="insurance_installment_daily"
                            placeholder="        " readonly>

                        @error('insurance_installment_daily')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> القسط </label>
                        <input type="text" name="insurance_installment"
                            class="insurance_installment form-control @error('insurance_installment') is-invalid @enderror"
                            value="49" id="insurance_installment" placeholder="  القسط" readonly>

                        @error('insurance_installment')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> الضريبة </label>
                        <input type="text" name="insurance_tax"
                            class="insurance_tax form-control @error('insurance_tax') is-invalid @enderror"
                            value="0.5" id="insurance_tax" placeholder="  الضريبة" readonly>

                        @error('insurance_tax')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> الإشراف </label>
                        <input type="text" name="insurance_supervision"
                            class="insurance_supervision form-control @error('insurance_supervision') is-invalid @enderror"
                            value="0.245" id="insurance_supervision" placeholder="  الإشراف" readonly>

                        @error('insurance_supervision')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> الإصدار </label>
                        <input type="text" name="insurance_version" id="insurance_version"
                            class="insurance_version form-control @error('insurance_version') is-invalid @enderror"
                            value="10.000" placeholder="  الإصدار" readonly>

                        @error('insurance_version')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> دمغة المحررات </label>
                        <input type="text" name="insurance_stamp"
                            class="insurance_stamp form-control @error('insurance_stamp') is-invalid @enderror"
                            value="0.250" id="insurance_stamp" placeholder="   دمغة المحررات " readonly>

                        @error('insurance_stamp')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputName" class="control-label"> الاجمالي </label>
                        <input type="text" name="insurance_total" id="insurance_total"
                            class="insurance_total form-control @error('insurance_total') is-invalid @enderror"
                                                       value="59.995" id="insurance_total" placeholder="   الاجمالي  " readonly>

                        @error('insurance_total')
                            <span class="invalid-feedback" style="color: red" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-12" style="text-align: left;">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">اصدار</button>
                    </div>
                </form>
            </div>
            <!-- /.box-content -->
        </div>


    </div>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('form').on('submit', function(event) {
                // Show the loader
                $('#loader-overlay').show();

                // Calculate the increased timeout based on the current time
                // var currentTime = new Date().getTime();
                // var increasedTimeout = 10000 ; // Adjust the additional time as needed

                // // Set the timeout with the increased duration
                // setTimeout(function() {
                //     // Hide the loader after the simulated delay
                //     $('#loader-overlay').hide();
                //     location.reload();
                //     // Handle the form submission as needed
                //     console.log('Form submitted successfully!');
                // }, increasedTimeout);

            });
        });
  
    
</script>
@endsection
