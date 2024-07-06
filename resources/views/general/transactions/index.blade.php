@extends('layouts.' . get_area_name())
@section('title', 'قائمة المعاملات المالية')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">قائمة المعاملات المالية</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.transactions.index') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="user_id" class="form-label"> المستخدم</label>
                                <select name="user_id" id="" class="form-control">
                                    <option value="">حدد مستخدم</option>
                                    @foreach (App\Models\User::all() as $user)
                                        <option {{$user->id == request('user_id') ? "selected" : ""}} value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="desc" class="form-label">الوصف</label>
                                <input type="text" class="form-control" id="desc" name="desc" value="{{ request('desc') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="transaction_type_id" class="form-label">نوع المعاملة</label>
                                <select class="form-control" id="transaction_type_id" name="transaction_type_id">
                                    <option value="">الكل</option>
                                    @foreach ($transactionTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('transaction_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="transaction_type_id" class="form-label"> الخزينة </label>
                                <select class="form-control" id="transaction_type_id" name="transaction_type_id">
                                    <option value="">الكل</option>
                                    @foreach ($vaults as $vault)
                                        <option value="{{ $vault->id }}" {{ request('vault_id') == $vault->id ? 'selected' : '' }}>{{ $vault->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-3">
                                <label for="type" class="form-label">نوع العملية</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="">الكل</option>
                                    <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>إيداع</option>
                                    <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>سحب</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">تطبيق الفلاتر</button>
                                <a href="{{ route(get_area_name().'.transactions.index') }}" class="btn btn-secondary">إعادة التعيين</a>
                            </div>
                        </div>
                    </form>
                    
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

                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
