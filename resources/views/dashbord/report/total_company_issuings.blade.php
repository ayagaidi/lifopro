@extends('layouts.app')
@section('title', 'إحصائيات عدد إصدارات الشركات (بمكاتبها)')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('report/totalcompanyissuingstats') }}">عدد إصدارات الشركات بمكاتبها</a>
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box-content">
            <canvas id="companyTotalChart" height="500"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('companyTotalChart').getContext('2d');

    const colors = [
        '#2ecc71', '#3498db', '#f1c40f', '#e67e22', '#1abc9c',
        '#9b59b6', '#e74c3c', '#34495e', '#7f8c8d', '#d35400',
        '#27ae60', '#2980b9', '#8e44ad', '#16a085', '#c0392b',
        '#f39c12', '#bdc3c7', '#95a5a6', '#ff6384', '#36a2eb'
    ];

    const labels = {!! json_encode($companyLabels) !!};
    const data = {!! json_encode($companyData) !!};

    const backgroundColors = labels.map((_, i) => colors[i % colors.length]);
    const borderColors = backgroundColors.map(c => c);

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'عدد الإصدارات الكلي',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'إجمالي عدد الإصدارات لكل شركة (بمكاتبها)',
                    font: {
                        size: 22,
                        weight: 'bold'
                    }
                },
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
                        text: 'الشركات'
                    }
                }
            }
        }
    });
</script>
@endsection
