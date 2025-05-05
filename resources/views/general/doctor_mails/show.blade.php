@extends('layouts.' . get_area_name())
@section('title', 'عرض طلب #' . $doctorMail->id)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 text-primary"><i class="fa fa-file-alt me-2"></i>تفاصيل الطلب #{{ $doctorMail->id }}</h3>
    <a href="{{ route(get_area_name().'.doctor-mails.index') }}" class="btn btn-primary text-white">
        <i class="fa fa-arrow-left me-1"></i> رجوع
    </a>
</div>

{{-- بيانات أساسية كجدول رسمي --}}
<div class="card shadow mb-4">
  <div class="card-header bg-primary text-light">البيانات الأساسية</div>
  <div class="card-body p-0">
    <table class="table table-striped table-bordered mb-0">
      <tbody>
        <tr>
          <th class="text-primary bg-light">الطبيب</th>
          <td>{{ $doctorMail->doctor->name }} ({{ $doctorMail->doctor->code }})</td>
        </tr>
        <tr>
          <th class="text-primary bg-light">استخراج سابقًا</th>
          <td>{{ $doctorMail->contacted_before ? 'نعم' : 'لا' }}</td>
        </tr>
        <tr>
          <th class="text-primary bg-light">الإيميلات</th>
          <td>
            @foreach($doctorMail->emails as $email)
              <span class="badge bg-white text-primary me-1">{{ $email }}</span>
            @endforeach
          </td>
        </tr>
        <tr>
          <th class="text-primary bg-light">الدول المستهدفة</th>
          <td>{{ $doctorMail->country_names }}</td>
        </tr>
        <tr>
          <th class="text-primary bg-light">ملاحظات</th>
          <td>{{ $doctorMail->notes ?? '—' }}</td>
        </tr>
        <tr>
          <th class="text-primary bg-light">الحالة الحالية</th>
          <td>
            @php
              $badge = match($doctorMail->status) {
                'under_approve' => 'bg-warning',
                'under_payment'       => 'bg-info',
                'done'     => 'bg-success',
                'failed'        => 'bg-danger',
                  'under_proccess' => 'bg-primary',
                  'under_payment'  => 'bg-info',
                  'done'      => 'bg-success',
                  'failed'         => 'bg-danger',
                  'under_edit' => 'bg-secondary',
                default         => 'bg-secondary'
              };
              $label = match($doctorMail->status) {
                'under_approve' => 'قيد الموافقة',
                'under_payment'       => 'قيد الدفع',
                'under_proccess'       => 'قيد المعالجة',
                'done'     => 'مكتمل',
                'failed'        => 'فشل',
                  'under_edit' => 'قيد التعديل',
                  'under_payment'  => 'قيد الدفع',
                default         => 'غير معروف'
              };
            @endphp
            <span class="badge {{ $badge }}">{{ $label }}</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

{{-- اعتماد الخدمات أو عرضها --}}
@if($doctorMail->status === 'under_approve')
  <form action="{{ route(get_area_name().'.doctor-mails.update', $doctorMail) }}" method="POST">
    @csrf @method('PUT')

    <div class="card shadow mb-4">
      <div class="card-header text-primary bg-light">اعتماد الخدمات</div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
          <thead class="text-primary bg-light">
            <tr>
              <th>الخدمة</th>
              <th class="text-end">المبلغ</th>
              <th>المرفق</th>
              <th class="text-center">اعتماد</th>
              <th>سبب الرفض</th>
            </tr>
          </thead>
          <tbody>
            @foreach($doctorMail->doctorMailItems as $item)
              <tr>
                <td>{{ $item->pricing->name }} /  @if ($item->work_mention === 'with')
                  مع ذكر جهة العمل
                @elseif ($item->work_mention === 'without')
                  دون ذكر جهة العمل
                @else
                  —
                @endif </td>
                <td class="text-end">{{ number_format($item->pricing->amount,2) }} د.ل</td>
                <td>
                  @if($item->file)
                    <a download="" href="{{ Storage::url($item->file) }}" target="_blank" class="btn btn-sm btn-light">
                      <i class="fa fa-download me-1"></i> تنزيل المرفق
                    </a>
                  @else
                    —
                  @endif
                </td>
                <td class="text-center">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input approve-toggle" type="radio"
                           name="items[{{ $item->id }}][approved]" value="1"
                           data-item="{{ $item->id }}" checked>
                    <label class="form-check-label">نعم</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input approve-toggle" type="radio"
                           name="items[{{ $item->id }}][approved]" value="0"
                           data-item="{{ $item->id }}">
                    <label class="form-check-label">لا</label>
                  </div>
                </td>
                <td id="reason-{{ $item->id }}" style="display:none;">
                  <input type="text"
                         name="items[{{ $item->id }}][reason]"
                         class="form-control form-control-sm"
                         placeholder="أدخل سبب الرفض">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary px-4">
          حفظ الاعتماد
        </button>
      </div>
    </div>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.approve-toggle').forEach(function(el) {
        el.addEventListener('change', function() {
          var id = this.dataset.item;
          var cell = document.getElementById('reason-' + id);
          cell.style.display = (this.value === '0') ? 'table-cell' : 'none';
        });
      });
    });
  </script>

@else
  <div class="card shadow mb-4">
    <div class="card-header bg-primary text-light">خدمات الطلب</div>
    <div class="card-body p-0">
      <table class="table table-bordered mb-0">
        <thead class="text-primary bg-light">
          <tr>
            <th>الخدمة</th>
            <th class="text-center">المبلغ</th>
            <th class="text-center">سبب رفض</th>

          </tr>
        </thead>
        <tbody>
          @foreach($doctorMail->doctorMailItems as $item)
            <tr>
              <td>{{ $item->pricing->name }} /  @if ($item->work_mention === 'with')
                مع ذكر جهة العمل
              @elseif ($item->work_mention === 'without')
                دون ذكر جهة العمل
              @else
                —
              @endif </td>
              <td class="text-center">{{ number_format($item->pricing->amount,2) }} د.ل</td>
               <td class="text-center">
                  {{ $item->rejected_reason ?? '—' }}

                  </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif

@endsection
