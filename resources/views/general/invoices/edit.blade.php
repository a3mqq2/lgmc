@extends('layouts.' . get_area_name())

@section('title', 'تعديل مبلغ الفاتورة')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">تعديل مبلغ الفاتورة</h4>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">تحديث المبلغ</div>
        <div class="card-body">
            <form method="POST" action="{{ route(get_area_name().'.invoices.update', $invoice->id) }}">
                @csrf
                @method('PUT')

                <!-- رقم الفاتورة (للقراءة فقط) -->
                <div class="mb-3">
                    <label for="invoice_number" class="form-label">رقم الفاتورة</label>
                    <input type="text" class="form-control" id="invoice_number" value="{{ $invoice->invoice_number }}" disabled>
                </div>

                <!-- المبلغ -->
                <div class="mb-3">
                    <label for="amount" class="form-label">المبلغ (د.ل)</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount', $invoice->amount) }}" required>
                </div>

                <!-- الأزرار -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">تحديث</button>
                    <a href="{{ route(get_area_name().'.invoices.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
