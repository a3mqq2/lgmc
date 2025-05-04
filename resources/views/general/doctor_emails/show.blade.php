@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل طلب المراسلة')

@section('content')
@php
    $total = $email->requests->sum(fn($r) => $r->pricing->amount);
    $invoice = $email->requests->first()?->invoice;
@endphp

{{-- ====== المعلومات الأساسية ====== --}}
<div class="row gy-4">
    {{-- الطبيب --}}
    <div class="col-xl-4 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white text-center">
                <h6 class="mb-0 text-white">بيانات الطبيب</h6>
            </div>
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th style="width:40%">الاسم</th>
                        <td>{{ $email->doctor->name }}</td>
                    </tr>
                    <tr>
                        <th>الفرع</th>
                        <td>{{ $email->doctor->branch?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>النوع</th>
                        <td>{{ __($email->doctor->type->value) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- البريد --}}
    <div class="col-xl-4 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white text-center">
                <h6 class="mb-0 text-white">بيانات البريد</h6>
            </div>
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th style="width:40%">البريد الإلكتروني</th>
                        <td>{{ $email->email }}</td>
                    </tr>
                    <tr>
                        <th>الدولة</th>
                        <td>{{ $email->country?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>سبق استخراج أوراق</th>
                        <td>
                            @if($email->has_docs)
                                <span class="badge bg-success">نعم</span>
                                <small class="text-muted">({{ $email->last_year ?? '—' }})</small>
                            @else
                                <span class="badge bg-secondary">لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>عدد الطلبات</th>
                        <td>{{ $email->requests_count ?? $email->requests->count() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- الفاتورة --}}
    <div class="col-xl-4 col-lg-12">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-dark text-center">
                <h6 class="mb-0 text-white">الفاتورة</h6>
            </div>
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th style="width:40%">رقم الفاتورة</th>
                        <td>{{ $invoice?->invoice_number ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>المبلغ الكلي</th>
                        <td>{{ number_format($invoice?->amount ?? $total, 2) }} د.ل</td>
                    </tr>
                    <tr>
                        <th>الحالة</th>
                        <td>
                            @if($invoice)
                                <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'secondary' }}">
                                    {{ $invoice->status }}
                                </span>
                            @else
                                <span class="badge bg-secondary">غير منشأة</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ====== جدول الطلبات ====== --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-primary text-white text-center">
        <h6 class="mb-0">الطلبات المرتبطة</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:5%">#</th>
                    <th>نوع الطلب</th>
                    <th>المبلغ (د.ل)</th>
                    <th>الحالة</th>
                    <th>الملف</th>
                </tr>
            </thead>
            <tbody>
                @foreach($email->requests as $loopIndex => $req)
                    <tr>
                        <td>{{ $loopIndex + 1 }}</td>
                        <td>{{ $req->pricing->name }}</td>
                        <td>{{ number_format($req->pricing->amount, 2) }}</td>
                        @php
                        $arabicStatus = [
                            'pending'       => 'قيد الانتظار',
                            'under_process' => 'قيد المعالجة',
                            'rejected'      => 'مرفوض',
                            'done'          => 'مكتمل',
                        ];
                    
                        $badgeClass = [
                            'pending'       => 'secondary',
                            'under_process' => 'info',
                            'rejected'      => 'danger',
                            'done'          => 'success',
                        ];
                    @endphp
                    {{-- … داخل جدول الطلبات … --}}
                    <td>
                        @php $s = $req->status; @endphp
                        <span class="badge bg-{{ $badgeClass[$s] }}">
                            {{ $arabicStatus[$s] }}
                        </span>
                        @if($s=='rejected' && $req->reason)
                            <br><small class="text-danger">{{ $req->reason }}</small>
                        @endif
                    </td>
                    
                        <td>
                            @if($req->file_path)
                                <a href="{{ Storage::url($req->file_path) }}" target="_blank"
                                   class="btn btn-outline-primary btn-sm">عرض</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="2">الإجمالي</th>
                    <th colspan="3">{{ number_format($total, 2) }} د.ل</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
