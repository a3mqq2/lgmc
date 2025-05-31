@extends('layouts.'.get_area_name())

@section('title', 'قائمة الفواتير')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route(get_area_name().'.invoices.create', ['type' => request()->type]) }}" class="btn btn-success btn-sm mb-3">إضافة فاتورة جديدة <i class="fa fa-plus mb-2"></i></a>
        </div>
    </div>

    <h4 class="mb-4">قائمة الفواتير</h4>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">🔍 تصفية الفواتير</div>
        <div class="card-body">
            <form method="GET" action="{{ route(get_area_name().'.invoices.index') }}">
                <div class="row">
                    <div class="col-md-6">
                        <label for="search">رقم الفاتورة</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="ادخل رقم الفاتورة كاملا او جزء منها للبحث">
                    </div>


                    <input type="hidden" name="status" value="{{request('status')}}">

                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">  بحث</button>
                        <a href="{{ route(get_area_name().'.invoices.index') }}" class="btn btn-secondary">  إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">      جدول الفواتير</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم الفاتورة</th>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>المستخدم</th>
                            <th>رقم الإذن</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->invoiceable?->name }}</td>
                                <td>{{ $invoice->description }}</td>
                                <td>{{ $invoice->user?->name ?? '-' }}</td>
                                <td>{{ $invoice->license_id ?? '-' }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} د.ل</td>
                                <td>
                                   <span class="badge {{$invoice->status->badgeClass()}}">
                                        {{$invoice->status->label()}}
                                   </span>
                                </td>
                                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                <td>
                                    
                                    @if ($invoice->status == App\Enums\InvoiceStatus::unpaid)

                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#received_{{$invoice->id}}">
                                            استلام القيمة <i class="fa fa-check"></i>
                                        </button>


                                        @if (!auth()->user()->branch_id)
                                        <a href="{{ route(get_area_name().'.invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning">تعديل <i class="fa fa-edit"></i> </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#relief_{{$invoice->id}}">
                                            اعفاء عن الدفع <i class="fa fa-times"></i>
                                        </button>
                                        @endif
                                    @endif
                                    <a href="{{ route(get_area_name().'.invoices.print', $invoice->id) }}" class="btn btn-sm btn-secondary">
                                        طباعة
                                    </a>
                                </td>
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


    @foreach ($invoices as $invoice)

    @if ((!auth()->user()->branch_id || (auth()->user()->branch_id == $invoice->branch_id ) ) && $invoice->status->value == App\Enums\InvoiceStatus::unpaid->value)
    <div class="modal fade" id="received_{{$invoice->id}}" tabindex="-1" aria-labelledby="received_{{$invoice->id}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route(get_area_name() . '.invoices.received', ['invoice' => $invoice->id]) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="received_{{$invoice->id}}Label">تآكيد إستلام القيمة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات - اختياري</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">موافقة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (!auth()->user()->branch_id)
    <div class="modal fade" id="relief_{{$invoice->id}}" tabindex="-1" aria-labelledby="relief_{{$invoice->id}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route(get_area_name() . '.invoices.relief', ['invoice' => $invoice->id]) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="relief_{{$invoice->id}}Label">تآكيد اعفاء عن دفع القيمة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">السبب - اجباري </label>
                            <textarea class="form-control" id="notes" name="notes" required rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-danger">موافقة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @endif




    @endforeach


</div>
@endsection
