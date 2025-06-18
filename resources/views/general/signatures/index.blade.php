@extends('layouts.' . get_area_name())
@section('title', 'التوقيعات')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-light mb-0">التوقيعات</h5>
                    <a href="{{ route(get_area_name() . '.signatures.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة توقيع جديد
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($signatures->count())
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم (عربي)</th>
                                        <th>الاسم (إنجليزي)</th>
                                        <th>المسمى الوظيفي (عربي)</th>
                                        <th>المسمى الوظيفي (إنجليزي)</th>
                                        <th>الفرع</th>
                                        <th>مختار</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($signatures as $index => $signature)
                                        @php
                                            $selected = $signature->branch_id
                                                ? optional($signature->branch)->signature_id == $signature->id
                                                : $signature->is_selected;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $signature->name }}</td>
                                            <td>{{ $signature->name_en }}</td>
                                            <td>{{ $signature->job_title_ar ?: '-' }}</td>
                                            <td>{{ $signature->job_title_en ?: '-' }}</td>
                                            <td>{{ $signature->branch?->name ?? 'النقابة العامة' }}</td>
                                            @php
                                            $selected = $signature->branch_id
                                                ? optional($signature->branch)->signature_id == $signature->id    // فروع
                                                : $signature->is_selected;                                         // النقابة العامة
                                        @endphp
                                        
                                        <td>
                                            <span class="badge {{ $selected ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $selected ? 'نعم' : 'لا' }}
                                            </span>
                                        </td>
                                        
                                            <td>{{ $signature->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route(get_area_name() . '.signatures.edit', $signature) }}"
                                                       class="btn btn-warning btn-sm" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                              
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> لا توجد توقيعات مسجلة حتى الآن
                            </div>
                            <a href="{{ route(get_area_name() . '.signatures.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة أول توقيع
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    function confirmDelete(id, name) {
        document.getElementById('signatureName').textContent = name;
        document.getElementById('confirmDeleteBtn').onclick = () =>
            document.getElementById('delete-form-' + id).submit();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
@endsection
