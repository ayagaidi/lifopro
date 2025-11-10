@extends('layouts.app')
@section('title', 'احصائيات الإصدارات لكل مكتب ')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title"><a href="{{ route('report/officeStats') }}">احصائيات الإصدارات لكل مكتب</a></h4>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="col-md-12">
        <div class="box-content" style="padding: 20px;">
            <form method="GET" action="{{ route('report/officeStats') }}" class="">
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
                        <label for="companies_id">الشركة:</label>
                        <select name="companies_id" id="companies_id" class="form-control">
                            <option value="">جميع الشركات</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ $request->companies_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
  <div class="form-group col-md-3">                        <label for="offices_id">المكتب:</label>
                        <select name="offices_id" id="offices_id" class="form-control">
                            <option value="">جميع المكاتب</option>
                           
                        </select>
                    </div>
  <div class="form-group col-md-3">                        <label for="office_users_id">مستخدم المكتب:</label>
                        <select name="office_users_id" id="office_users_id" class="form-control">
                            <option value="">جميع المستخدمين</option>
                         
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">فلترة</button>
                        <a href="{{ route('report/officeStats') }}" class="btn btn-secondary">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-12" style="margin-top: 20px;">
        <div class="box-content" style="padding: 20px;">
            <div style="width: 100%; max-width: 800px; margin: auto;">
                <canvas id="officeChart" height="400"></canvas>
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

            <script>
                $(document).ready(function() {
                    // When company is selected, load offices
                    $('#companies_id').change(function() {
                        var companyId = $(this).val();
                        var officesSelect = $('#offices_id');
                        var officeUsersSelect = $('#office_users_id');

                        // Reset offices and office users
                        officesSelect.html('<option value="">جميع المكاتب</option>');
                        officeUsersSelect.html('<option value="">جميع المستخدمين</option>');

                        if (companyId) {
                            $.ajax({
                                url: '{{ route("report/offices", ":id") }}'.replace(':id', companyId),
                                type: 'GET',
                                success: function(data) {
                                    if (data && data.length > 0) {
                                        $.each(data, function(index, office) {
                                            officesSelect.append('<option value="' + office.id + '">' + office.name + '</option>');
                                        });
                                    }
                                },
                                error: function() {
                                    alert('خطأ في تحميل المكاتب');
                                }
                            });
                        }
                    });

                    // When office is selected, load office users
                    $('#offices_id').change(function() {
                        var officeId = $(this).val();
                        var officeUsersSelect = $('#office_users_id');

                        // Reset office users
                        officeUsersSelect.html('<option value="">جميع المستخدمين</option>');

                        if (officeId) {
                            $.ajax({
                                url: '{{ route("report/officesuser", ":id") }}'.replace(':id', officeId),
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
        </div>
    </div>
</div>
@endsection
