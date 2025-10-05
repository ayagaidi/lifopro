@extends('comapny.app')
@section('title', 'احصائيات الإصدارات لكل مكتب ')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title"><a href="{{ route('company/report/officeStats') }}">احصائيات الإصدارات لكل مكتب</a></h4>
        </div>
    </div>

    <div class="col-md-12" style="margin-top: 50px;">
        <div class="box-content" style="padding: 20px;">
            <div style="width: 80%; max-width: 1200px; margin: auto;">
                <canvas id="officeChart" height="600"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('officeChart').getContext('2d');

                const officeChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($officeLabels) !!},
                        datasets: [{
                            label: 'عدد الإصدارات',
                            data: {!! json_encode($officeData) !!},
                            backgroundColor: 'rgba(30, 90, 150, 0.9)',      // أزرق داكن
                            borderColor: 'rgba(20, 60, 100, 1)',             // حدود أزرق داكن أقوى
                            borderWidth: 2,
                            hoverBackgroundColor: 'rgba(20, 60, 100, 1)',   // أغمق عند التحويم
                            hoverBorderColor: 'rgba(10, 30, 50, 1)',
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
                            legend: {
                                display: true,
                                labels: {
                                    font: {
                                        size: 16
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'توزيع الإصدارات حسب المكاتب',
                                font: {
                                    size: 24,
                                    weight: 'bold'
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
                                    text: 'المكاتب',
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
        </div>
    </div>
</div>
@endsection
