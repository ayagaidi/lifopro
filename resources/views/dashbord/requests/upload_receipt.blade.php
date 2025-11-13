@extends('layouts.app')
@section('title', 'رفع إيصال الدفع')

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
            <h4 class="box-title">رفع إيصال الدفع للطلب رقم: {{ $request->request_number }}</h4>
            <form action="{{ route('cardrequests/upload-payment-receipt', encrypt($request->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="payment_receipt">اختر ملف الإيصال (PNG, JPG, JPEG, PDF - حد أقصى 2 ميجابايت)</label>
                    <input type="file" class="form-control" id="payment_receipt" name="payment_receipt" accept=".png,.jpg,.jpeg,.pdf" required>
                </div>
                <button type="submit" class="btn btn-primary">رفع الإيصال</button>
                <a href="{{ route('cardrequests/company') }}" class="btn btn-secondary">العودة</a>
            </form>
        </div>
    </div>
</div>
@endsection
