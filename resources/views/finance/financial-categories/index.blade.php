@extends('layouts.finance')

@section('title', 'التصنيفات المالية')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">التصنيفات المالية</h2>
            <p class="text-muted">إدارة تصنيفات الحركات المالية (الإيداع والسحب)</p>
        </div>
        <a href="{{ route('finance.financial-categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            إضافة تصنيف جديد
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('finance.financial-categories.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">البحث</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="البحث في اسم التصنيف..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">النوع</label>
                        <select name="type" class="form-select">
                            <option value="">جميع الأنواع</option>
                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>إيداع</option>
                            <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>سحب</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('finance.financial-categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم التصنيف</th>
                                <th>النوع</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $category->type_color }}">
                                        {{ $category->type_name }}
                                    </span>
                                </td>
                                <td>{{ $category->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('finance.financial-categories.edit', $category) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد تصنيفات مالية</h5>
                    <p class="text-muted">قم بإنشاء تصنيف مالي جديد للبدء</p>
                    <a href="{{ route('finance.financial-categories.create') }}" class="btn btn-primary">
                        إضافة تصنيف جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection