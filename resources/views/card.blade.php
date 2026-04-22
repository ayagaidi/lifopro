<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة التأمين العربية الموحدة - ليبيا</title>
    {{-- <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script> --}}

    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">


    <style>

body {
    font-family: 'Cairo', Arial, sans-serif;
    direction: rtl;
    unicode-bidi: embed;
    text-align: right;
}

ol, ul {
    direction: rtl;
    text-align: right;
    list-style-position: inside;
    padding-left: 0;
    padding-right: 10px;
}

ol li, ul li {
    text-align: right;
    padding-right: 5px;
    padding-left: 0;
    margin-right: 0;
    margin-left: auto;
    unicode-bidi: embed;
    display: list-item;
    list-style-position: inside;
}

@page {
            size: A4;
            margin: 5mm;
        }

        body {
            font-family: 'Cairo', Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .container {
            width: 100%;
            max-width: 175mm;
            margin: 0 auto;
            background-color: #fff;
            padding: 4px;
            border: 2px solid #000;
        }

        body {
            font-family:  Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .container {
            width: 100%;
            max-width: 175mm;
            margin: 0 auto;
            background-color: #fff;
            padding: 6px;
            border: 2px solid #000;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 50px;
            color: rgba(255, 0, 0, 0.1);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
            white-space: nowrap;
        }

        .header-top {
            text-align: center;
            color: red;
            font-weight: bold;
            font-size: 6px;
            margin-bottom: 2px;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2px;
        }

        .header-center {
            text-align: center;
            flex: 1;
        }

        .header-center h1 {
            margin: 0;
            font-size: 13px;
        }

        .header-center h2 {
            margin: 0;
            font-size: 11px;
        }

        .header-center h3 {
            margin: 1px 0;
            font-size: 12px;
            color: red;
        }

        .logo-img {
            width: 55px !important;
        }

        .contact-info-wrapper {
            display: flex;
            justify-content: space-between;
            margin-top: 4px;
            gap: 6px;
        }

        .contact-box {
            width: 48%;
            border: 1px solid #000;
            padding: 3px;
            font-size: 8px;
            line-height: 1.2;
        }

        .qr-section {
            text-align: center;
            width: 55px;
        }

        .qr-code {
            width: 50px;
            height: 50px;
            border: 1px solid #000;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            font-size: 9px;
            z-index: 1;
            position: relative;
        }

        th, td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: right;
        }

        .countries-title {
            text-align: center;
            background: #eee;
            border: 1px solid #000;
            border-bottom: none;
            padding: 2px;
            font-weight: bold;
            margin-top: 4px;
            font-size: 9px;
        }

        .countries-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            border: 1px solid #000;
            padding: 3px;
            font-size: 8px;
        }

        .country-item {
            display: flex;
            align-items: center;
            gap: 1px;
        }

        .instructions {
            margin-top: 4px;
            font-size: 7px;
            line-height: 1.2;
        }

        .instructions h4 {
            margin: 0;
            text-decoration: underline;
        }

        .instructions-list {
            font-size: 7px;
            line-height: 1.4;
        }

        .instructions-list div {
            margin-bottom: 2px;
        }

        .instructions-list .num {
            display: inline-block;
            width: 15px;
            font-weight: bold;
            margin-left: 5px;
        }

        .instructions ol, .instructions ul {
            margin: 1px 0;
            padding-right: 12px;
            padding-left: 0;
        }

        .instructions ol li, .instructions ul li {
            padding-right: 15px;
            padding-left: 0;
            text-align: right;
            direction: rtl;
        }

        .footer {
            margin-top: 4px;
            font-size: 9px;
        }

        .warning {
            color: red;
            font-weight: bold;
            margin-top: 4px;
            padding-top: 3px;
            font-size: 8px;
        }

        .date-container {
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between;
            align-items: center;
            direction: rtl;
            width: 100%;
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 0.8rem;
        }

        .date-item {
            display: flex;
            gap: 4px;
        }

        .card-number {
            font-weight: bold;
            font-size: 12px !important;
        }

        /* العلامة المائية */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 80px;
            color: rgba(255, 0, 0, 0.1);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
            white-space: nowrap;
        }

        .header-top {
            text-align: center;
            color: red;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .header-center {
            text-align: center;
            flex: 1;
        }

        .header-center h1 {
            margin: 0;
            font-size: 22px;
        }

        .header-center h2 {
            margin: 0;
            font-size: 20px;
        }

        .header-center h3 {
            margin: 5px 0;
            font-size: 24px;
            color: red;
        }

        .contact-info-wrapper {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            gap: 10px;
        }

        .contact-box {
            width: 48%;
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
            line-height: 1.4;
        }

        .red-box {
            color: red;
            border-color: red;
        }

        .qr-section {
            text-align: center;
            width: 100px;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            border: 1px solid #000;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 12px;
            z-index: 1;
            position: relative;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 8px;
            text-align: right;
        }

        .bg-gray {
            background-color: #efefef;
            font-weight: bold;
        }

        .countries-title {
            text-align: center;
            background: #eee;
            border: 1px solid #000;
            border-bottom: none;
            padding: 3px;
            font-weight: bold;
            margin-top: 10px;
        }

        .countries-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
        }

        .country-item {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .footer {
            margin-top: 10px;
            font-size: 12px;
        }

        .warning {
            color: red;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 5px;
        }

        .date-container {
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between;
            align-items: center;
            direction: rtl;
            width: 100%;
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .date-item {
            display: flex;
            gap: 10px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>

    <div class="container">
        @php 
            $issuing = $issuing ?? $card->issuings ?? null; 
            $office = $office ?? null;
            $company = $company ?? null;
            $car = $car ?? null;
        @endphp
        
        @if (env('TEST_MODE', ture))
        <div class="watermark" dir="ltr">Test Document</div>
        <div class="header-top" dir="ltr">
            This document is generated for testing purposes only. This is not a valid document.
        </div>
        @endif

        <div class="main-header">
            <div style="width: 55px;"><img class="logo-img" src="{{ asset('logo.png') }}" alt="Logo"></div>
            <div class="header-center">
                <h1>بطاقة التأمين العربية الموحدة</h1>
                <h2>عن سير السيارات (المركبات) عبر البلاد العربية</h2>
                <h3>للمركبات الليبية</h3>
                <div style="font-weight: bold; font-size: 12px;">{{ $card_number ?? 'LBY/000000' }}</div>
            </div>
            <div style="width: 55px; text-align: left;"><img class="logo-img" src="{{ asset('gaif-logo.png') }}" alt="Logo"></div>
        </div>

        <div class="contact-info-wrapper">
            <div class="contact-box red-box">
                <strong >المكتب الموحد</strong>: المكتب الموحد الليبي <br>
                <strong>العنوان</strong> :شارع جمال القاسمي بجانب جامع امبارك باب بن
غشير<br>
                <strong>صندوق البريد</strong>: ميدان الجزائر 4784<br>
                <strong>الهاتف</strong>: 218213632518+<br>
                <strong>الفاكس</strong>: 218213602571+<br>
                <strong>البريد الإلكتروني</strong>:lub@insurancefed.ly
            </div>
            <div class="qr-section">
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($card_number ?? 'LBY000000') }}"
                        alt="QR">
                </div>
            </div>

            <div class="contact-box">
                <strong dir="rtl">الشركة المصدرة  للبطاقة </strong>: {{ $insurance_company ?? '' }}<br>
                <strong dir="rtl">العنوان</strong> :{{ $company_address ?? '' }}<br>
                <strong dir="rtl">صندوق البريد</strong> :{{ $company_box ?? '' }}<br>
                <strong dir="rtl">الهاتف</strong> :{{ $company_phone ?? '' }}<br>
                <strong dir="rtl">الفاكس</strong> :{{ $company_fax ?? '' }}<br>
                <strong dir="rtl">البريد الإلكتروني</strong>: {{ $company_email ?? '' }}
            </div>
        </div>

        <table>
            <tr>
                <td class="bg-gray" width="12%">إسم المؤمن له</td>
                <td width="30%">{{ $issuing->insurance_name ?? 'اسم المؤمن له' }}</td>
                <td class="bg-gray" width="10%">العنوان</td>
                <td width="20%">{{ $issuing->insurance_location ?? 'العنوان' }}</td>
                <td class="bg-gray" width="10%">الهاتف</td>
                <td>{{ $issuing->insurance_phone ?? '0000000000' }}</td>
            </tr>
        </table>

        <table>
            @php
                $visibleFields = [];
                try {
                    $visibleFields = \App\Models\CardFieldVisibility::getVisibleFields();
                } catch (\Exception $e) {
                    $visibleFields = [
                        (object)['field_name' => 'vehicle_type', 'field_label' => 'نوع المركبة', 'visible' => true],
                        (object)['field_name' => 'vehicle_nationality', 'field_label' => 'جنسية المركبة', 'visible' => true],
                        (object)['field_name' => 'manufacturing_year', 'field_label' => 'سنة الصنع', 'visible' => true],
                        (object)['field_name' => 'chassis_number', 'field_label' => 'رقم الهيكل (الشاسيه)', 'visible' => true],
                        (object)['field_name' => 'plate_number', 'field_label' => 'رقم اللوحة', 'visible' => true],
                        (object)['field_name' => 'engine_number', 'field_label' => 'رقم المحرك (الموتور)', 'visible' => true],
                        (object)['field_name' => 'usage_purpose', 'field_label' => 'الغرض من الإستعمال', 'visible' => true],
                    ];
                }
                $fieldRows = array_chunk($visibleFields->toArray(), 2);
            @endphp

            @foreach ($fieldRows as $row)
                <tr>
                    @foreach ($row as $field)
                        <td class="bg-gray" width="15%">{{ $field['field_label'] }}</td>
                        <td>
                            @if ($field['field_name'] == 'usage_purpose')
                                {{ $issuing->insurance_clause ?? 'خاصة' }}
                            @elseif ($field['field_name'] == 'vehicle_type')
                                {{ $issuing->cars->name ?? 'نوع المركبة' }}
                            @elseif ($field['field_name'] == 'vehicle_nationality')
                                {{ $issuing->vehicle_nationalities->name ?? 'الجنسية' }}
                            @elseif ($field['field_name'] == 'manufacturing_year')
                                {{ $issuing->car_made_date ?? '2025' }}
                            @elseif ($field['field_name'] == 'chassis_number')
                                {{ $issuing->chassis_number ?? 'رقم الهيكل' }}
                            @elseif ($field['field_name'] == 'plate_number')
                                {{ $issuing->plate_number ?? 'رقم اللوحة' }}
                            @elseif ($field['field_name'] == 'engine_number')
                                {{ $issuing->motor_number ?? 'رقم المحرك' }}
                            @else
                                @if ($issuing)
                                    {{ $issuing->{$field['field_name']} ?? '' }}
                                @endif
                            @endif
                        </td>
                    @endforeach
                    @if (count($row) == 1)
                        <td colspan="2"></td>
                    @endif
                </tr>
            @endforeach
        </table>

        <table>
            <tr class="bg-gray" style="text-align: center;">
                <td rowspan="2" width="15%" style="vertical-align: middle;">سريان التأمين</td>
                <td>من الساعة</td>
                <td>{{ $insurance_start_time ?? '00:00' }}</td>
                <td>يوم</td>
                <td>{{ $insurance_start_day ?? 'يوم السبت' }}</td>
                <td>الموافق</td>
                <td>{{ $insurance_start_date ?? '05/يوليو/2025' }}</td>
            </tr>
            <tr class="bg-gray" style="text-align: center;">
                <td>إلى الساعة</td>
                <td>{{ $insurance_end_time ?? '23:59' }}</td>
                <td>يوم</td>
                <td>{{ $insurance_end_day ?? 'يوم الأحد' }}</td>
                <td>الموافق</td>
                <td>{{ $insurance_end_date ?? '31/ديسمبر/2025' }}</td>
            </tr>
        </table>

        <div class="countries-title">البلاد التي تسري فيها البطاقة</div>
        <div class="countries-grid">
            @php
                $countryNames = [
                    'OMN' => 'عمان', 'IRQ' => 'العراق', 'SYR' => 'سوريا',
                    'DZA' => 'الجزائر', 'TUN' => 'تونس', 'BHR' => 'البحرين',
                    'UAE' => 'الإمارات', 'JOR' => 'الأردن', 'YEM' => 'اليمن',
                    'EGY' => 'مصر', 'LBY' => 'ليبيا', 'LBN' => 'لبنان',
                    'KWT' => 'الكويت', 'QAT' => 'قطر',
                ];

                $defaultCountries = [
                    'OMN' => false, 'IRQ' => false, 'SYR' => false,
                    'DZA' => true, 'TUN' => true, 'BHR' => false,
                    'UAE' => false, 'JOR' => false, 'YEM' => false,
                    'EGY' => false, 'LBY' => false, 'LBN' => false,
                    'KWT' => false, 'QAT' => false,
                ];

                $displayCountries = $defaultCountries;
                if (isset($countries) && is_array($countries)) {
                    foreach ($countries as $symbol => $enabled) {
                        if(isset($displayCountries[$symbol])) {
                            $displayCountries[$symbol] = $enabled;
                        }
                    }
                }
            @endphp
            @foreach ($displayCountries as $symbol => $enabled)
                <div class="country-item">
                    <input type="checkbox" {{ $enabled ? 'checked' : '' }}> {{ $countryNames[$symbol] ?? $symbol }}
                </div>
            @endforeach
        </div>

        <table>
            <tr class="bg-gray" style="font-size: 10px;">
                <td width="10%">البلد</td>
                <td width="45%">عناوين المكاتب الموحدة التي يرجع إليها حامل البطاقة في حالة حدوث حادث او غيره</td>
                <td width="45%">بيان مختصر عن نوعية التغطيات طبقا لقوانين اللزامي في البلد العربية</td>
            </tr>
            @if (!empty($office_info))
                @foreach ($office_info as $office)
                    <tr style="font-size: 7px;">
                        <td>{{ $office['country'] ?? '' }}</td>
                        <td>{{ $office['address'] ?? '' }}</td>
                        <td>{{ $office['coverage'] ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr style="font-size: 7px;">
                    <td>تونس</td>
                    <td>إقامة شعباني / واد حيدرة. -حيدرة 16033 الجزائر ++21321604507 <br/>smg.buat@buat.com.tn<br/>   21671845124+</td>
                    <td>الأضرار الجسمانية بقيمة محددة والمادية غير محددة</td>
                </tr>
                <tr style="font-size: 7px;">
                    <td>الجزائر</td>
                    <td>إقامة شعباني / واد حيدرة. -حيدرة 16033 الجزائر ++21321604507 <br/> bua.algerie@gmail.com <br/>   21321609295+</td>
                    <td>الأضرار البدنية بقيمة محدده والضرارالمادية بقيمة غير محددة</td>
                </tr>
            @endif
        </table>

        <div class="instructions">
<h4>إرشادات وشروط عامة:</h4>
            <div class="instructions-list">
                <div><span class="num">1</span> يقصد بلفظ سيارة (مركبة) كل مركبة ألية يلزم القانون في البلد المصدر للبطاقة و/أو البلد المزار بوجوب إبرام مالكها لوثيقة التأمين اللزامي له.</div>
                <div><span class="num">2</span> تغطي هذه البطاقة أضرار الشخص الثالث (الغير) الناجمة عن الحوادث التي تسببها المركبة المؤمنة وفقاً لقانون البلد المزار، ولا تضمن الأضرار اللاحقة بها أيا كان سببها.</div>
                <div><span class="num">3</span> في حالة زيارة المركبة المؤمن عليها بموجب هذه البطاقة لأي بلد عربي تشمله هذه البطاقة فإن المكتب الموحد في هذا البلد يتلقى المطالبات الناجمة عن حوادث المركبات...</div>
                <div><span class="num">4</span> في حالة إنتهاء مدة هذه البطاقة أثناء تواجد المؤمن له في البلد المزار فإن عليه الحصول علي وثيقة تأمين محلية.</div>
                <div><span class="num">5</span> لا يحق للمؤمن له إلغاء هذه البطاقة.</div>
                <div><span class="num">6</span> يحق للمؤمن الرجوع على المؤمن له بما أداه من تعويضات في حالة مخالفة القوانين النافذة.</div>
            </div>
        </div>

        <div class="footer">
            <strong>إجمالي القسط والرسوم ( شامل الرسوم والضرائب الحكومية ): {{ $issuing->insurance_total ?? '0.00' }} د.ل</strong><br>
            <strong>تقوم الشركة المصدرة للبطاقة بمحاسبة مصلحة الضرائب على الرسوم المستحقة.</strong><br>

            <div class="date-container">
                <div class="date-item">
                    <span class="label">الساعة :</span>
                    <span class="value">{{ $issuing && $issuing->issuing_date ? $issuing->issuing_date->format('H:i') : '09:00 ص' }}</span>
                </div>
                <div class="date-item">
                    <span class="label">سنة :</span>
                    <span class="value">{{ $issuing && $issuing->issuing_date ? $issuing->issuing_date->format('Y') : '2025' }}</span>
                </div>
                <div class="date-item">
                    <span class="label">من شهر :</span>
                    <span class="value">{{ $issue_month ?? 'يوليو' }}</span>
                </div>
                <div class="date-item">
                    <span class="label">الموافق :</span>
                    <span class="value">{{ $issue_weekday ?? 'يوم السبت' }}</span>
                </div>
                <div class="date-item">
                    <span class="label">تحريراً في يوم :</span>
                    <span class="value">{{ $issuing && $issuing->issuing_date ? $issuing->issuing_date->format('d') : '05' }}</span>
                </div>
            </div>
        </div>

        <div class="warning">
            <strong>هام : أي كشط أو شطب أو تعديل في هذه الصفحة يبطل البطاقة وتعد لاغية</strong>
        </div>

        <div class="warning" style="text-align: center !important;border-top:none !important">
            @if (env('TEST_MODE', true))
                <small>This document is generated for testing purpose only. This is not a valid document</small>
                @endif
        </div>
    </div>

    <script>
window.onload = function () {
    const element = document.querySelector('.container');

    html2pdf().set({
        margin: 3,
        filename: 'insurance-card.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
            scale: 4, // مهم للجودة العالية
            useCORS: true
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        }
    }).from(element).save();
};


</script>
</body>
</html>