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
                                <th>الخرينة</th>
                                <th> المستخدم</th>
                                <th>الوصف</th>
                                <th class="bg-danger text-light" >سحب</th>
                                <th class="bg-success text-light">ايداع</th>
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
                                    <td>{{$transaction->type == "withdrawal" ? $transaction->amount . ' د.ل ' : ""}}</td>
                                    <td>{{$transaction->type == "deposit" ? $transaction->amount  . 'د.ل' : ""}} </td>
                                    <td>{{ $transaction->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
