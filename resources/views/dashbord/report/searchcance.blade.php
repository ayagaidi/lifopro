@extends('layouts.app')
@section('title', 'تقرير البطاقات الملغية ')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content">
                <h4 class="box-title"><a href="{{ route('report/cancelcards') }}">التقارير</a>/ تقارير البطاقات الملغية  </h4>
            </div>

            <div class="box-content">
                <form method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="companies_id" class="control-label">الشركة</label>
                            <select name="companies_id" id="companies_id"
                                class="form-control @error('companies_id') is-invalid @enderror select2 wd-250"
                                data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                data-parsley-errors-container="#slErrorContainer" required>
                                <option value="">اختر الشركة </option>
                                @forelse ($Company as $Compan)
                                    <option value="{{ $Compan->id }}"> {{ $Compan->name }}</option>
                                @empty
                                    <option value="">لايوجد الشركة</option>
                                @endforelse
                            </select>
                            @error('companies_id')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="request_number" class="control-label">رقم الطلب </label>
                            <input type="text" name="request_number" class="form-control @error('request_number') is-invalid @enderror"
                                value="{{ old('request_number') }}" id="request_number" placeholder="رقم الطلب">
                            @error('request_number')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="card_number" class="control-label">رقم البطاقة </label>
                            <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror"
                                value="{{ old('card_number') }}" id="card_number" placeholder="رقم البطاقة">
                            @error('card_number')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>تاريخ الالغاء</label>
                            <label for="fromdate" class="control-label"> من </label>
                            <input name="fromdate" id="fromdate" type="date"
                                class="form-control @error('fromdate') is-invalid @enderror wd-250" />
                            @error('fromdate')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="todate" class="control-label"> الي </label>
                            <input name="todate" id="todate" type="date"
                                class="form-control @error('todate') is-invalid @enderror wd-250" />
                            @error('todate')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12" style="text-align: left;">
                            <button type="button" onclick="search()" class="btn btn-primary waves-effect waves-light">بحث</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row small-spacing">
                <div class="col-md-12">
                    <div class="box-content">
                        <h4 class="box-title">عرض البطاقات</h4>
                        <a href="{{ route('report/cancelcards/pdf') }}" class="btn btn-danger" id="pdfLink" target="_blank" style="display:none;">
                            طباعة التقرير PDF
                        </a>
                        <div class="table-responsive" data-pattern="priority-columns" id="searchs"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Update PDF link with current filters
        function updatePdfLink() {
            const params = new URLSearchParams({
                companies_id: $('#companies_id').val(),
                request_number: $('#request_number').val(),
                card_number: $('#card_number').val(),
                fromdate: $('#fromdate').val(),
                todate: $('#todate').val()
            }).toString();

            $('#pdfLink').attr('href', '{{ route("report/cancelcards/pdf") }}' + '?' + params);
        }

        function search() {
            $('#loader-overlay').show();

            var companies_id = $('#companies_id').val();
            var request_number = $('#request_number').val();
            var card_number = $('#card_number').val();
            var fromdate = $('#fromdate').val();
            var todate = $('#todate').val();

            if (companies_id || fromdate || todate || request_number || card_number) {
                $.ajax({
                    url: '../report/searchcacel',
                    type: 'GET',
                    data: {
                        companies_id: companies_id,
                        request_number: request_number,
                        card_number: card_number,
                        fromdate: fromdate,
                        todate: todate,
                    },
                    success: function(response) {
                        $('#loader-overlay').hide();

                        if (response.code == 1) {
                            // Show the PDF button and update its link
                            $('#pdfLink').show();
                            updatePdfLink();

                            $('#searchs').html(
                                `<div class="table-responsive" data-pattern="priority-columns">
                                    <table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom" style="cursor: pointer;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                               
                                                <th>رقم البطاقة</th>
                                                
                                                <th>الشركة</th>
                                                <th>حالة البطاقة</th>
                                                <th>رقم الطلب</th>
                                                <th>تاريخ اصدار البطاقة</th>
                                                <th>تاريخ الغاء البطاقة</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rowsss"></tbody>
                                    </table>
                                </div>`
                            );

                            var x = 0;
                            response.data.forEach(item => {
                                let companies = item.companies ? item.companies.name : 'الإتحاد الليبي للتأمين';

                                $('#rowsss').append(
                                    `<tr>
                                        <td>${++x}</td>
                                        
                                        <td>${item.card_number}</td>
                                       
                                        <td>${companies}</td>
                                        <td>${item.cardstautes.name}</td>
                                        <td>${item.requests.request_number}</td>
                                    
                                        <td>${item.issuing.issuing_date}</td>
                                        <td>${item.card_delete_date}</td>
                                    </tr>`
                                );
                            });

                            $('#datatable1').dataTable({
                                language: { url: "{{ asset('Arabic.json') }}" },
                                lengthMenu: [10, 20, 30, 50],
                                bLengthChange: true,
                                serverSide: false,
                                paging: true,
                                searching: true,
                                ordering: true,
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                dom: 'Blfrtip',
                                buttons: [
                                    { extend: 'copyHtml5', exportOptions: { columns: [':visible'] }, text: 'نسخ' },
                                    { extend: 'excelHtml5', exportOptions: { columns: ':visible' }, text: 'excel تصدير كـ' }
                                ],
                            });
                        } else {
                            swal.fire("لايوجد بطاقات");
                            $('#searchs').html("");
                            $('#pdfLink').hide();
                        }
                    },
                    error: function() {
                        $('#loader-overlay').hide();
                        swal.fire("حدث خطأ أثناء البحث.");
                        $('#searchs').html("");
                        $('#pdfLink').hide();
                    }
                });
            } else {
                $('#loader-overlay').hide();
                swal.fire(" الرجاء قم باختيار خيار واحد علي الاقل ");
                $('#searchs').html("");
                $('#pdfLink').hide();
            }
        }
    </script>
@endsection
