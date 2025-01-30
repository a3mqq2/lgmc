@extends('layouts.' . get_area_name())

@section('title', 'تفاصيل الفاتورة الكلية')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">تفاصيل الفاتورة الكلية - {{ $totalInvoice->invoice_number }}</h4>

    <div class="card">
        <div class="card-header bg-primary text-white">معلومات الفاتورة</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>رقم الفاتورة:</strong> {{ $totalInvoice->invoice_number }}</p>
                    <p><strong>اسم الطبيب:</strong> {{ $totalInvoice->doctor->name }}</p>
                    <p><strong>المبلغ الإجمالي:</strong> {{ number_format($totalInvoice->total_amount, 2) }} د.ل</p>
                </div>
                <div class="col-md-6">
                    <p><strong>المستخدم:</strong> {{ $totalInvoice->user->name }}</p>
                    <p><strong>الملاحظات:</strong> {{ $totalInvoice->notes ?? '-' }}</p>
                    <p><strong>تاريخ الإنشاء:</strong> {{ $totalInvoice->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">الفواتير المرتبطة</div>
        <div class="card-body">
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
                        @forelse($totalInvoice->invoices as $invoice)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->description }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} د.ل</td>
                                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد فواتير مرتبطة بهذه الفاتورة الكلية.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <a href="{{ route(get_area_name().'.total_invoices.print', $totalInvoice->id) }}" class="btn btn-secondary" target="_blank">طباعة الفاتورة <i class="fa fa-print"></i></a>
    </div>
</div>
@endsection
