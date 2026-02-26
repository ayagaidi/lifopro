<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بطاقة التأمين العربية الموحدة - ليبيا</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            color: #000;
            direction: rtl;
            line-height: 1.2;
        }

        .container {
            width: 100%;
            max-width: 750px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 8px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 70px;
            color: rgba(255, 0, 0, 0.1);
            font-weight: bold;
            z-index: 0;
            white-space: nowrap;
        }

        .header-top {
            text-align: center;
            color: red;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
        }

        .main-header {
            text-align: center;
            margin-bottom: 3px;
        }

        .logos-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3px;
        }

        .logos-row .logo-cell {
            width: 100px;
            vertical-align: middle;
        }

        .logos-row .logo-left { text-align: left; }
        .logos-row .logo-right { text-align: right; }
        .logos-row .title-cell {
            flex: 1;
            text-align: center;
        }

        .logos-row img { width: 100px; }

        .main-header h1 { margin: 0; font-size: 15pt; }
        .main-header h2 { margin: 0; font-size: 11pt; }
        .main-header h3 { margin: 2px 0; font-size: 13pt; color: red; }
        
        .card-number {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 2px;
        }

        .contact-row {
            display: flex;
            justify-content: space-between;
            margin-top: 4px;
            gap: 5px;
            align-items: stretch;
        }

        .contact-row .contact-cell {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
            vertical-align: top;
            width: 48%;
            line-height: 1.4;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .contact-row .contact-cell.red-box {
            color: red;
            border-color: red;
        }

        .contact-row .qr-cell {
            width: 100px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-row .qr-cell img {
            width: 80px;
            height: 80px;
            border: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            font-size: 9pt;
        }

        th, td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: right;
        }

        .bg-gray {
            background-color: #ddd;
            font-weight: bold;
        }

        .countries-title {
            text-align: center;
            background: #ccc;
            border: 1px solid #000;
            border-bottom: none;
            padding: 2px;
            font-weight: bold;
            margin-top: 4px;
            font-size: 9pt;
        }

        .countries-grid {
            display: table;
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .countries-grid td {
            display: table-cell;
            border: none;
            padding: 1px 2px;
            width: 16.66%;
        }

        .instructions {
            margin-top: 4px;
            font-size: 7pt;
            line-height: 1.3;
        }

        .instructions h4 {
            margin: 0;
            text-decoration: underline;
            font-size: 8pt;
        }

        .instructions ol {
            margin: 1px 0;
            padding-right: 10px;
        }

        .instructions li { margin-bottom: 0; }

        .footer {
            margin-top: 4px;
            font-size: 8pt;
        }

        .warning {
            color: red;
            font-weight: bold;
            margin-top: 3px;
            font-size: 8pt;
        }

        .date-row {
            display: table;
            width: 100%;
            margin-top: 2px;
            font-size: 8pt;
            font-weight: bold;
        }

        .date-row .date-cell {
            display: table-cell;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    @if ($test_mode ?? false)
        <div class="watermark">Test Document</div>
        <div class="header-top">
            This document is generated for testing purposes only. This is not a valid document.
        </div>
    @endif

    <div class="main-header">
        <div class="logos-row">
            <div class="logo-cell logo-right">
                <img src="{{ public_path('logo.png') }}" alt="Logo">
            </div>
            <div class="title-cell">
                <h1>بطاقة التأمين العربية الموحدة</h1>
                <h2>عن سير السيارات (المركبات) عبر البلاد العربية</h2>
                <h3>للمركبات الليبية</h3>
                <div class="card-number">{{ $card_number ?? 'LBY/000000' }}</div>
            </div>
            <div class="logo-cell logo-left">
                <img src="{{ public_path('gaif-logo.png') }}" alt="Logo">
            </div>
        </div>
    </div>

    <div class="contact-row">
        <div class="contact-cell red-box">
            <strong>المكتب الموحد:</strong> {{ $unified_office_name ?? 'المكتب الموحد Libyan' }}<br>
            <strong>العنوان:</strong> {{ $unified_office_address ?? 'عنوان المكتب الموحد' }}<br>
            <strong>صندوق البريد:</strong> {{ $unified_office_box ?? 'صندوق البريد' }}<br>
            <strong>الهاتف:</strong> {{ $unified_office_phone ?? '+0000000000' }}<br>
            <strong>البريد الإلكتروني:</strong> {{ $unified_office_email ?? 'email@example.com' }}
        </div>
        <div class="qr-cell">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($card_number ?? 'LBY000000') }}" alt="QR">
        </div>
        <div class="contact-cell">
            <strong>الشركة المصدرة للبطاقة:</strong> {{ $insurance_company ?? 'شركة التأمين' }}<br>
            <strong>العنوان:</strong> {{ $company_address ?? 'عنوان الشركة' }}<br>
            <strong>صندوق البريد:</strong> {{ $company_box ?? 'صندوق البريد' }}<br>
            <strong>الهاتف:</strong> {{ $company_phone ?? '0000000000' }}<br>
            <strong>البريد الإلكتروني:</strong> {{ $company_email ?? 'info@company.com' }}
        </div>
    </div>

    <table>
        <tr>
            <td class="bg-gray" width="12%">إسم المؤمن له</td>
            <td width="30%">{{ $beneficiary_name ?? 'اسم المؤمن له' }}</td>
            <td class="bg-gray" width="8%">العنوان</td>
            <td width="22%">{{ $beneficiary_address ?? 'العنوان' }}</td>
            <td class="bg-gray" width="8%">الهاتف</td>
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
                    (object)['field_name' => 'vehicle_type', 'field_label' => 'نوع المركبة', 'visible' => true],
                    (object)['field_name' => 'vehicle_nationality', 'field_label' => 'جنسية المركبة', 'visible' => true],
                    (object)['field_name' => 'manufacturing_year', 'field_label' => 'سنة الصنع', 'visible' => true],
                    (object)['field_name' => 'chassis_number', 'field_label' => 'رقم الهيكل (الشاسيه)', 'visible' => true],
                    (object)['field_name' => 'plate_number', 'field_label' => 'رقم اللوحة', 'visible' => true],
                    (object)['field_name' => 'engine_number', 'field_label' => 'رقم المحرك (الموتور)', 'visible' => true],
                    (object)['field_name' => 'usage_purpose', 'field_label' => 'الغرض من الاستعمال', 'visible' => true],
                ];
            }
            $fieldRows = array_chunk($visibleFields->toArray(), 2);
        @endphp

        @foreach ($fieldRows as $row)
            <tr>
                @foreach ($row as $field)
                    <td class="bg-gray" width="12%">{{ $field['field_label'] }}</td>
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
        function convertToArabicDatePdf($date)
        {
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
                $arabicMonth = $arabicMonths[$month] ?? $month;
                return $day . '/' . $arabicMonth . '/' . $year;
            }
            return $date;
        }
    @endphp

    <table>
        <tr class="bg-gray" style="text-align: center;">
            <td rowspan="2" width="10%" style="vertical-align: middle;">سريان التأمين</td>
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

    <table>
        <tr class="bg-gray" style="font-size: 7pt;">
            <td width="8%">البلد</td>
            <td width="46%">عناوين المكاتب الموحدة التي يرجع إليها حامل البطاقة في حالة حدوث حادث او غيره</td>
            <td width="46%">بيان مختصر عن نوعية التغطيات طبقا لقوانين اللزامي في البلد العربية</td>
        </tr>
        @if (!empty($office_info))
            @foreach ($office_info as $office)
                <tr style="font-size: 6pt;">
                    <td>{{ $office['country'] ?? '' }}</td>
                    <td>{{ $office['address'] ?? '' }}</td>
                    <td>{{ $office['coverage'] ?? '' }}</td>
                </tr>
            @endforeach
        @else
            <tr style="font-size: 6pt;">
                <td>تونس</td>
                <td>إقامة شعباني / واد حيدرة. -حيدرة 16033 الجزائر ++21321604507 <br />smg.buat@buat.com.tn 21671845124+</td>
                <td>الأضرار الجسمانية بقيمة محددة والمادية غير محددة</td>
            </tr>
            <tr style="font-size: 6pt;">
                <td>الجزائر</td>
                <td>إقامة شعباني / واد حيدرة. -حيدرة 16033 الجزائر ++21321604507 <br />bua.algerie@gmail.com 21321609295+</td>
                <td>الأضرار البدنية بقيمة محدده والضرارالمادية بقيمة غير محددة</td>
            </tr>
        @endif
    </table>

    <div class="instructions">
        <h4>إرشادات وشروط عامة:</h4>
        <ol>
            <li>يقصد بلفظ سيارة (مركبة) كل مركبة ألية يلزم القانون في البلد المصدر للبطاقة و/أو البلد المزار بوجوب إبرام مالكها لوثيقة التأمين اللزامي له.</li>
            <li>تغطي هذه البطاقة أضرار الشخص الثالث (الغير) الناجمة عن الحوادث التي تسببها المركبة المؤمنة لا تضمن الأضرار اللاحقة بها.</li>
            <li>في حالة زيارة المركبة المؤمن عليها لأي بلد عربي فإن المكتب الموحد يتلقى المطالبات الناجمة عن حوادث المركبات.</li>
            <li>في حالة إنتهاء مدة هذه البطاقة أثناء تواجد المؤمن عليه فعليه الحصول علي وثيقة تأمين محلية.</li>
            <li>لا يحق للمؤمن له إلغاء هذه البطاقة.</li>
            <li>يحق للمؤمن الرجوع على المؤمن له بما أداه من تعويضات في حالة مخالفة القوانين.</li>
        </ol>
    </div>

    <div class="footer">
        <strong>إجمالي القسط والرسوم ( شامل الرسوم والضرائب الحكومية ): {{ $total_premium ?? '0.00' }} د.ل</strong><br>
        <strong>تقوم الشركة المصدرة للبطاقة بمحاسبة مصلحة الضرائب على الرسوم المستحقة.</strong>
    </div>

    <div class="date-row">
        <div class="date-cell">الساعة: {{ $issue_time ?? '09:00 ص' }}</div>
        <div class="date-cell">سنة: {{ $issue_year ?? '2025' }}</div>
        <div class="date-cell">شهر: {{ $issue_month ?? 'يوليو' }}</div>
        <div class="date-cell">الموافق: {{ $issue_weekday ?? 'السبت' }}</div>
        <div class="date-cell">يوم: {{ $issue_day ?? '05' }}</div>
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
