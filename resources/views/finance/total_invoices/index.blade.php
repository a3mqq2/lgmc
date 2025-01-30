@extends('layouts.' . get_area_name())

@section('title', 'الفواتير الكلية')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">الفواتير الكلية</h4>

    <div class="card">
        <div class="card-header bg-primary text-white">تصفية الفواتير الكلية</div>
        <div class="card-body">
            <form method="GET" action="{{ route(get_area_name().'.total_invoices.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="invoice_number">رقم الفاتورة الكلية</label>
                        <input type="text" name="invoice_number" class="form-control" value="{{ request('invoice_number') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="doctor_name">اسم الطبيب</label>
                        <input type="text" name="doctor_name" class="form-control" value="{{ request('doctor_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="user_name">اسم المستخدم</label>
                        <input type="text" name="user_name" class="form-control" value="{{ request('user_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_from">من تاريخ</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label for="date_to">إلى تاريخ</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" class="btn btn-primary">بحث</button>
                        <a href="{{ route(get_area_name().'.total_invoices.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">قائمة الفواتير الكلية</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم الفاتورة الكلية</th>
                            <th>اسم الطبيب</th>
                            <th>المبلغ الإجمالي</th>
                            <th>المستخدم</th>
                            <th>الملاحظات</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($totalInvoices as $invoice)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->doctor->name }}</td>
                                <td>{{ number_format($invoice->total_amount, 2) }} د.ل</td>
                                <td>{{ $invoice->user->name }}</td>
                                <td>{{ $invoice->notes ?? '-' }}</td>
                                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route(get_area_name().'.total_invoices.show', $invoice->id) }}" class="btn btn-primary btn-sm text-light">عرض <i class="fa fa-eye"></i></a>
                                    <a href="{{ route(get_area_name().'.total_invoices.print', $invoice->id) }}" class="btn btn-secondary btn-sm text-light">طباعة <i class="fa fa-print"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">لا توجد فواتير كلية مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $totalInvoices->links() }}
        </div>
    </div>
</div>
@endsection
