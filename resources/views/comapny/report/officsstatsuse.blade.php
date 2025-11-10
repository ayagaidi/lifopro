@extends('layouts.app')
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
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="offices_id">المكتب:</label>
                        <select name="offices_id" id="offices_id" class="form-control">
                            <option value="">جميع المكاتب</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ $request->offices_id == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="office_users_id">مستخدم المكتب:</label>
                        <select name="office_users_id" id="office_users_id" class="form-control">
                            <option value="">جميع المستخدمين</option>
                            @if($request->offices_id)
                                @foreach($officeUsers->where('offices_id', $request->offices_id) as $user)
                                    <option value="{{ $user->id }}" {{ $request->office_users_id == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                                @endforeach
                            @endif
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
        <div class="box-content" style="padding: 20px;">
            <div style="width: 100%; max-width: 800px; margin: auto;">
                <canvas id="officeUsersChart" height="400"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('officeUsersChart').getContext('2d');

    const labels = {!! json_encode($labels) !!};
    const data = {!! json_encode($data) !!};

    const colors = labels.map((_, i) => {
        const palette = ['#2ecc71', '#3498db', '#f1c40f', '#e67e22', '#1abc9c', '#9b59b6', '#e74c3c', '#34495e', '#16a085'];
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
                    text: 'توزيع الإصدارات حسب مستخدمي المكاتب',
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
                        text: 'المستخدمين',
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

<script>
    $(document).ready(function() {
        // When office is selected, load office users
        $('#offices_id').change(function() {
            var officeId = $(this).val();
            var officeUsersSelect = $('#office_users_id');

            // Reset office users
            officeUsersSelect.html('<option value="">جميع المستخدمين</option>');

            if (officeId) {
                $.ajax({
                    url: '{{ route("company/report/officesuser", ":id") }}'.replace(':id', officeId),
                    type: 'GET',
                    success: function(data) {
                        if (data && data.length > 0) {
                            $.each(data, function(index, user) {
                                officeUsersSelect.append('<option value="' + user.id + '">' + user.username + '</option>');
                            });
                        }
                    },
                    error: function() {
                        alert('خطأ في تحميل مستخدمي المكتب');
                    }
                });
            }
        });
    });
</script>
@endsection
