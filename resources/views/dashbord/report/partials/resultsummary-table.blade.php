@if($issuing->count() > 0)
<div class="table-responsive">
    <table id="datatable1" class="table table-bordered table-hover table-custom">
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
              
                
                <th>رقم اللوحة</th>
                <th>رقم الهيكل</th>

            </tr>
        </thead>
        <tbody>
            @php $count = 1; @endphp
            @foreach($issuing as $item)
                @php
                    $user = $item->company_users->username ?? $item->office_users->username ?? 'غير محدد';
                    $companies = $item->companies->name ?? 'غير محدد';
                    $offices = $item->offices->name ?? 'الفرع الرئيسي';
                @endphp
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $item->cards?->card_number ?? '' }}</td>
                    <td>{{ $user }}</td>
                    <td>{{ $companies }}</td>
                    <td>{{ $offices }}</td>
                    <td>{{ $item->insurance_name }}</td>
                    <td>{{ $item->issuing_date }}</td>
                    <td>{{ $item->insurance_installment }}</td>
                    <td>{{ $item->insurance_tax }}</td>
                    <td>{{ $item->insurance_stamp }}</td>
                    <td>{{ $item->insurance_supervision }}</td>
                    <td>{{ $item->insurance_version }}</td>
                    <td>{{ $item->insurance_total }}</td>
                    
                    <td>{{ $item->plate_number ?? '' }}</td>
                    <td>{{ $item->chassis_number ?? '' }}</td>

                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="12" style="text-align: right;"><strong>الإجمالي:</strong></td>
                <td><strong>{{ $total ?? 0 }}</strong></td>
                <td colspan="8"></td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="mt-3">
    {{ $issuing->links() }}
</div>
@else
<div class="alert alert-warning">لا توجد نتائج مطابقة للبحث</div>
@endif
