@extends('layouts.'.get_area_name())

@section('title', 'التقارير')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">كشف حساب حساب</div>
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

    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">كشف تجديد اذونات المزاولة </div>
            <div class="card-body">
                <form action="{{route(get_area_name().'.reports.licences')}}">
                    <div class="row">
                        @if (get_area_name() == "admin")
                        <div class="col-12">
                            <label for="">الفرع</label>
                            <select name="branch_id" class="form-control">
                                <option value="">حدد فرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{$branch->id}}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-12">
                            <label for=""> النوع</label>
                            <select name="licensable_type" class="form-control">
                                <option value="">حدد نوع</option>
                                <option value="App\Models\Doctor" {{ request('licensable_type') == 'App\Models\Doctor' ? 'selected' : '' }}>طبيب</option>
                                <option value="App\Models\MedicalFacility" {{ request('licensable_type') == 'App\Models\MedicalFacility' ? 'selected' : '' }}>منشأة طبية</option>
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
