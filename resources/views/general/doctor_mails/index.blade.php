{{-- resources/views/general/doctor_mails/index.blade.php --}}
@extends('layouts.' . get_area_name())
@section('title', 'طلبات أوراق بالخارج')

@section('content')
{{-- رأس الصفحة --------------------------------------------------------- --}}
<div class="row mb-4">
  <div class="col-md-6">
    <h3 class="mb-0">قائمة طلبات الأوراق الخارجية</h3>
  </div>
  <div class="col-md-6 text-end">
    <a href="{{ route(get_area_name().'.doctor-mails.create') }}" class="btn btn-success">
      <i class="fa fa-plus me-1"></i> إضافة طلب جديد
    </a>
  </div>
</div>

{{-- فلترة --------------------------------------------------------------- --}}
<div class="card mb-4 shadow-sm">
  <div class="card-header bg-primary text-white">
    <i class="fa fa-filter me-2"></i> فلترة الطلبات
  </div>
  <div class="card-body">
    <form class="row g-3" method="GET" action="{{ route(get_area_name().'.doctor-mails.index') }}">
      <div class="col-sm-6 col-md-3">
        <div class="form-floating">
          <input type="text" name="name" value="{{ request('name') }}" class="form-control" id="filterName" placeholder="اسم الطبيب">
          <label for="filterName"><i class="fa fa-user-doctor me-1"></i> اسم الطبيب</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="form-floating">
          <input type="text" name="code" value="{{ request('code') }}" class="form-control" id="filterCode" placeholder="رقم النقابي">
          <label for="filterCode"><i class="fa fa-id-badge me-1"></i> رقم النقابي</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-2">
        <div class="form-floating">
          <input type="text" name="request_number" value="{{ request('request_number') }}" class="form-control" id="filterReqNum" placeholder="رقم الطلب">
          <label for="filterReqNum"><i class="fa fa-hashtag me-1"></i> رقم الطلب</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-2">
        <div class="form-floating">
          <input type="date" name="request_date" value="{{ request('request_date') }}" class="form-control" id="filterDate" placeholder="تاريخ الطلب">
          <label for="filterDate"><i class="fa fa-calendar-day me-1"></i> تاريخ الطلب</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-2">
        <div class="form-floating">
          <select name="status" id="filterStatus" class="form-select">
            <option value="">اختر الحالة</option>
            <option value="under_approve"   @selected(request('status')=='under_approve') >قيد الموافقة</option>
            <option value="under_payment"   @selected(request('status')=='under_payment') >قيد الدفع</option>
            <option value="under_proccess"  @selected(request('status')=='under_proccess')>قيد المعالجة</option>
            <option value="done"       @selected(request('status')=='done')     >مكتمل</option>
            <option value="failed"          @selected(request('status')=='failed')        >فشل</option>
          </select>
          <label for="filterStatus"><i class="fa fa-info-circle me-1"></i> الحالة</label>
        </div>
      </div>
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-outline-primary px-4">
          <i class="fa fa-search me-1"></i> تطبيق الفلتر
        </button>
      </div>
    </form>
  </div>
</div>

{{-- الجدول -------------------------------------------------------------- --}}
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">
    <i class="fa fa-list me-2"></i> عرض الطلبات
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-bordered mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:50px">#</th>
            <th>الطبيب</th>
            <th>الإيميلات</th>
            <th>الدول</th>
            <th>الإجمالي</th>
            <th>هل اصدر اوراق من قبل ؟ </th>
            <th>سنة الاصدار</th>
            <th>الحالة</th>
            <th>تاريخ الإنشاء</th>
            <th style="width:170px">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($doctor_mails as $mail)
            <tr>
              <td>{{ $mail->id }}</td>
              <td>
                  <a href="{{route(get_area_name().'.doctors.show', $mail->doctor->id)}}">
                    <strong>{{ $mail->doctor->name }}</strong><br>
                    <small class="text-muted">{{ $mail->doctor->code }}</small>
                  </a>
              </td>
              <td>
                <ul class="mb-0 ps-3">
                  @foreach($mail->emails as $e) <li>{{ $e }}</li> @endforeach
                </ul>
              </td>
              <td>
                @foreach ($mail->countries as $country)
                  <span class="badge bg-secondary">{{ App\Models\Country::find($country) ? App\Models\Country::find($country)->name : "" }}</span>
                @endforeach
              </td>
              <td>{{ number_format($mail->grand_total,2) }} د.ل</td>
              <td>
                @if ($mail->contacted_before)
                  <span class="badge bg-success">نعم</span>
                @else
                  <span class="badge bg-danger">لا</span>
                @endif
              </td>
              <td>
                @if ($mail->last_extract_year)
                  {{ $mail->last_extract_year }}
                @else
                  <span class="badge bg-secondary">غير محدد</span>
                @endif
              </td>
              <td>
                @php
                  $badge = [
                    'under_approve'  => 'bg-warning',
                    'under_payment'  => 'bg-info',
                    'under_proccess' => 'bg-primary',
                    'done'      => 'bg-success',
                    'failed'         => 'bg-danger',
                     'under_edit' => 'bg-secondary',
                  ][$mail->status] ?? 'bg-secondary';

                  $label = [
                    'under_approve'  => 'قيد الموافقة',
                    'under_payment'  => 'قيد الدفع',
                    'under_proccess' => 'قيد المعالجة',
                    'done'      => 'مكتمل',
                     'under_edit' => 'قيد التعديل',
                    'failed'         => 'فشل',
                  ][$mail->status] ?? 'غير معروف';
                @endphp
                <span class="badge {{ $badge }}">{{ $label }}</span>
              </td>
              <td>{{ $mail->created_at->format('Y-m-d') }}</td>
              <td>
               <div class="btn-group btn-group-sm" role="group">
                   {{-- زرّ عرض --}}
                  

                   @if (get_area_name() == "admin")
                   <a href="{{ route(get_area_name().'.doctor-mails.show', $mail) }}"
                    class="btn btn-outline-info" title="عرض">
                    <i class="fa fa-eye"></i>
                  </a>
                   @endif

                   {{-- زرّ اكتمال (يظهر فقط إذا كان قيد المعالجة) --}}
                   @if($mail->status === 'under_proccess')
                     <form action="{{ route(get_area_name().'.doctor-mails.complete', $mail) }}"
                           method="POST" onsubmit="return confirm('تأكيد الإكمال؟');">
                       @csrf @method('PUT')
                       <button type="submit" class="btn btn-outline-success" title="اكتمال">
                         <i class="fa fa-check"></i>
                       </button>
                     </form>
                   @endif
             
                   {{-- زرّ طباعة --}}
                   {{-- <a href="{{ route(get_area_name().'.doctor-mails.print', $mail) }}"
                      target="_blank" class="btn btn-outline-primary" title="طباعة">
                      <i class="fa fa-print"></i>
                   </a>
              --}}
                   {{-- زرّ حذف --}}
                 
                   @if (get_area_name() == "active")
                   <form action="{{ route(get_area_name().'.doctor-mails.destroy', $mail) }}"
                        method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" title="حذف">
                      <i class="fa fa-trash"></i>
                    </button>
                  </form>
                   @endif

               </div>
             </td>
             
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-4">لا توجد طلبات لعرضها</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-4">
  {{ $doctor_mails->withQueryString()->links() }}
</div>
@endsection
