<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بطاقة التأمين العربية الموحدة - ليبيا</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 5mm;
            color: #000;
            direction: rtl;
            font-size: 10pt;
        }
        
        .container {
            width: 190mm;
            border: 2px solid #000;
            padding: 5mm;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 40pt;
            color: rgba(255, 0, 0, 0.1);
            font-weight: bold;
        }
        
        .header-top {
            text-align: center;
            color: red;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3mm;
        }
        
        .main-header {
            width: 100%;
            margin-bottom: 3mm;
            text-align: center;
        }
        
        .main-header h1 {
            margin: 0;
            font-size: 16pt;
        }
        
        .main-header h2 {
            margin: 0;
            font-size: 12pt;
        }
        
        .main-header h3 {
            margin: 2mm 0;
            font-size: 14pt;
            color: red;
        }
        
        .card-number-display {
            font-weight: bold;
            font-size: 12pt;
        }
        
        .logo-left {
            float: left;
            width: 20mm;
        }
        
        .logo-right {
            float: right;
            width: 20mm;
        }
        
        .logo-img {
            width: 18mm;
            height: auto;
        }
        
        .contact-wrapper {
            clear: both;
            width: 100%;
            margin-top: 3mm;
        }
        
        .contact-box {
            width: 85mm;
            border: 1px solid #000;
            padding: 2mm;
            font-size: 8pt;
            line-height: 1.3;
        }
        
        .red-box {
            color: red;
            border-color: red;
        }
        
        .contact-left {
            float: left;
        }
        
        .contact-right {
            float: right;
        }
        
        .qr-section {
            text-align: center;
            margin: 0 auto;
            padding: 2mm 0;
        }
        
        .qr-code img {
            width: 15mm;
            height: 15mm;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2mm;
            font-size: 9pt;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 1mm 2mm;
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
            padding: 1mm;
            font-weight: bold;
            margin-top: 3mm;
            font-size: 9pt;
        }
        
        .countries-grid {
            width: 100%;
            border: 1px solid #000;
            font-size: 8pt;
        }
        
        .countries-grid td {
            border: none;
            padding: 1mm;
            width: 16.66%;
        }
        
        .instructions {
            margin-top: 3mm;
            font-size: 7pt;
            line-height: 1.2;
        }
        
        .instructions h4 {
            margin: 0;
            text-decoration: underline;
            font-size: 8pt;
        }
        
        .instructions ol {
            margin: 1mm 0;
            padding-right: 10mm;
        }
        
        .footer {
            margin-top: 3mm;
            font-size: 9pt;
        }
        
        .warning {
            color: red;
            font-weight: bold;
            margin-top: 2mm;
            font-size: 9pt;
        }
        
        .date-row {
            margin-top: 2mm;
            font-size: 9pt;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    {{-- @if ($test_mode ?? false) --}}
        <div class="watermark">Test Document</div>
        <div class="header-top">
            This document is generated for testing purposes only. This is not a valid document.
        </div>
    {{-- @endif --}}

    <div class="main-header">
        <div class="logo-right">
            <img class="logo-img" src="{{ public_path('logo.png') }}" alt="Logo">
        </div>
        <div class="logo-left">
            <img class="logo-img" src="{{ public_path('gaif-logo.png') }}" alt="Logo">
        </div>
        <h1>بطاقة التأمين العربية الموحدة</h1>
        <h2>عن سير السيارات (المركبات) عبر البلاد العربية</h2>
        <h3>للمركبات الليبية</h3>
        <div class="card-number-display">{{ $card_number ?? 'LBY/000000' }}</div>
    </div>

    <div class="contact-wrapper">
        <div class="contact-box red-box contact-right">
            <strong>المكتب الموحد:</strong> {{ $unified_office_name ?? 'المكتب الموحد Libyan' }}<br>
            <strong>العنوان:</strong> {{ $unified_office_address ?? 'عنوان المكتب الموحد' }}<br>
            <strong>صندوق البريد:</strong> {{ $unified_office_box ?? 'صندوق البريد' }}<br>
            <strong>الهاتف:</strong> {{ $unified_office_phone ?? '+0000000000' }}<br>
            <strong>البريد الإلكتروني:</strong> {{ $unified_office_email ?? 'email@example.com' }}
        </div>
        <div class="contact-box contact-left">
            <strong>الشركة المصدرة للبطاقة:</strong> {{ $insurance_company ?? 'شركة التأمين' }}<br>
            <strong>العنوان:</strong> {{ $company_address ?? 'عنوان الشركة' }}<br>
            <strong>صندوق البريد:</strong> {{ $company_box ?? 'صندوق البريد' }}<br>
            <strong>الهاتف:</strong> {{ $company_phone ?? '0000000000' }}<br>
            <strong>البريد الإلكتروني:</strong> {{ $company_email ?? 'info@company.com' }}
        </div>
        <div class="qr-section">
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode($card_number ?? 'LBY000000') }}" alt="QR">
            </div>
        </div>
    </div>

    <table>
        <tr>
            <td class="bg-gray" style="width: 12%;">إسم المؤمن له</td>
            <td style="width: 30%;">{{ $beneficiary_name ?? 'اسم المؤمن له' }}</td>
            <td class="bg-gray" style="width: 10%;">العنوان</td>
            <td style="width: 20%;">{{ $beneficiary_address ?? 'العنوان' }}</td>
            <td class="bg-gray" style="width: 10%;">الهاتف</td>
            <td>{{ $beneficiary_phone ?? '0000000000' }}</td>
        </tr>
    </table>

    <table>
        @php
            $visibleFields = [];
            try {
                $visibleFields = \App\Models\CardFieldVisibility::getVisibleFields();
            } catch (\Exception $e) {
                $visibleFields = [
                    ['field_name' => 'vehicle_type', 'field_label' => 'نوع المركبة', 'visible' => true],
                    ['field_name' => 'vehicle_nationality', 'field_label' => 'جنسية المركبة', 'visible' => true],
                    ['field_name' => 'manufacturing_year', 'field_label' => 'سنة الصنع', 'visible' => true],
                    ['field_name' => 'chassis_number', 'field_label' => 'رقم الهيكل', 'visible' => true],
                    ['field_name' => 'plate_number', 'field_label' => 'رقم اللوحة', 'visible' => true],
                    ['field_name' => 'engine_number', 'field_label' => 'رقم المحرك', 'visible' => true],
                    ['field_name' => 'usage_purpose', 'field_label' => 'الغرض من الإستعمال', 'visible' => true],
                ];
            }
            $fieldRows = array_chunk($visibleFields->toArray(), 2);
        @endphp

        @foreach ($fieldRows as $row)
            <tr>
                @foreach ($row as $field)
                    <td class="bg-gray" style="width: 15%;">{{ $field['field_label'] }}</td>
                    <td>
                        @if ($field['field_name'] == 'usage_purpose')
                            {{ $usage_purpose ?? 'خاصة' }}
                        @elseif ($field['field_name'] == 'vehicle_type')
                            {{ $vehicle_type ?? 'نوع المركبة' }}
                        @elseif ($field['field_name'] == 'vehicle_nationality')
                            {{ $vehicle_nationality ?? 'الجنسية' }}
                        @elseif ($field['field_name'] == 'manufacturing_year')
                            {{ $manufacturing_year ?? '2025' }}
                        @elseif ($field['field_name'] == 'chassis_number')
                            {{ $chassis_number ?? 'رقم الهيكل' }}
                        @elseif ($field['field_name'] == 'plate_number')
                            {{ $plate_number ?? 'رقم اللوحة' }}
                        @elseif ($field['field_name'] == 'engine_number')
                            {{ $engine_number ?? 'رقم المحرك' }}
                        @else
                            {{ isset($$field['field_name']) ? $$field['field_name'] : '' }}
                        @endif
                    </td>
                @endforeach
                @if (count($row) == 1)
                    <td colspan="2"></td>
                @endif
            </tr>
        @endforeach
    </table>

    @php
        function convertToArabicDatePdf($date) {
            if (empty($date)) return $date;
            $arabicMonths = [
                '01' => 'يناير', '02' => 'فبراير', '03' => 'مارس', '04' => 'أبريل',
                '05' => 'مايو', '06' => 'يونيو', '07' => 'يوليو', '08' => 'أغسطس',
                '09' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر',
            ];
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $matches)) {
                $day = $matches[1];
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];
                return $day . '/' . ($arabicMonths[$month] ?? $month) . '/' . $year;
            }
            return $date;
        }
    @endphp

    <table>
        <tr class="bg-gray" style="text-align: center;">
            <td rowspan="2" style="vertical-align: middle; width: 12%;">سريان التأمين</td>
            <td>من الساعة</td>
            <td>{{ $insurance_start_time ?? '00:00' }}</td>
            <td>يوم</td>
            <td>{{ $insurance_start_day ?? 'السبت' }}</td>
            <td>الموافق</td>
            <td>{{ convertToArabicDatePdf($insurance_start_date ?? '05/يوليو/2025') }}</td>
        </tr>
        <tr class="bg-gray" style="text-align: center;">
            <td>إلى الساعة</td>
            <td>{{ $insurance_end_time ?? '23:59' }}</td>
            <td>يوم</td>
            <td>{{ $insurance_end_day ?? 'الأحد' }}</td>
            <td>الموافق</td>
            <td>{{ convertToArabicDatePdf($insurance_end_date ?? '31/ديسمبر/2025') }}</td>
        </tr>
    </table>

    <div class="countries-title">البلاد التي تسري فيها البطاقة</div>
    <table class="countries-grid">
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
            $countryChunks = array_chunk($displayCountries, 6, true);
        @endphp
        
        @foreach ($countryChunks as $chunk)
            <tr>
                @foreach ($chunk as $symbol => $enabled)
                    <td><input type="checkbox" {{ $enabled ? 'checked' : '' }}> {{ $countryNames[$symbol] ?? $symbol }}</td>
                @endforeach
                @for ($i = count($chunk); $i < 6; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
    </table>

    <table style="font-size: 7pt;">
        <tr class="bg-gray">
            <td style="width: 10%;">البلد</td>
            <td style="width: 45%;">عناوين المكاتب الموحدة</td>
            <td style="width: 45%;">نوعية التغطيات</td>
        </tr>
        <tr>
            <td>تونس</td>
            <td>إقامة شعباني / واد حيدرة - حيدرة 16033 الجزائر ++21321604507</td>
            <td>الأضرار الجسمانية بقيمة محددة والمادية غير محددة</td>
        </tr>
        <tr>
            <td>الجزائر</td>
            <td>إقامة شعباني / واد حيدرة - حيدرة 16033 الجزائر ++21321604507</td>
            <td>الأضرار البدنية بقيمة محدده والمادية بقيمة غير محددة</td>
        </tr>
    </table>

    <div class="instructions">
        <h4>إرشادات وشروط عامة:</h4>
        <ol>
            <li>يقصد بلفظ سيارة (مركبة) كل مركبة ألية يلزم القانون في البلد المصدر للبطاقة بوجوب إبرام مالكها لوثيقة التأمين اللزامي له.</li>
            <li>تغطي هذه البطاقة أضرار الشخص الثالث (الغير) الناجمة عن الحوادث التي تسببها المركبة المؤمنة.</li>
            <li>في حالة زيارة المركبة المؤمن عليها لأي بلد عربي تشمله هذه البطاقة فإن المكتب الموحد يتلقى المطالبات.</li>
            <li>في حالة إنتهاء مدة هذه البطاقة أثناء تواجد المؤمن له في البلد المزار فعليه الحصول علي وثيقة تأمين محلية.</li>
            <li>لا يحق للمؤمن له إلغاء هذه البطاقة.</li>
            <li>يحق للمؤمن الرجوع على المؤمن له بما أداه من تعويضات في حالة مخالفة القوانين.</li>
        </ol>
    </div>

    <div class="footer">
        <strong>إجمالي القسط والرسوم: {{ $total_premium ?? '0.00' }} د.ل</strong><br>
        <strong>تقوم الشركة المصدرة للبطاقة بمحاسبة مصلحة الضرائب على الرسوم المستحقة.</strong>
    </div>

    <div class="date-row">
        الساعة: {{ $issue_time ?? '09:00 ص' }} | 
        سنة: {{ $issue_year ?? '2025' }} | 
        شهر: {{ $issue_month ?? 'يوليو' }} | 
        الموافق: {{ $issue_weekday ?? 'السبت' }} | 
        تحريراً في يوم: {{ $issue_day ?? '05' }}
    </div>

    <div class="warning">
        <strong>هام : أي كشط أو شطب أو تعديل في هذه الصفحة يبطل البطاقة وتعد لاغية</strong>
    </div>

    @if ($test_mode ?? false)
        <div class="warning" style="text-align: center;">
            <small>This document is generated for testing purpose only.</small>
        </div>
    @endif
</div>

</body>
</html>
