@extends('layouts.' . get_area_name())
@section('title','قائمة البريد والطلبات')

@section('content')
{{-- ============ فلترة دقيقة ============ --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white">فلترة البيانات</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row gy-3 gx-2 align-items-end">

            {{-- الطبيب --}}
            <div class="col-md-3">
                <label class="form-label">الطبيب</label>
                <select name="doctor_id" class="form-control select2">
                    <option value="">الكل</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" @selected(request('doctor_id')==$doc->id)>
                            {{ $doc->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- البريد --}}
            <div class="col-md-3">
                <label class="form-label">البريد الإلكتروني يحتوي على</label>
                <input type="text" name="email" class="form-control"
                       value="{{ request('email') }}" placeholder="example@">
            </div>

            {{-- الدولة --}}
            <div class="col-md-3">
                <label class="form-label">الدولة</label>
                <select name="country_id" class="form-control select2">
                    <option value="">الكل</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}" @selected(request('country_id')==$c->id)>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- سبق استخراج أوراق --}}
            <div class="col-md-2 form-check pt-2">
                <input class="form-check-input" type="checkbox"
                       id="has_docs" name="has_docs" value="1"
                       {{ request('has_docs') ? 'checked' : '' }}>
                <label class="form-check-label" for="has_docs">
                    لديه أوراق مستخرَجة
                </label>
            </div>

            {{-- سنة آخر استخراج --}}
            <div class="col-md-2" id="last_year_container"
                 style="{{ request('has_docs') ? '' : 'display:none;' }}">
                <label class="form-label">سنة آخر استخراج</label>
                <select name="last_year" class="form-control">
                    <option value="">الكل</option>
                    @for($y = now()->year; $y >= 2000; $y--)
                        <option value="{{ $y }}" @selected(request('last_year')==$y)>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- المبلغ الإجمالي (>=) --}}
            <div class="col-md-2">
                <label class="form-label">إجمالي الفاتورة (د.ل) ≥</label>
                <input type="number" name="min_total" class="form-control"
                       value="{{ request('min_total') }}">
            </div>

            {{-- عدد الطلبات (>=) --}}
            <div class="col-md-2">
                <label class="form-label">عدد الطلبات ≥</label>
                <input type="number" name="min_requests" class="form-control"
                       value="{{ request('min_requests') }}">
            </div>

            {{-- زر البحث / إعادة تعيين --}}
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1">بحث</button>
                <a href="{{ route(get_area_name().'.doctor-emails.index') }}"
                   class="btn btn-outline-secondary">إعادة</a>
            </div>
        </form>
    </div>
</div>

{{-- ============ جدول النتائج ============ --}}
<div class="card">
    <div class="card-header bg-primary text-white"><h5 class="mb-0 text-white">قائمة البريد</h5></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الطبيب</th>
                    <th>البريد</th>
                    <th>الدولة</th>
                    <th>طلبات</th>
                    <th>لديه أوراق؟</th>
                    <th>آخر سنة</th>
                    <th>إجمالي الفاتورة (د.ل)</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($emails as $i => $email)
                <tr>
                    <td>{{ $emails->firstItem() + $i }}</td>
                    <td>{{ $email->doctor->name }}</td>
                    <td>{{ $email->email }}</td>
                    <td>{{ $email->country?->name ?? '-' }}</td>
                    <td>{{ $email->requests->count() }}</td>
                    <td>
                        <span class="badge bg-{{ $email->has_docs ? 'success' : 'secondary' }}">
                            {{ $email->has_docs ? 'نعم' : 'لا' }}
                        </span>
                    </td>
                    <td>{{ $email->last_year ?? '-' }}</td>
                    <td>
                        {{ number_format($email->requests->sum(fn($r)=>$r->pricing->amount),2) }}
                    </td>
                    <td>
                        <a href="{{ route(get_area_name().'.doctor-emails.show',$email) }}"
                           class="btn btn-sm btn-info">عرض</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">لا توجد بيانات</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $emails->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function(){
    $('.select2').select2({ width:'100%' });
    $('#has_docs').on('change', function(){
        $('#last_year_container').toggle(this.checked);
    });
});
</script>
@endpush
