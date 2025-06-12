@extends('layouts.finance')

@section('title', 'إضافة تصنيف مالي جديد')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="">
        <div>
            <h2 class="h3 mb-0">إضافة تصنيف مالي جديد</h2>
            <p class="text-muted">إنشاء تصنيف جديد للحركات المالية</p>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('finance.financial-categories.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم التصنيف <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="مثال: رواتب، إعاشة، مصروفات إدارية..."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">نوع التصنيف <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required>
                                <option value="">اختر نوع التصنيف</option>
                                <option value="deposit" {{ old('type') == 'deposit' ? 'selected' : '' }}>
                                    إيداع
                                </option>
                                <option value="withdrawal" {{ old('type') == 'withdrawal' ? 'selected' : '' }}>
                                    سحب
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('finance.financial-categories.index') }}" class="btn btn-secondary">
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                حفظ التصنيف
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
