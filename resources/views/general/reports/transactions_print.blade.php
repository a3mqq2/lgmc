@extends('layouts.a4')
@section('title', 'كشف حساب الخزينة')
@section('content')

@php
    $total_deposit = $transactions->where('type', 'deposit')->sum('amount');
    $total_withdrawal = $transactions->where('type', 'withdrawal')->sum('amount');
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light mb-3">كشف حساب الخزينة</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th>اشاري المعاملة</th>
                                <th>الخزينة</th>
                                <th>المستخدم</th>
                                <th>الوصف</th>
                                <th class="bg-danger text-light">سحب</th>
                                <th class="bg-success text-light">إيداع</th>
                                <th>التاريخ والوقت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->vault->name }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->desc }}</td>
                                    <td>{{ $transaction->type == "withdrawal" ? number_format($transaction->amount, 2) . ' د.ل' : '' }}</td>
                                    <td>{{ $transaction->type == "deposit" ? number_format($transaction->amount, 2) . ' د.ل' : '' }}</td>
                                    <td>{{ $transaction->created_at }}</td>
                                </tr>
                            @endforeach

                            <tr class="bg-light fw-bold">
                                <td colspan="4" class="text-center">الإجمالي</td>
                                <td>{{ number_format($total_withdrawal, 2) }} د.ل</td>
                                <td>{{ number_format($total_deposit, 2) }} د.ل</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 text-end">
                        <h5>الرصيد الحالي: 
                            <span class="{{ $total_deposit - $total_withdrawal >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($total_deposit - $total_withdrawal, 2) }} د.ل
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
