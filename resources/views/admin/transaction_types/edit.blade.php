@extends('layouts.' . get_area_name())

@section('title', 'تعديل تصنيف مالي')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">تعديل تصنيف مالي</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.transaction-types.update', $transactionType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم التصنيف</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $transactionType->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">نوع التصنيف</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="deposit" {{ $transactionType->type === 'deposit' ? 'selected' : '' }}>إيداع</option>
                                <option value="withdrawal" {{ $transactionType->type === 'withdrawal' ? 'selected' : '' }}>سحب</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
