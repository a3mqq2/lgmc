@extends('layouts.' . get_area_name())

@section('title', 'دفع الفواتير الكلية')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">دفع الفواتير الكلية</h4>

    <div class="card">
        <div class="card-header bg-primary text-white">قائمة الفواتير غير المدفوعة</div>
        <div class="card-body">
            <form method="POST" action="{{ route(get_area_name().'.total_invoices.store', $doctor) }}">
                @csrf
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                    <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="total_amount" class="form-label">المبلغ الإجمالي المدفوع</label>
                    <input type="number" step="0.01" class="form-control" name="total_amount" id="total_amount" required>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الفاتورة</th>
                                <th>الوصف</th>
                                <th>المبلغ</th>
                                <th>تاريخ الإنشاء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doctor->invoices->where('status', \App\Enums\InvoiceStatus::unpaid) as $invoice)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="invoices[]" value="{{ $invoice->id }}" checked>
                                    </td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->description }}</td>
                                    <td>{{ number_format($invoice->amount, 2) }} د.ل</td>
                                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد فواتير غير مدفوعة.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                              <tr>
                                 <th colspan="2" class="bg-light">الاجمــــالي</th>
                                 <td colspan="3" class="bg-light">{{$doctor->invoices->where('status', \App\Enums\InvoiceStatus::unpaid)->sum('amount')}} د.ل </td>
                              </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary mt-3">تأكيد الدفع</button>
            </form>
        </div>
    </div>
</div>
@endsection
