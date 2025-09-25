@extends('layouts.app')
@section('title', 'التقرير المجمع للمكاتب')

@section('content')


    <div id="loadingSpinner" style="display:none; text-align: center; margin: 20px;">
        <img src="https://i.gifer.com/YCZH.gif" alt="جارٍ التحميل..." width="60">
        <p>جارٍ تحميل التقرير...</p>
    </div>

    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">

                <h4 class="box-title"><a href="{{ route('report/officeSummary') }}">ادارة التقارير</a>/ التقرير المجمع للمكاتب
                </h4>

            </div>

          <div class="box-content">
   <form method="GET" action="{{ route('report/officeSummary') }}" class="mb-3">
        @csrf
        <div class="row">
            <div class="form-group col-md-3">
                <label for="start_date" class="control-label">من</label>
                <input name="start_date" id="start_date" type="date"
                       class="form-control @error('start_date') is-invalid @enderror"
                       value="{{ request('start_date') }}" required />
                @error('start_date')
                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-3">
                <label for="end_date" class="control-label">إلى</label>
                <input name="end_date" id="end_date" type="date"
                       class="form-control @error('end_date') is-invalid @enderror"
                       value="{{ request('end_date') }}" required />
                @error('end_date')
                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-6 d-flex align-items-end justify-content-start gap-2">
                <button type="submit" name="action" value="view" class="btn btn-primary">عرض التقرير</button>
                <button type="submit" name="action" value="print" class="btn btn-success">طباعة في صفحة جديدة</button>
            </div>
        </div>
    </form>
</div>

            <div class="row small-spacing">

                <div class="col-md-12">
                    <div class="box-content ">

                        <div class="table-responsive" data-pattern="priority-columns" id="searchs">
                            <table class="table table-bordered table-hover js-basic-example dataTable table-custom "
                                style="cursor: pointer;">
                                <thead>
                                    <tr>
                                        <th>المكتب</th>
                                        <th>الصادرة</th>
                                        <th>الملغاة</th>
                                        <th>صافي القسط</th>
                                        <th>الضريبة</th>
                                        <th>الدمغة</th>
                                        <th>الإشراف</th>
                                        <th>رسوم الإصدار</th>
                                        <th>الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $row)
                                        <tr>
                                            <td>{{ $row->office_name }}</td>
                                            <td>{{ $row->issued_count }}</td>
                                            <td>{{ $row->canceled_count }}</td>
                                            <td>{{ number_format($row->net_premium, 2) }}</td>
                                            <td>{{ number_format($row->tax, 2) }}</td>
                                            <td>{{ number_format($row->stamp, 2) }}</td>
                                            <td>{{ number_format($row->supervision, 2) }}</td>
                                            <td>{{ number_format($row->issuing_fee, 2) }}</td>
                                            <td>{{ number_format($row->total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">لا توجد بيانات للفترة المحددة.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>



        <script>
            document.querySelector('form').addEventListener('submit', function() {
                document.getElementById('loadingSpinner').style.display = 'block';
            });
        </script>

    @endsection
