@extends('layouts.app')
@section('title', 'إحصائيات إصدارات مستخدمي المكاتب')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('report/officeUsersStats') }}">إحصائيات إصدارات مستخدمي المكاتب</a>
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box-content">
            <canvas id="officeUsersChart" height="500"></canvas>
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
