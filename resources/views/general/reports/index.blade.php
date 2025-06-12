@extends('layouts.'.get_area_name())

@section('title', 'التقارير')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">كشف حساب</div>
            <div class="card-body">
                <form action="{{route(get_area_name().'.reports.transactions')}}" method="GET">
                    <div class="row">
                        <div class="col-12">
                            <label for="">الحساب</label>
                            <select name="vault_id" class="form-control">
                                <option value="">حدد حساب</option>
                                @foreach ($vaults as $vault)
                                    <option value="{{$vault->id}}" {{ request('vault_id') == $vault->id ? 'selected' : '' }}>{{$vault->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">من تاريخ</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date', now()->toDateString()) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="">الى تاريخ</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date', now()->toDateString()) }}">
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary text-light">عرض</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
