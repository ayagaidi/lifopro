@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>إدارة إعدادات عرض الحقول في بطاقة التأمين</h3>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p class="mb-4">يمكنك التحكم في ظهور أو إخفاء الحقول المختلفة في بطاقة التأمين. تحديث الإعدادات سيؤثر على جميع البطاقات الجديدة التي ستتم طباعتها.</p>

                    <form action="{{ route('dashbord.card_field_visibility.updateAll') }}" method="POST">
                        @csrf
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">اسم الحقل</th>
                                        <th scope="col">العنوان</th>
                                        <th scope="col">عرض الحقل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fields as $field)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $field->field_name }}</td>
                                            <td>{{ $field->field_label }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="visibilities[{{ $field->id }}]" value="0">
                                                    <input 
                                                        type="checkbox" 
                                                        class="form-check-input" 
                                                        name="visibilities[{{ $field->id }}]" 
                                                        value="1" 
                                                        id="field-{{ $field->id }}" 
                                                        {{ $field->visible ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="field-{{ $field->id }}">
                                                        {{ $field->visible ? 'مُعرض' : 'مُخفي' }}
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ جميع التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
