@extends('layouts.a4')

@section('title', 'طباعة الفاتورة الكلية')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="bg-primary text-light p-2">فاتورة رقم #{{$totalInvoice->invoice_number}} </h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light">رقم النقابي</th>
                            <td>{{ $totalInvoice->doctor->code}}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">اسم الطبيب</th>
                            <td>{{ $totalInvoice->doctor->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">المستخدم</th>
                            <td>{{ $totalInvoice->user->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الملاحظات</th>
                            <td>{{ $totalInvoice->notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ الإنشاء</th>
                            <td>{{ $totalInvoice->created_at->format('Y-m-d h:i A') }}  </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4 class="bg-primary text-light p-2"> عناصر الفاتورة </h4>
            <div class="table-responsive">
                <table class="table table-bordered">
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
                    <tfoot>
                        <tr>
                            <th class="bg-light" colspan="2">المبلغ الإجمالي</th>
                            <td colspan="3" >{{ number_format($totalInvoice->total_amount, 2) }} د.ل</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection