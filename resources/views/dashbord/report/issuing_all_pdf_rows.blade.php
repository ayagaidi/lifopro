@php
    $nf = fn($v) => number_format((float)$v, 2, '.', ',');
@endphp

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #000;
        padding: 4px;
        font-size: 11pt;
        word-wrap: break-word;
        text-align: center;
        vertical-align: middle;
    }

    thead {
        display: table-header-group; /* تكرار الهيدر تلقائيًا */
    }

    /* ✅ الحل لمشكلة 5 صفوف فقط */
    tr {
        page-break-inside: auto;
    }
</style>

        @php $i = 1; @endphp

        @foreach($rows as $row)
            <tr>
                <td>{{ $i++ }}</td>

                <td>{{ optional($row->cards)->card_number ?? '-' }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($row->issuing_date)->format('Y-m-d H:i') }}
                </td>

                <td>
                    @if($row->offices)
                        {{ $row->offices->name }}
                        @if($row->offices->companies)
                            / {{ $row->offices->companies->name }}
                        @else
                            / الفرع الرئيسي
                        @endif
                    @else
                        {{ optional($row->companies)->name ?? '-' }} / الفرع الرئيسي
                    @endif
                </td>

                <td>{{ $row->insurance_name ?? '-' }}</td>
                <td>{{ $row->plate_number ?? '-' }}</td>
                <td>{{ $row->chassis_number ?? '-' }}</td>

                <td>{{ $nf($row->insurance_installment) }}</td>
                <td>{{ $nf($row->insurance_tax) }}</td>
                <td>{{ $nf($row->insurance_stamp) }}</td>
                <td>{{ $nf($row->insurance_supervision) }}</td>
                <td>{{ $nf($row->insurance_version) }}</td>
                <td>{{ $nf($row->insurance_total) }}</td>

                <td>{{ $row->insurance_day_from ?? '-' }}</td>
                <td>{{ $row->nsurance_day_to ?? '-' }}</td>
                <td>{{ $row->insurance_days_number ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- ================= TOTALS ================= --}}
<table style="margin-top:10px;">
    <tr>
        <td><strong>الإجماليات:</strong></td>
        <td>القسط: {{ $nf($totals['total_installment']) }}</td>
        <td>الضريبة: {{ $nf($totals['total_tax']) }}</td>
        <td>الدمغة: {{ $nf($totals['total_stamp']) }}</td>
        <td>الإشراف: {{ $nf($totals['total_supervision']) }}</td>
        <td>الإصدار: {{ $nf($totals['total_version']) }}</td>
        <td>الإجمالي: {{ $nf($totals['total_insurance']) }}</td>
    </tr>
</table>
