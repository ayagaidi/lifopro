@extends('comapny.app')
@section('title', 'إحصائيات إصدارات مستخدمي المكاتب')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('company/report/officeUsersStats') }}">إحصائيات إصدارات مستخدمي المكاتب</a>
            </h4>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="col-md-12">
        <div class="box-content" style="padding: 20px;">
            <form method="GET" action="{{ route('company/report/officeUsersStats') }}" class="">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fromdate">من تاريخ:</label>
                        <input type="date" name="fromdate" id="fromdate" class="form-control" value="{{ $request->fromdate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="todate">إلى تاريخ:</label>
                        <input type="date" name="todate" id="todate" class="form-control" value="{{ $request->todate }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="offices_id">المكتب:</label>
                        <select name="offices_id" id="offices_id" class="form-control">
                            <option value="">جميع المكاتب</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ $request->offices_id == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">فلترة</button>
                        <a href="{{ route('company/report/officeUsersStats') }}" class="btn btn-secondary">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-12" style="margin-top: 20px;">
        <div class="box-content">
            <canvas id="officeUsersChart" height="200"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('officeUsersChart').getContext('2d');

    const labels = {!! json_encode($labels) !!};
    const data = {!! json_encode($data) !!};

    const colors = labels.map((_, i) => {
        const palette = ['#e74c3c', '#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#1abc9c', '#e67e22', '#34495e', '#16a085'];
        return palette[i % palette.length];
    });

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'عدد الإصدارات',
                data: data,
                backgroundColor: colors,
                borderColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
               
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'عدد الإصدارات'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'المستخدمين'
                    }
                }
            }
        }
    });
</script>
@endsection
