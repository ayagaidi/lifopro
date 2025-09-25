@php
    $nf = fn($v) => number_format((float)$v, 2, '.', ',');
@endphp

@php $i = 1; @endphp
@foreach($rows as $row)
  <tr>
    <td>{{ $i++ }}</td> {{-- الكاونتر --}}
        <td>{{ $row->cards->card_number }}</td>

    <td>{{ \Carbon\Carbon::parse($row->issuing_date)->format('Y-m-d') }}</td>
    <td>
      {{ optional($row->offices)->name }}
      @if(optional($row->offices)->companies)
        / {{ optional($row->offices->companies)->name }}
        @else
        {{$row->companies->name}}/
       الفرع الرئيسي
      @endif
    </td>
    <td>{{ $row->insurance_name ?? '-' }}</td>
    <td>{{ $row->plate_number }}</td>
    <td>{{ $row->chassis_number }}</td>
    <td>{{ $nf($row->insurance_installment) }}</td>
    <td>{{ $nf($row->insurance_tax) }}</td>
    <td>{{ $nf($row->insurance_stamp) }}</td>
    <td>{{ $nf($row->insurance_supervision) }}</td>
    <td>{{ $nf($row->insurance_version) }}</td>
    <td>{{ $nf($row->insurance_total) }}</td>
    <td>{{ $row->insurance_day_from ?? '-' }}</td>
    <td>{{ $row->insurance_day_to ?? '-' }}</td>
    <td>{{ $row->insurance_days_number ?? '-' }}</td>
  </tr>
@endforeach


</tbody>
</table>

<table style="margin-top:8px;">
  <tr>
    <td>الإجماليات:</td>
    <td>القسط: {{ $nf($totals['total_installment']) }}</td>
    <td>الضريبة: {{ $nf($totals['total_tax']) }}</td>
    <td>الطابع: {{ $nf($totals['total_stamp']) }}</td>
    <td>الإشراف: {{ $nf($totals['total_supervision']) }}</td>
    <td>الإصدار: {{ $nf($totals['total_version']) }}</td>
    <td>الإجمالي: {{ $nf($totals['total_insurance']) }}</td>
  </tr>
</table>


</body>
</html>
