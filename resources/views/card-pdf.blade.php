<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة التأمين العربية الموحدة - ليبيا</title>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script>
        // Auto-print when page loads
        window.onload = function() {
            @if (($test_mode ?? false) == false)
                // Only auto-print if not in test mode
                setTimeout(function() {
                    window.print();
                }, 500);
            @endif
        };
    </script>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            color: #000;
        }

        .container {
            width: 800px;
            margin: auto;
            background-color: #fff;
            border: 2px solid #000;
            padding: 15px;
            position: relative;
            overflow: hidden;
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

        .instructions {
            margin-top: 10px;
            font-size: 10px;
            line-height: 1.3;
        }

        .instructions h4 {
            margin: 0;
            text-decoration: underline;
        }

        .instructions ol {
            margin: 2px 0;
            padding-right: 20px;
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
            <div style="width: 100px;"><img style="width: 100px;" src="{{ asset('logo.png') }}" alt="Logo"></div>
            <div class="header-center">
                <h1>بطاقة التأمين العربية الموحدة</h1>
                <h2>عن سير السيارات (المركبات) عبر البلاد العربية</h2>
                <h3>للمركبات الليبية</h3>
                <div style="font-weight: bold; font-size: 18px;">{{ $card_number ?? 'LBY/000000' }}</div>
            </div>
            <div style="width: 100px; text-align: left;"><img style="width: 100px;" src="{{ asset('gaif-logo.png') }}"
                    alt="Logo"></div>
        </div>

        <div class="contact-info-wrapper">
            <div class="contact-box red-box">
                <strong>المكتب الموحد:</strong> {{ $unified_office_name ?? 'المكتب الموحد Libyan' }}<br>
                <strong>العنوان:</strong> {{ $unified_office_address ?? 'عنوان المكتب الموحد' }}<br>
                <strong>صندوق البريد:</strong> {{ $unified_office_box ?? 'صندوق البريد' }}<br>
                <strong>الهاتف:</strong> {{ $unified_office_phone ?? '+0000000000' }}<br>
                <strong>البريد الإلكتروني:</strong> {{ $unified_office_email ?? 'email@example.com' }}
            </div>
            <div class="qr-section">
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($card_number ?? 'LBY000000') }}"
                        alt="QR">
                </div>
            </div>

            <div class="contact-box">
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
                <td class="bg-gray" width="10%">العنوان</td>
                <td width="20%">{{ $beneficiary_address ?? 'العنوان' }}</td>
                <td class="bg-gray" width="10%">الهاتف</td>
                <td>{{ $beneficiary_phone ?? '0000000000' }}</td>
            </tr>
        </table>

        <table>
            @php
                // Get visible fields from database
                $visibleFields = [];
                try {
                    $visibleFields = \App\Models\CardFieldVisibility::getVisibleFields();
                } catch (\Exception $e) {
                    // Default fields if table doesn't exist or error
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

                // Group fields into rows of 2 fields each
                $fieldRows = array_chunk($visibleFields->toArray(), 2);
            @endphp

            @foreach ($fieldRows as $row)
                <tr>
                    @foreach ($row as $field)
                        <td class="bg-gray" width="15%">{{ $field['field_label'] }}</td>
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
                    {{-- Add colspan if row has only 1 field --}}
                    @if (count($row) == 1)
                        <td colspan="2"></td>
                    @endif
                </tr>
            @endforeach
        </table>

        @php
            function convertToArabicDate($date)
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
                <td rowspan="2" width="15%" style="vertical-align: middle;">سريان التأمين</td>
                <td>من الساعة</td>
                <td>{{ $insurance_start_time ?? '00:00' }}</td>
                <td>يوم</td>
                <td>{{ $insurance_start_day ?? 'يوم السبت' }}</td>
                <td>الموافق</td>
                <td>{{ convertToArabicDate($insurance_start_date ?? '05/يوليو/2025') }}</td>
            </tr>
            <tr class="bg-gray" style="text-align: center;">
                <td>إلى الساعة</td>
                <td>{{ $insurance_end_time ?? '23:59' }}</td>
                <td>يوم</td>
                <td>{{ $insurance_end_day ?? 'الأحد' }}</td>
                <td>الموافق</td>
                <td>{{ convertToArabicDate($insurance_end_date ?? '31/ديسمبر/2025') }}</td>
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
                    <tr style="font-size: 9px;">
                        <td>{{ $office['country'] ?? '' }}</td>
                        <td>{{ $office['address'] ?? '' }}</td>
                        <td>{{ $office['coverage'] ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr style="font-size: 9px;">
                    <td>تونس</td>
                    <td>إقامة شعباني / واد حيدرة. -حيدرة 16033 الجزائر ++21321604507 <br />smg.buat@buat.com.tn 21671845124+</td>
                    <td>الأضرار الجسمانية بقيمة محددة والمادية غير محددة</td>
                </tr>
                <tr style="font-size: 9px;">
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
                <li>تغطي هذه البطاقة أضرار الشخص الثالث (الغير) الناجمة عن الحوادث التي تسببها المركبة المؤمنة وفقا لقانون البلد المزار، ولا تضمن الأضرار اللاحقة بها أيا كان سببها.</li>
                <li>في حالة زيارة المركبة المؤمن عليها بموجب هذه البطاقة لأي بلد عربي تشمله هذه البطاقة فإن المكتب الموحد في هذا البلد يتلقى المطالبات الناجمة عن حوادث المركبات...</li>
                <li>في حالة إنتهاء مدة هذه البطاقة أثناء تواجد المؤمن له في البلد المزار فإن عليه الحصول علي وثيقة تأمين محلية.</li>
                <li>لا يحق للمؤمن له إلغاء هذه البطاقة.</li>
                <li>يحق للمؤمن الرجوع على المؤمن له بما أداه من تعويضات في حالة مخالفة القوانين النافذة.</li>
            </ol>
        </div>

        <div class="footer">
            <strong>إجمالي القسط والرسوم ( شامل الرسوم والضرائب الحكومية ): {{ $total_premium ?? '0.00' }} د.ل</strong><br>
            <strong>تقوم الشركة المصدرة للبطاقة بمحاسبة مصلحة الضرائب على الرسوم المستحقة.</strong><br>

            <div class="date-container">
                <div class="date-item">
                    <span class="label">الساعة :</span>
                    <span class="value">{{ $issue_time ?? '09:00 ص' }}</span>
                </div>
                <div class="date-item">
                    <span class="label">سنة :</span>
                    <span class="value">{{ $issue_year ?? '2025' }}</span>
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
                    <span class="value">{{ $issue_day ?? '05' }}</span>
                </div>
            </div>
        </div>

        <div class="warning">
            <strong>هام : أي كشط أو شطب أو تعديل في هذه الصفحة يبطل البطاقة وتعد لاغية</strong>
        </div>

        <div class="warning" style="text-align: center !important;border-top:none !important">
            @if ($test_mode ?? false)
                <small>This document is generated for testing purpose only. This is not a valid document</small>
            @endif
        </div>
    </div>

</body>
</html>