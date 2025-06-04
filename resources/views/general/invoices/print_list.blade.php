@extends('layouts.a4')
@section('title', 'طباعة فواتير')
@section('content')

<div class="text-center mb-4">
    <h4>طباعة فواتير</h4>
    <p>بتاريخ: {{ now()->format('Y-m-d') }}</p>
</div>

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
<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-primary">طباعة</button>
</div>

@endsection
