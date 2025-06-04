@extends('layouts.'.get_area_name())

@section('title', 'قائمة الفواتير')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">قائمة الفواتير

        @if (isset($doctor))
            للطبيب : {{$doctor->name}}
        @endif

    </h4>

    <div class="card">
        <div class="card-header bg-primary text-white">      جدول الفواتير</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>الوصف</th>
                            <th>المستخدم</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->description }}</td>
                                <td>{{ $invoice->user?->name ?? '-' }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} د.ل</td>
                                <td>
                                   <span class="badge {{$invoice->status->badgeClass()}}">
                                        {{$invoice->status->label()}}
                                   </span>
                                </td>
                                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">لا توجد فواتير متاحة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
