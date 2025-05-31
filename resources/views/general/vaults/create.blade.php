@extends('layouts.' . get_area_name())
@section('title', 'إنشاء حساب  جديدة')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">إنشاء حساب  جديدة</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.vaults.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الحساب</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                      

                        <div class="mb-3">
                            <label for="name" class="form-label"> الرصيد الافتتاحي </label>
                            <input type="text" class="form-control" id="name" name="openning_balance" required>
                        </div>
                      


                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
