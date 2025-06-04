@extends('layouts.a4')
@section('title', 'طباعة قائمة الأطباء')
@section('content')

<div class="text-center mb-4">
    <h4>قائمة الأطباء</h4>
    <p>بتاريخ: {{ now()->format('Y-m-d') }}</p>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead>
            <tr>
                <th class="bg-light">#</th>
                <th class="bg-light">كود الطبيب</th>
                <th class="bg-light">الاسم</th>
                <th class="bg-light">رقم الهاتف</th>
                <th class="bg-light">الصفة / التخصص</th>

                @if (request('type') == "visitor")
                    <th class="bg-light">تاريخ بدء الزيارة</th>
                    <th class="bg-light">تاريخ انتهاء الزيارة</th>
                @endif

                @if (request('type') == "libyan")
                <th class="bg-light">الرقم الوطني</th>
                @endif
                
                <th class="bg-light text-dark">نوع الطبيب</th>
                <th class="bg-light">حالة الاشتراك</th>

                @if (request('init_approve'))
                    <th class="bg-light">تاريخ الزيارة</th>
                @endif

                @if (get_area_name() == "finance")
                <th class="bg-danger text-light">المستحقات</th>
                <th class="bg-success text-light">المدفوعات</th>
                <th class="bg-warning text-light">عدد الفواتير</th>
                <th class="bg-info text-light">آخر دفعة</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($doctors as $doctor)
            <tr @if ($doctor->membership_status->value == "banned") class="bg-gradient-danger text-light" @endif>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $doctor->code }}</td>
                <td>{{ $doctor->name }}</td>
                <td>{{ $doctor->phone }}</td>
                <td>
                    {{ $doctor->doctor_rank->name ?? '-' }}
                    {{ $doctor->specialization }}
                </td>

                @if (request('type') == "visitor")
                <td>{{ $doctor->visit_from }}</td>
                <td>{{ $doctor->visit_to }}</td>
                @endif

                @if (request('type') == "libyan")
                <td>{{ $doctor->national_number }}</td>
                @endif
                
                <td class="{{$doctor->type->badgeClass()}}">
                    {{ $doctor->type->label() }}
                </td>

                <td>
                    <span class="badge {{$doctor->membership_status->badgeClass()}}">
                        {{ $doctor->membership_status->label() }}
                    </span>
                </td>

                @if (request('init_approve'))
                    <td>{{$doctor->visiting_date}}</td>
                @endif

                @if (get_area_name() == "finance")
                @php
                    $unpaidTotal = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::unpaid)->sum('amount');
                    $paidTotal = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::paid)->sum('amount');
                    $reliefTotal = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::relief)->sum('amount');
                    $totalInvoices = $doctor->invoices->count();
                    $lastPayment = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::paid)->sortByDesc('received_at')->first();
                @endphp
                
                <td class="{{ $unpaidTotal > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                    {{ number_format($unpaidTotal, 2) }} د.ل
                    @if($reliefTotal > 0)
                        <br><small class="text-muted">(إعفاء: {{ number_format($reliefTotal, 2) }})</small>
                    @endif
                </td>
                
                <td class="text-success fw-bold">
                    {{ number_format($paidTotal, 2) }} د.ل
                </td>
                
                <td class="text-center">
                    <span class="badge bg-primary">{{ $totalInvoices }}</span>
                </td>
                
                <td class="text-center">
                    @if($lastPayment)
                        {{ $lastPayment->received_at ? $lastPayment->received_at : '-' }}
                        <br><small class="text-muted">{{ number_format($lastPayment->amount, 2) }} د.ل</small>
                    @else
                        <span class="text-muted">لا توجد دفعات</span>
                    @endif
                </td>
                @endif

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-primary">طباعة</button>
</div>

@endsection
