@extends('layouts.'.get_area_name())

@section('title', 'كشف حساب الخزينة')

@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="{{route(get_area_name().'.reports.transactions_print', request()->all())}}" class="btn btn-secondary mb-3"><i class="fa fa-print"></i>طباعة </a>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">كشف حساب الخزينة</div>
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
                            @php
                                $totalWithdrawals = 0;
                                $totalDeposits = 0;
                            @endphp

                            @foreach ($transactions as $transaction)
                                @php
                                    if($transaction->type == 'withdrawal') {
                                        $totalWithdrawals += $transaction->amount;
                                    } elseif($transaction->type == 'deposit') {
                                        $totalDeposits += $transaction->amount;
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->vault->name }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->desc }}</td>
                                    <td>{{ $transaction->type == 'withdrawal' ? number_format($transaction->amount, 2) . ' د.ل' : '' }}</td>
                                    <td>{{ $transaction->type == 'deposit' ? number_format($transaction->amount, 2) . ' د.ل' : '' }}</td>
                                    <td>{{ $transaction->created_at }}</td>
                                </tr>
                            @endforeach

                            <!-- الإجماليات -->
                            <tr class="bg-light">
                                <td colspan="4" class="text-center"><strong>الإجمالي</strong></td>
                                <td><strong>{{ number_format($totalWithdrawals, 2) }} د.ل</strong></td>
                                <td><strong>{{ number_format($totalDeposits, 2) }} د.ل</strong></td>
                                <td></td>
                            </tr>
                            <tr class="bg-white">
                                <td colspan="4" class="text-center"><strong>الرصيد الحالي</strong></td>
                                <td colspan="3" class="text-center">
                                    <strong>
                                        {{ number_format($totalDeposits - $totalWithdrawals, 2) }} د.ل
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
