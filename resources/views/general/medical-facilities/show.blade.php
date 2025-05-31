@extends('layouts.' . get_area_name())
@section('title', 'عرض تفاصيل المنشأة الطبية')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">{{ $medicalFacility->name }}</h2>
    </div>
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light text-primary p-2">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active text-primary" id="tab-details" data-bs-toggle="tab" href="#details" role="tab">
                            <i class="fa fa-info-circle me-1"></i> البيانات الأساسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" id="tab-files" data-bs-toggle="tab" href="#files" role="tab">
                            <i class="fa fa-folder-open me-1"></i> المستندات ({{ $medicalFacility->files->count() }})
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    {{-- البيانات الأساسية --}}
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td>اسم المنشأة</td>
                                        <td>{{ $medicalFacility->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>الموقع</td>
                                        <td>{{ $medicalFacility->address }}</td>
                                    </tr>
                                    <tr>
                                        <td>رقم الهاتف</td>
                                        <td>{{ $medicalFacility->phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>تاريخ الإنشاء</td>
                                        <td>{{ $medicalFacility->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>الحالة</td>
                                        <td>
                                            <span class="badge {{ $medicalFacility->membership_status->badgeClass() }}">
                                                {{ $medicalFacility->membership_status->label() }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- المستندات --}}
                    <div class="tab-pane fade" id="files" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:5%;">#</th>
                                        <th style="width:10%;">معاينة</th>
                                        <th>اسم الملف</th>
                                        <th>نوع الملف</th>
                                        <th class="text-center" style="width:20%;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($medicalFacility->files as $file)
                                        @php
                                            $url = Storage::url($file->file_path);
                                            $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['png','jpg','jpeg','gif','bmp','webp']);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($isImage)
                                                    <img src="{{ $url }}" class="img-thumbnail" style="height:40px;" alt="preview">
                                                @else
                                                    <i class="fa fa-file-pdf fa-2x text-danger"></i>
                                                @endif
                                            </td>
                                            <td>{{ $file->file_name }}</td>
                                            <td>{{ optional($file->fileType)->name ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-success me-1">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a download href="{{ $url }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                لا توجد مستندات مرفوعة بعد.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

          </div>

          @if ($medicalFacility->membership_status->value == 'under_approve')
          <form action="{{route(get_area_name().'.medical-facilities.change-status', $medicalFacility)}}" method="POST">
            @csrf
            @method('POST')
            <div class="col-12 mt-4">
                <div class="card shadow-sm">
                  <div class="card-header bg-info text-white">
                    <i class="fa fa-exchange-alt me-1"></i> تحويل إلى حالة
                  </div>
                  <div class="card-body">
                    <div class="row gy-3">
                      <div class="col-md-12">
                        <label for="status-select" class="form-label">اختر الحالة</label>
                        <select id="status-select" name="status" class="form-select">
                          <option value="active">تمت الموافقة</option>
                          <option value="under_edit">قيد التعديل</option>
                        </select>
                      </div>
          
                      <div class="col-md-12 d-none" id="reason-group">
                        <label for="reason" class="form-label">سبب التعديل</label>
                        <textarea id="reason" name="edit_reason" class="form-control" rows="3" placeholder="اكتب السبب هنا..."></textarea>
                      </div>
          
                      <div class="col-md-12 form-check form-switch d-none m-3" id="paid-switch">
                        <input name="is_paid" class="form-check-input" type="checkbox" id="invoice-paid">
                        <label class="form-check-label" for="invoice-paid">الفاتورة مدفوعة</label>
                      </div>
                    </div>
          
                    <div class="mt-3 text-end">
                      <button id="save-status" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> حفظ التغيير
                      </button>
                    </div>
                  </div>
                </div>
              </div>
          </form>
          @endif
          @endsection
          
          @section('scripts')
          <script>
          document.addEventListener('DOMContentLoaded', () => {
            const select      = document.getElementById('status-select');
            const reasonGroup = document.getElementById('reason-group');
            const paidSwitch  = document.getElementById('paid-switch');
          
            function toggleFields() {
              if (select.value === 'under_edit') {
                reasonGroup.classList.remove('d-none');
                paidSwitch .classList.add   ('d-none');
              } else {
                reasonGroup.classList.add   ('d-none');
                paidSwitch .classList.remove('d-none');
              }
            }
          
            select.addEventListener('change', toggleFields);
            toggleFields(); // للتأكد من الحالة الافتراضية عند التحميل
          
          
          });
          </script>
          @endsection