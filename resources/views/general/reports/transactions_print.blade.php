{{-- resources/views/general/reports/transactions_print.blade.php --}}
@extends('layouts.a4')
@section('title', 'كشف حساب الخزينة - للطباعة')

@section('content')
<div class="print-header text-center mb-4">
    <h2>كشف حساب الخزينة المفصل</h2>
    <h4>{{ config('app.name', 'نظام إدارة الخزائن') }}</h4>
    <hr>
</div>

{{-- معلومات التقرير --}}
<div class="report-info mb-4">
    <div class="row">
        <div class="col-6">
            <table class="table table-borderless table-sm">
                <tr>
                    <td><strong>الخزينة:</strong></td>
                    <td>{{ $reportData['vault']->name ?? 'جميع الخزائن' }}</td>
                </tr>
                <tr>
                    <td><strong>الفترة:</strong></td>
                    <td>من {{ $reportData['from_date']->format('Y-m-d') }} إلى {{ $reportData['to_date']->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td><strong>نوع المعاملات:</strong></td>
                    <td>
                        @if($reportData['type_filter'] == 'deposit')
                            الإيداعات فقط
                        @elseif($reportData['type_filter'] == 'withdrawal')
                            السحوبات فقط
                        @else
                            جميع المعاملات
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class="table table-borderless table-sm">
                <tr>
                    <td><strong>تاريخ الإنشاء:</strong></td>
                    <td>{{ $reportData['generated_at']->format('Y-m-d H:i:s') }}</td>
                </tr>
                <tr>
                    <td><strong>المستخدم:</strong></td>
                    <td>{{ $reportData['generated_by']->name }}</td>
                </tr>
                <tr>
                    <td><strong>عدد المعاملات:</strong></td>
                    <td>{{ $reportData['transaction_count'] }} معاملة</td>
                </tr>
            </table>
        </div>
    </div>
</div>

{{-- الإحصائيات --}}
<div class="summary-section mb-4">
    <h5>ملخص الإحصائيات</h5>
    <div class="row">
        <div class="col-3">
            <div class="border p-3 text-center bg-light">
                <h6>إجمالي الإيداعات</h6>
                <h4 class="text-success">{{ number_format($reportData['total_deposits'], 2) }} د.ل</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="border p-3 text-center bg-light">
                <h6>إجمالي السحوبات</h6>
                <h4 class="text-danger">{{ number_format($reportData['total_withdrawals'], 2) }} د.ل</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="border p-3 text-center bg-light">
                <h6>الرصيد الصافي</h6>
                <h4 class="{{ $reportData['net_balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($reportData['net_balance'], 2) }} د.ل
                </h4>
            </div>
        </div>
        <div class="col-3">
            <div class="border p-3 text-center bg-light">
                <h6>عدد المعاملات</h6>
                <h4 class="text-info">{{ $reportData['transaction_count'] }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- جدول المعاملات --}}
<div class="transactions-table">
    <h5>تفاصيل المعاملات</h5>
    <table class="table table-bordered table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th  style="background:#000; width: 5%">#</th>
                <th  style="background:#000; width: 8%">رقم المعاملة</th>
                <th  style="background:#000; width: 12%">الخزينة</th>
                <th  style="background:#000; width: 12%">المستخدم</th>
                <th  style="background:#000; width: 10%">التصنيف</th>
                <th  style="background:#000; width: 20%">الوصف</th>
                <th  style="background:#000; width: 8%" class="bg-danger text-white">سحب</th>
                <th  style="background:#000; width: 8%" class="bg-success text-white">إيداع</th>
                <th  style="background:#000; width: 8%">الرصيد</th>
                <th  style="background:#000; width: 9%">التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $index => $transaction)
                <tr class="{{ $transaction->type == 'withdrawal' ? 'table-danger' : ($transaction->type == 'deposit' ? 'table-success' : '') }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <small>#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</small>
                        @if($transaction->reference_id)
                            <br><small class="text-muted">{{ $transaction->reference_id }}</small>
                        @endif
                    </td>
                    <td><small>{{ $transaction->vault->name }}</small></td>
                    <td><small>{{ $transaction->user->name }}</small></td>
                    <td>
                        @if($transaction->financialCategory)
                            <small>{{ $transaction->financialCategory->name }}</small>
                        @else
                            <small class="text-muted">غير محدد</small>
                        @endif
                    </td>
                    <td><small>{{ Str::limit($transaction->desc, 40) }}</small></td>
                    <td class="text-danger">
                        @if($transaction->type == "withdrawal")
                            <strong>{{ number_format($transaction->amount, 2) }}</strong>
                        @endif
                    </td>
                    <td class="text-success">
                        @if($transaction->type == "deposit")
                            <strong>{{ number_format($transaction->amount, 2) }}</strong>
                        @endif
                    </td>
                    <td class="text-info">
                        <small>{{ number_format($transaction->balance ?? 0, 2) }}</small>
                    </td>
                    <td>
                        <small>{{ $transaction->created_at->format('Y-m-d') }}</small>
                        <br><small>{{ $transaction->created_at->format('H:i') }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-3">
                        <strong>لا توجد معاملات في الفترة المحددة</strong>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($transactions->isNotEmpty())
            <tfoot class="table-secondary">
                <tr>
                    <td colspan="6" class="text-center"><strong>الإجماليات</strong></td>
                    <td class="text-danger"><strong>{{ number_format($reportData['total_withdrawals'], 2) }}</strong></td>
                    <td class="text-success"><strong>{{ number_format($reportData['total_deposits'], 2) }}</strong></td>
                    <td class="text-info"><strong>{{ number_format($reportData['net_balance'], 2) }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>

{{-- ملاحظات نهاية التقرير --}}
<div class="report-footer mt-4">
    <div class="row">
        <div class="col-8">
            <h6>ملاحظات:</h6>
            <ul class="list-unstyled small">
                <li>• جميع المبالغ بالدينار الليبي (د.ل)</li>
                <li>• الرصيد الصافي = إجمالي الإيداعات - إجمالي السحوبات</li>
                <li>• يتم احتساب الرصيد بعد كل معاملة</li>
                <li>• التقرير يشمل الفترة من {{ $reportData['from_date']->format('Y-m-d') }} إلى {{ $reportData['to_date']->format('Y-m-d') }}</li>
            </ul>
        </div>
        <div class="col-4 text-end">
            <div class="signature-section">
                <br><br>
                <hr style="width: 150px; margin-left: auto;">
                <p class="small mb-0">التوقيع</p>
                <p class="small">{{ $reportData['generated_by']->name }}</p>
                <p class="small text-muted">{{ $reportData['generated_at']->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- رقم الصفحة للطباعة --}}
<div class="page-break"></div>

@endsection

@push('styles')
<style>
/* أنماط الطباعة */
@media print {
    body {
        font-size: 12px;
        line-height: 1.4;
    }
    
    .print-header h2 {
        font-size: 18px;
        margin-bottom: 5px;
    }
    
    .print-header h4 {
        font-size: 14px;
        margin-bottom: 10px;
    }
    
    .table {
        font-size: 10px;
    }
    
    .table th,
    .table td {
        padding: 3px;
        vertical-align: middle;
    }
    
    .summary-section .border {
        border: 1px solid #000 !important;
    }
    
    .summary-section h4 {
        font-size: 14px;
    }
    
    .summary-section h6 {
        font-size: 11px;
        margin-bottom: 5px;
    }
    
    .page-break {
        page-break-after: always;
    }
    
    /* تحسين عرض الألوان في الطباعة */
    .text-success {
        color: #000 !important;
        font-weight: bold;
    }
    
    .text-danger {
        color: #000 !important;
        font-weight: bold;
    }
    
    .text-info {
        color: #000 !important;
    }
    
    .table-success {
        background-color: #f0f0f0 !important;
    }
    
    .table-danger {
        background-color: #f5f5f5 !important;
    }
    
    .table-dark {
        background-color: #666 !important;
        color: white !important;
    }
    
    .bg-success {
        background-color: #7ce573 !important;
    }
    
    .bg-danger {
        background-color: #c97171 !important;
    }
}

/* أنماط العرض العادي */
.print-header {
    border-bottom: 2px solid #333;
    padding-bottom: 15px;
}

.report-info .table td {
    padding: 5px;
    border: none;
}

.summary-section .border {
    border-radius: 5px;
}

.transactions-table .table {
    margin-bottom: 0;
}

.signature-section {
    margin-top: 30px;
}

.report-footer {
    border-top: 1px solid #ddd;
    padding-top: 15px;
}

/* تحسين عرض الجدول */
.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    text-align: center;
}

.table td {
    vertical-align: middle;
}

.table-sm th,
.table-sm td {
    padding: 0.3rem;
}

/* ألوان مخصصة للتصنيفات */
.bg-success {
    background-color: #d4edda !important;
}

.bg-danger {
    background-color: #f8d7da !important;
}

.table-success {
    background-color: rgba(25, 135, 84, 0.1);
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
// طباعة تلقائية عند تحميل الصفحة
window.onload = function() {
    window.print();
};
</script>
@endpush