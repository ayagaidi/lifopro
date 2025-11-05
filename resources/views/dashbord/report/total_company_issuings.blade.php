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

    <!-- فلتر التاريخ -->
    <div class="col-md-12">
        <div class="box-content">
            <form method="GET" action="{{ route('report/totalcompanyissuingstats') }}" class="form-inline">
                <div class="form-group">
                    <label for="fromdate">من تاريخ:</label>
                    <input type="date" name="fromdate" id="fromdate" class="form-control" value="{{ $request->fromdate ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="todate">إلى تاريخ:</label>
                    <input type="date" name="todate" id="todate" class="form-control" value="{{ $request->todate ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">فلترة</button>
                <a href="{{ route('report/totalcompanyissuingstats') }}" class="btn btn-secondary">إعادة تعيين</a>
            </form>
        </div>
    </div>

    <div class="col-md-12" style="margin-top: 20px;">
        <div class="box-content" style="padding: 20px;">
            <div style="width: 100%; max-width: 800px; margin: auto;">
                <canvas id="companyTotalChart" height="400"></canvas>
            </div>
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
                borderWidth: 2,
                barPercentage: 0.7,
                categoryPercentage: 0.7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'nearest',
                intersect: true,
            },
            hover: {
                onHover: function(event, chartElement) {
                    event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                }
            },
            animation: {
                duration: 300,
                easing: 'easeOutQuad'
            },
            plugins: {
                title: {
                    display: true,
                    text: 'إجمالي عدد الإصدارات لكل شركة (بمكاتبها)',
                    font: {
                        size: 24,
                        weight: 'bold'
                    }
                },
                legend: {
                    display: true,
                    labels: {
                        font: {
                            size: 16
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    bodyFont: {
                        size: 16
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'عدد الإصدارات',
                        font: {
                            size: 18,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        font: {
                            size: 14
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'الشركات',
                        font: {
                            size: 18,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        font: {
                            size: 14
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        },
        plugins: [{
            id: 'hoverZoom',
            afterDatasetDraw(chart) {
                const {ctx} = chart;
                const activeElements = chart.getActiveElements();

                if (activeElements.length === 0) return;

                const active = activeElements[0];
                const datasetIndex = active.datasetIndex;
                const index = active.index;
                const meta = chart.getDatasetMeta(datasetIndex);
                const bar = meta.data[index];

                if (!bar) return;

                ctx.save();

                const scale = 1.3;
                const barWidth = bar.width * scale;
                const barHeight = bar.height * scale;

                const x = bar.x - (barWidth - bar.width) / 2;
                const y = bar.y - barHeight + bar.height;

                ctx.shadowColor = 'rgba(0,0,0,0.3)';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 4;

                ctx.fillStyle = bar.options.backgroundColor;
                ctx.strokeStyle = bar.options.borderColor;
                ctx.lineWidth = bar.options.borderWidth;

                ctx.beginPath();
                ctx.rect(x, y, barWidth, barHeight);
                ctx.fill();
                ctx.stroke();

                ctx.restore();
            }
        }]
    });
</script>
@endsection
