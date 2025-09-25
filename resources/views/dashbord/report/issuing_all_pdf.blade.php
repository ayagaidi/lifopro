<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <style>
    @page { margin: 15mm; }
    @font-face {
      font-family: 'amiri';
      font-style: normal;
      font-weight: normal;
      src: url("{{ storage_path('app/fonts/Amiri-Regular.ttf') }}") format('truetype');
    }
    @font-face {
      font-family: 'amiri';
      font-style: bold;
      font-weight: bold;
      src: url("{{ storage_path('app/fonts/Amiri-Bold.ttf') }}") format('truetype');
    }
    body { font-family: 'amiri', DejaVu Sans, sans-serif; direction: rtl; }

    /* ===== HEADER FIX ===== */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      font-size: 12px;
      direction: rtl;
      border-bottom: 1px solid #000; /* optional separator */
      padding-bottom: 8px;
    }

    .header .side {
      width: 30%;
    }

    .header .center {
      width: 40%;
      text-align: center;
    }

    .header .center img {
      max-width: 90px;
      display: block;
      margin: 0 auto 5px;
    }

    .header .side .title {
      text-align: right;
    }
    .header .center img {
  max-width: 60px;   /* العرض الأقصى */
  height: auto;      /* يحافظ على التناسب */
  display: block;
  margin: 0 auto 5px;
}


    /* ===== TABLE STYLES ===== */
    table { width:100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 4px; font-size: 11px; text-align: center; }
    .title { font-weight: bold; margin: 4px 0; }
    .totals { max-width: 350px; margin: 10px 0 0 auto; border:1px solid #000; }
    .totals td, .totals th { padding: 4px; border:1px solid #000; text-align:right; }
    .right { text-align: right; }
    .nowrap { white-space: nowrap; }
  </style>
</head>
<body>

  {{-- رأس التقرير --}}
  <div class="header">
    <div class="side">
      <div class="title">العنوان: الإتحاد الليبي للتأمين</div>
      <div class="title">البريد: info@insurancefed.ly</div>
      <div class="title">الموقع: www.insurancefed.ly</div>
    </div>

    <div class="center">
      <img src="{{ asset('logopdf.png') }}"  alt="Logo">
      <div class="title">دولــة لـيـبـيـا</div>
      <div class="title">الاتـــحـاد الليبي للتأمين</div>
    </div>

    <div class="side">
      <div class="title">التاريخ: {{ $meta['today'] }}</div>
      <div class="title">تم إنشاؤه من قبل: {{ $meta['username'] }}</div>
      <div class="title">الفترة: {{ $meta['from'] }} → {{ $meta['to'] }}</div>
    </div>
  </div>

  {{-- جدول البيانات --}}
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>رقم البطاقة</th>
        <th>المُصدر</th>
        <th>الشركة</th>
        <th>المكتب</th>
        <th>المؤمن له</th>
        <th>تاريخ الاصدار</th>
        <th>صافي القسط</th>
        <th>الضريبة</th>
        <th>رسم الدمغة</th>
        <th>الإشراف</th>
        <th>الإصدار</th>
        <th>الإجمالي</th>
        <th>مدة التأمين من</th>
        <th>مدة التأمين إلى</th>
        <th>عدد الأيام</th>
        <th>نوع المركبة</th>
        <th>رقم اللوحة</th>
        <th>رقم الهيكل</th>
        <th>رقم المحرك</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $i => $item)
        @php
          $companies = $item->offices->companies->name ?? ($item->companies->name ?? 'الإتحاد الليبي للتأمين');
          $offices   = $item->offices->name ?? 'الفرع الرئيسي';
          $user      = $item->office_users->username ?? ($item->company_users->username ?? '-');
          $card      = optional($item->cards)->card_number ?? $item->id;
          $cars      = optional($item->cars)->name ?? ($item->cars_id ?? '-');
        @endphp
        <tr>
          <td class="nowrap">{{ $i+1 }}</td>
          <td class="nowrap">{{ $card }}</td>
          <td>{{ $user }}</td>
          <td>{{ $companies }}</td>
          <td>{{ $offices }}</td>
          <td>{{ $item->insurance_name ?? '-' }}</td>
          <td class="nowrap">{{ $item->issuing_date ?? '-' }}</td>
          <td>{{ number_format((float)$item->insurance_installment, 3, '.', '') }}</td>
          <td>{{ number_format((float)$item->insurance_tax, 3, '.', '') }}</td>
          <td>{{ number_format((float)$item->insurance_stamp, 3, '.', '') }}</td>
          <td>{{ number_format((float)$item->insurance_supervision, 3, '.', '') }}</td>
          <td>{{ number_format((float)$item->insurance_version, 3, '.', '') }}</td>
          <td>{{ number_format((float)$item->insurance_total, 3, '.', '') }}</td>
          <td class="nowrap">{{ $item->insurance_day_from ?? '-' }}</td>
          <td class="nowrap">{{ $item->insurance_day_to ?? '-' }}</td>
          <td>{{ $item->insurance_days_number ?? '-' }}</td>
          <td>{{ $cars }}</td>
          <td>{{ $item->plate_number ?? '-' }}</td>
          <td>{{ $item->chassis_number ?? '-' }}</td>
          <td>{{ $item->motor_number ?? '-' }}</td>
        </tr>
      @empty
        <tr><td colspan="20" style="text-align:center">لا توجد بيانات ضمن الفترة المختارة</td></tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr>
        <th colspan="7" style="text-align:center;">الإجمالي (كل النتائج)</th>
        <th>{{ number_format($totals['total_installment'], 3, '.', '') }}</th>
        <th>{{ number_format($totals['total_tax'], 3, '.', '') }}</th>
        <th>{{ number_format($totals['total_stamp'], 3, '.', '') }}</th>
        <th>{{ number_format($totals['total_supervision'], 3, '.', '') }}</th>
        <th>{{ number_format($totals['total_version'], 3, '.', '') }}</th>
        <th>{{ number_format($totals['total_insurance'], 3, '.', '') }}</th>
        <th colspan="8"></th>
      </tr>
    </tfoot>
  </table>
</body>
</html>
