<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة التأمين العربية الموحدة</title>
    <style>
        /* إعدادات الطباعة الدقيقة */
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f0f0f0;
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            color: #000000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* الحاوية الرئيسية بمقاس A4 */
        .card-container {
            width: 21cm;
            min-height: 29.7cm;
            margin: 0.5cm auto;
            background: #ffffff;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            position: relative;
            border: 1px solid #cccccc;
        }

        /* محتوى البطاقة */
        .card-content {
            padding: 0.7cm 0.5cm 0.5cm 0.5cm;
            height: 100%;
            position: relative;
        }

        /* منطقة الشعار العلوي */
        .top-logo {
            position: absolute;
            right: 0.5cm;
            top: 0.3cm;
            text-align: center;
        }

        .company-logo {
            width: 3cm;
            height: 1.5cm;
            border: 1px solid #000000;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 8pt;
            color: #666666;
        }

        .company-name {
            font-size: 9pt;
            font-weight: bold;
            margin-top: 0.1cm;
        }

        /* منطقة QR */
        .qr-area {
            position: absolute;
            left: 0.5cm;
            top: 0.5cm;
            text-align: center;
        }

        .qr-code {
            width: 2cm;
            height: 2cm;
            border: 1px dashed #666666;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .qr-label {
            font-size: 7pt;
            color: #666666;
            margin-top: 0.1cm;
        }

        /* الرأس */
        .header {
            text-align: center;
            margin: 0 2cm 0.3cm 2cm;
            padding-bottom: 0.2cm;
            border-bottom: 1px solid #000000;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            color: #000000;
        }

        .header h2 {
            font-size: 11pt;
            font-weight: normal;
            margin: 0.1cm 0 0 0;
            padding: 0;
            color: #000000;
        }

        /* الجداول */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.3cm;
            font-size: 9pt;
            table-layout: fixed;
        }

        .data-table th {
            background: #d9d9d9 !important;
            border: 1px solid #000000;
            padding: 0.12cm 0.2cm;
            text-align: right;
            font-weight: bold;
            color: #000000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .data-table td {
            border: 1px solid #000000;
            padding: 0.12cm 0.2cm;
            text-align: right;
            color: #000000;
        }

        .data-table .col-separator {
            background: #ffffff !important;
            border: none;
            width: 0.2cm;
            padding: 0;
        }

        /* العناوين الرئيسية */
        .section-title {
            background: #d9d9d9;
            border: 1px solid #000000;
            padding: 0.12cm 0.3cm;
            margin: 0.2cm 0 0.1cm 0;
            font-weight: bold;
            text-align: center;
            font-size: 10pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* خانات الاختيار */
        .countries-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 0.2cm;
            margin: 0.2cm 0;
            padding: 0.2cm;
            border: 1px solid #000000;
        }

        .country-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .country-name {
            font-size: 9pt;
        }

        .checkbox-custom {
            display: inline-block;
            width: 0.35cm;
            height: 0.35cm;
            border: 1px solid #000000;
            margin-left: 0.1cm;
            position: relative;
        }

        .checkbox-custom.checked:after {
            content: "✓";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 7pt;
            color: #000000;
            font-weight: bold;
        }

        /* جدول المكاتب الموحدة */
        .offices-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.2cm;
            font-size: 8pt;
            table-layout: fixed;
        }

        .offices-table th,
        .offices-table td {
            border: 1px solid #000000;
            padding: 0.12cm 0.2cm;
            vertical-align: top;
        }

        .offices-table th {
            background: #d9d9d9;
            font-weight: bold;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .offices-table td {
            text-align: right;
        }

        .underline {
            text-decoration: underline;
        }

        /* الشروط */
        .terms {
            margin-top: 0.3cm;
            font-size: 8pt;
            text-align: justify;
        }

        .terms ol {
            margin: 0.1cm 0;
            padding-right: 1.2cm;
        }

        .terms li {
            margin-bottom: 0.15cm;
        }

        /* التذييل */
        .footer {
            margin-top: 0.3cm;
            padding-top: 0.2cm;
            border-top: 1px solid #000000;
            font-size: 9pt;
        }

        .total-amount {
            font-weight: bold;
            margin: 0.1cm 0;
            font-size: 10pt;
            color: #000000;
        }

        .warning-box {
            background: #ffe6e6;
            border: 1px solid #ff0000;
            padding: 0.15cm;
            margin-top: 0.3cm;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            color: #ff0000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* التواقيع */
        .signature-area {
            margin-top: 0.4cm;
            padding-top: 0.2cm;
            border-top: 1px dashed #000000;
            text-align: center;
            font-size: 9pt;
        }

        /* منطقة رقم البطاقة */
        .card-number {
            position: absolute;
            left: 0.5cm;
            bottom: 0.5cm;
            font-size: 9pt;
            color: #000000;
            font-weight: bold;
        }

        /* منطقة إصدار البطاقة */
        .issue-date {
            position: absolute;
            right: 0.5cm;
            bottom: 0.5cm;
            font-size: 9pt;
            color: #000000;
        }

        /* التنسيقات العامة */
        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-small {
            font-size: 8pt;
        }

        /* الطباعة */
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }

            .card-container {
                width: 100%;
                height: 100%;
                margin: 0;
                box-shadow: none;
                border: none;
                page-break-after: always;
            }

            .no-print {
                display: none;
            }

            .card-content {
                padding: 0.7cm 0.5cm 0.5cm 0.5cm;
            }
        }

        /* معلومات المركبة الجانبية */
        .vehicle-side-info {
            position: absolute;
            left: 0.5cm;
            top: 3.5cm;
            width: 2.5cm;
        }

        .license-plate {
            border: 2px solid #000000;
            padding: 0.2cm;
            text-align: center;
            margin-bottom: 0.3cm;
            background: #f0f0f0;
        }

        .plate-number {
            font-size: 12pt;
            font-weight: bold;
            color: #000000;
        }

        .vehicle-type {
            font-size: 8pt;
            color: #666666;
        }

        /* أزرار التحكم */
        .controls {
            position: fixed;
            top: 20px;
            left: 20px;
            background: white;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .controls button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12pt;
        }

        .controls button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    
    <div class="card-container">
        <div class="card-content">
            <!-- منطقة الشعار العلوي -->
            <div class="top-logo">
                <div class="company-logo" id="companyLogo">
                    شعار<br>الشركة
                </div>
                <div class="company-name">شركة العين الأهلية للتأمين</div>
            </div>

            <!-- منطقة QR -->
            <div class="qr-area">
                <div class="qr-code" id="qrCode">
                    QR Code
                </div>
                <div class="qr-label">رمز الاستجابة السريعة</div>
            </div>

            <!-- معلومات المركبة الجانبية -->
            <div class="vehicle-side-info">
                <div class="license-plate">
                    <div class="plate-number">5.238904</div>
                    <div class="vehicle-type">لوحة المركبة</div>
                </div>
                <div class="text-small">
                    LBY/6575061<br>
                    طرابلس
                </div>
            </div>

            <!-- رأس البطاقة -->
            <div class="header">
                <h1>بطاقة التأمين العربية الموحدة</h1>
                <h2>عن سير السيارات (المركبات) عبر البلاد العربية</h2>
                <h2>للمركبات النبية</h2>
            </div>

            <!-- جدول الشركة والمكتب الموحد -->
            <table class="data-table">
                <tr>
                    <th width="15%"></th>
                    <th width="3%">:</th>
                    <th width="20%">الشركة المصدرة للبطاقة</th>
                    <td width="22%">الشركة العالمية للتأمين</td>
                    <td class="col-separator"></td>
                    <th width="3%">:</th>
                    <th width="20%">المكتب الموحد</th>
                    <td width="17%">المكتب الموحد الثاني</td>
                </tr>
                <tr>
                    <th>العنوان</th>
                    <td>:</td>
                    <td>20 : شارع رمضان سوق الجمعة - طرابلس</td>
                    <td>ميدان الجزائر</td>
                    <td class="col-separator"></td>
                    <td>:</td>
                    <th>العنوان</th>
                    <td></td>
                </tr>
                <tr>
                    <th>صندوق البريد</th>
                    <td>:</td>
                    <td>4784</td>
                    <td></td>
                    <td class="col-separator"></td>
                    <td>:</td>
                    <th>صندوق البريد</th>
                    <td></td>
                </tr>
                <tr>
                    <th>الهاتف</th>
                    <td>:</td>
                    <td>00218213504529</td>
                    <td>092000000</td>
                    <td class="col-separator"></td>
                    <td>:</td>
                    <th>الهاتف</th>
                    <td></td>
                </tr>
                <tr>
                    <th>الفاكس</th>
                    <td>:</td>
                    <td></td>
                    <td></td>
                    <td class="col-separator"></td>
                    <td>:</td>
                    <th>الفاكس</th>
                    <td></td>
                </tr>
                <tr>
                    <th>البريد الإلكتروني</th>
                    <td>:</td>
                    <td></td>
                    <td></td>
                    <td class="col-separator"></td>
                    <td>:</td>
                    <th>البريد الإلكتروني</th>
                    <td></td>
                </tr>
            </table>

            <!-- جدول المؤمن له -->
            <table class="data-table">
                <tr>
                    <th width="20%">اسم المؤمن له</th>
                    <td width="3%">:</td>
                    <td width="27%">محمد السيرك الصويمي</td>
                    <th width="15%">العنوان</th>
                    <td width="3%">:</td>
                    <td width="32%"></td>
                </tr>
            </table>

            <!-- جدول معلومات المركبة -->
            <table class="data-table">
                <tr>
                    <th width="15%">إماراتية</th>
                    <td width="3%">:</td>
                    <td width="12%"></td>
                    <th width="15%">جنسية المركبة</th>
                    <td width="3%">:</td>
                    <td width="15%">النبية</td>
                    <th width="15%">نوع المركبة</th>
                    <td width="3%">:</td>
                    <td width="17%">كها</td>
                </tr>
                <tr>
                    <th>سنة الصنع</th>
                    <td>:</td>
                    <td>2015</td>
                    <th>رقم الهيكل (الشاسيه)</th>
                    <td>:</td>
                    <td>53884098</td>
                    <th>رقم اللوحة</th>
                    <td>:</td>
                    <td>5.238904</td>
                </tr>
                <tr>
                    <th>رقم المحرك (الموتور)</th>
                    <td>:</td>
                    <td>0000</td>
                    <th>الغرض من الاستعمال</th>
                    <td>:</td>
                    <td>خاصة</td>
                    <td colspan="3"></td>
                </tr>
            </table>

            <!-- جدول مدة التأمين -->
            <table class="data-table">
                <tr>
                    <th width="10%">من</th>
                    <td width="3%">:</td>
                    <td width="13%">2025/11/24 الموافق</td>
                    <td width="3%">:</td>
                    <td width="8%">يوم</td>
                    <td width="3%">:</td>
                    <td width="12%">من الساعة</td>
                    <td width="3%">:</td>
                    <td width="10%">02:44 PM</td>
                    <th width="15%">سريان التأمين</th>
                    <td width="20%"></td>
                </tr>
                <tr>
                    <th>إلى</th>
                    <td>:</td>
                    <td>2025/11/30 الموافق</td>
                    <td>:</td>
                    <td>يوم</td>
                    <td>:</td>
                    <td>إلى الساعة</td>
                    <td>:</td>
                    <td>11:59 PM</td>
                    <td colspan="2"></td>
                </tr>
            </table>

            <!-- البلاد التي تسري فيها البطاقة -->
            <div class="section-title">البلاد التي تسري فيها البطاقة</div>

            <div class="countries-grid">
                <div class="country-item">
                    <span class="country-name">العراق</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">سورية</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">الجزائر</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">تونس</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">البحرين</span>
                    <div class="checkbox-custom checked"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">الأردن</span>
                    <div class="checkbox-custom checked"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">مصر</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">ليبيا</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">لبنان</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">الكويت</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">قطر</span>
                    <div class="checkbox-custom"></div>
                </div>
                <div class="country-item">
                    <span class="country-name">عُمان</span>
                    <div class="checkbox-custom checked"></div>
                </div>
            </div>

            <!-- جدول المكاتب الموحدة -->
            <table class="offices-table">
                <tr>
                    <th width="15%">البلد</th>
                    <th width="45%">عناوين المكاتب الموحدة التي يرجع إليها حامل البطاقة في حالة حدوث حادث او غيره التي يرجع إليها حامل البطاقة في حالة حدوث حادث أو غيره</th>
                    <th width="40%">بيان مختصر عن نوعية التغطيات طبقا لقوانين الإلزامي في البلاد العربية</th>
                </tr>
                <tr>
                    <td class="text-center">تونس</td>
                    <td>
                        21671841784-<br>
                        buat@buat.com.tn<br>
                        21571845124+
                    </td>
                    <td>الأضرار الجسدية بقيمة محددة والأضرار المادية غير محددة</td>
                </tr>
            </table>

            <!-- الإرشادات والشروط -->
            <div class="section-title underline">إرشادات وشروط عامة</div>

            <div class="terms">
                <ol>
                    <li>يقصد بلفظ سيارة (مركبة) كل مركبة ألية يلزم القانون في البلد المصدر للبطاقة و/أو البلد المزار بوجوب إبرام مالكها لوثيقة التأمين الإلزامي لها.</li>
                    <li>تغطي هذه البطاقة أضرار الشخص الثالث (الغير) الناجمة عن الحوادث التي تسببها المركبة المؤمنة وفقا لقانون البلد المزار، ولا تضمن الأضرار اللاحقة بها أيا كان سببها.</li>
                    <li>في حالة زيارة المركبة المؤمن عليها بموجب هذه البطاقة لأي بلد عربي تشمله هذه البطاقة فإن المكتب الموحد في هذا البلد يتلقي المطالبات الناجمة عن حوادث المركبات التي تقع في الدولة الكائن فيها هذا المكتب.</li>
                    <li>يلتزم المؤمن له بالإتصال بالمكتب الموحد في البلد الذي وقع فيه الحادث، وفي حالة وقوع الحادث في المنطقة الحدودية (المسافة الجغرافية الواقعة فيما بين الحدود الرسمية لبلدين متاجورين) الإتصال بالمكتب الموحد التابع للبلد الذي خرجت منه المركبة أو المكتب الموحد في بلد السلطة الرسمية التي تتولي السيطرة والتحقيق في الحادث. ويعتبر المكتب الموحد موطنا مختارا لكافة المكاتب العربية الموحدة الأخرى والشركات الأعضاء بها.</li>
                    <li>في حالة إنتهاء مدة هذه البطاقة أثناء تواجد المؤمن له في البلد المزار فإن عليه الحصول علي وثيقة تأمين محلية من البلد المذكور.</li>
                    <li>لا يجق للمؤمن له إلغاء هذه البطاقة.</li>
                    <li>يحق للمؤمن الرجوع على المؤمن له بما أداه من تعويضات في حالة مخالفة المؤمن له للقوانين النافذة في البلد المصدر للبطاقة و / أو البلد المزار.</li>
                </ol>
            </div>

            <!-- التذييل -->
            <div class="footer">
                <div class="text-bold">إجمالي القسط و الرسوم (شامل الرسوم والضرائب الحكومية):</div>
                <div class="total-amount">60.000</div>
                <div>تقوم الشركة المصدرة للبطاقة بمحاسبة مصلحة الضرائب على الرسوم المستحقة.</div>

                <div class="signature-area">
                    تم إصدار البطاقة بتاريخ: 24 نوفمبر 2025
                </div>
            </div>

            <!-- التحذير -->
            <div class="warning-box">
                هــام : أي كشـط أو شطـب أو تعديـل فـي هـذه الصفحـة يبطـل البطاقـة وتـعـد لاغيـة.
            </div>

            <!-- رقم البطاقة -->
            <div class="card-number">
                رقم البطاقة: LBY/6575061
            </div>

            <!-- تاريخ الإصدار -->
            <div class="issue-date">
                تاريخ الإصدار: 2025/11/24
            </div>
        </div>
    </div>

    <script>
        // دالة لتبديل QR
        function toggleQR() {
            const qrCode = document.getElementById('qrCode');
            const qrImages = [
                '<div style="background:black; color:white; padding:5px; font-size:6pt;">QR CODE</div>',
                '<div style="background:#333; color:white; padding:5px; font-size:6pt;">🟧 QR بطاقة</div>',
                '<div style="background:linear-gradient(45deg,#ff6b00,#ffa500); color:white; padding:5px; font-size:6pt;">بطاقة التأمين</div>'
            ];

            let currentQR = qrCode.getAttribute('data-qr') || '0';
            let nextQR = (parseInt(currentQR) + 1) % qrImages.length;

            qrCode.innerHTML = qrImages[nextQR];
            qrCode.setAttribute('data-qr', nextQR);
        }

        // دالة لتبديل الشعار
        function toggleLogo() {
            const logo = document.getElementById('companyLogo');
            const logos = [
                'شعار<br>الشركة',
                '<div style="background:#ff6b00; color:white; padding:3px; font-size:7pt;">عين للتأمين</div>',
                '<div style="background:#003366; color:white; padding:3px; font-size:7pt;">العين الأهلية</div>',
                '<div style="border:2px solid #ff6b00; padding:3px; font-size:7pt;">🚗 تأمين</div>'
            ];

            let currentLogo = logo.getAttribute('data-logo') || '0';
            let nextLogo = (parseInt(currentLogo) + 1) % logos.length;

            logo.innerHTML = logos[nextLogo];
            logo.setAttribute('data-logo', nextLogo);
        }

        // دالة لتحديث الصفحة
        function refreshPage() {
            location.reload();
        }

        // تهيئة QR وشعار افتراضي
        document.addEventListener('DOMContentLoaded', function() {
            const qrCode = document.getElementById('qrCode');
            qrCode.innerHTML = '<div style="background:black; color:white; padding:5px; font-size:6pt;">QR CODE</div>';
            qrCode.setAttribute('data-qr', '0');

            const logo = document.getElementById('companyLogo');
            logo.setAttribute('data-logo', '0');

            // إضافة تأثيرات عند التحميل
            setTimeout(() => {
                document.querySelector('.card-container').style.opacity = '1';
            }, 100);
        });
    </script>

    <!-- تعليمات الطباعة -->

</body>
</html>