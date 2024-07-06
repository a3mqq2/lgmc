@extends('layouts.' . get_area_name())

@section('title', 'إنشاء تصنيف مالي جديد')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">إنشاء تصنيف مالي جديد</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.transaction-types.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم التصنيف</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">نوع التصنيف</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="deposit">إيداع</option>
                                <option value="withdrawal">سحب</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
