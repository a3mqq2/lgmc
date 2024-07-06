@extends('layouts.'.get_area_name())

@section('title', 'التقارير')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">كشف حساب خزينة</div>
            <div class="card-body">
                <form action="{{route(get_area_name().'.reports.transactions')}}" method="GET">
                    <div class="row">
                        <div class="col-12">
                            <label for="">الخزينة</label>
                            <select name="vault_id" id="" class="form-control">
                                <option value="">حدد خزينة</option>
                                @foreach ($vaults as $vault)
                                    <option value="{{$vault->id}}">{{$vault->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">من تاريخ</label>
                            <input type="date" name="from_date" id="" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="">الى تاريخ</label>
                            <input type="date" name="to_date" id="" class="form-control">
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
                            <select name="branch_id" id="" class="form-control">
                                <option value="">حدد فرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-12">
                            <label for=""> النوع</label>
                            <select name="licensable_type" id="" class="form-control">
                                <option value="">حدد نوع</option>
                                <option value="App\Models\Doctor">طبيب</option>
                                <option value="App\Models\MedicalFacility">منشاة طبية</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">من تاريخ</label>
                            <input type="date" name="from_date" id="" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="">الى تاريخ</label>
                            <input type="date" name="to_date" id="" class="form-control">
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
