@extends('layouts.' . get_area_name())
@section('title', 'تعديل خزينة')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">تعديل خزينة</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.vaults.update', $vault->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الخزينة</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $vault->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="branch_id" class="form-label">تابعه للإدارة</label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">اختر الفرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $vault->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
