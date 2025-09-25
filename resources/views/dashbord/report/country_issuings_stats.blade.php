@extends('layouts.app')
@section('title', 'إحصائيات إصدارات البطاقة حسب الدول')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    #chart-container {
        width: 500px;
        height: 500px;
        margin: 40px auto;
    }

    #countryPieChart {
        width: 100% !important;
        height: 100% !important;
    }

    @media (max-width: 768px) {
        #chart-container {
            width: 100%;
            height: auto;
        }
    }
</style>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">
                <a href="{{ route('report/countryissuingsstats') }}">
                    إحصائيات إصدارات البطاقة حسب الدول
                </a>
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box-content">
            <div id="chart-container">
                <canvas id="countryPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('countryPieChart').getContext('2d');
    const countryPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($countryLabels) !!},
            datasets: [{
                label: 'عدد الإصدارات',
                data: {!! json_encode($countryData) !!},
                backgroundColor: [
                    '#2c3e50', '#e74c3c', '#3498db', '#9b59b6', '#f1c40f',
                    '#1abc9c', '#e67e22', '#34495e', '#7f8c8d', '#27ae60',
                    '#d35400', '#2980b9', '#8e44ad', '#16a085', '#c0392b'
                ],
                borderColor: '#fff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: false, // عشان يحترم الحجم الثابت
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold',
                        },
                        padding: 20,
                    }
                },
                title: {
                    display: false
                },
                tooltip: {
                    bodyFont: {
                        size: 14
                    }
                }
            }
        }
    });
</script>

@endsection
