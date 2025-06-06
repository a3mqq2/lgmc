@extends('layouts.doctor')

@push('styles')
<style>
.switch { position: relative; display: inline-block; width: 50px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
.switch input:checked + .slider { background-color: #28a745; }
.switch input:focus + .slider { box-shadow: 0 0 1px #28a745; }
.switch input:checked + .slider:before { transform: translateX(26px); }
</style>
@endpush

@section('content')
<div class="row">
   <div class="col-lg-12">
       <div>
           <div class="tab-content pt-4 text-muted">
               <div class="tab-pane active" id="requests" role="tabpanel">
                   <div class="card">
                       <div class="card-body">
                           <h3 class="mb-0 text-primary"><i class="fa fa-file-alt me-2"></i>تفاصيل الطلب #{{ $doctorMail->id }}</h3>
                       </div>
                   </div>

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
                                   {{-- last year  --}}
                                      <tr>
                                        <th class="text-primary bg-light">اخر سنة استخراج</th>
                                        <td>{{ $doctorMail->last_extract_year ?? '—' }}</td>
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
                                                   'under_proccess' => 'bg-primary',
                                                   'under_payment' => 'bg-info',
                                                   'done' => 'bg-success',
                                                   'failed' => 'bg-danger',
                                                   'under_edit' => 'bg-secondary',
                                                   default => 'bg-secondary'
                                               };
                                               $label = match($doctorMail->status) {
                                                   'under_approve' => 'قيد الموافقة',
                                                   'under_proccess' => 'قيد التجهيز',
                                                   'under_payment' => 'قيد الدفع',
                                                   'done' => 'مكتمل',
                                                   'failed' => 'فشل',
                                                   'under_edit' => 'قيد التعديل',
                                                   default => 'غير معروف'
                                               };
                                           @endphp
                                           <span class="badge {{ $badge }}">{{ $label }}</span>
                                       </td>
                                   </tr>
                               </tbody>
                           </table>
                       </div>
                   </div>

                   <div class="card shadow mb-4">
                       <div class="card-header bg-primary text-light">خدمات الطلب</div>
                       <div class="card-body p-0">
                           <form method="POST" action="{{ route('doctor.doctor-emails.update', $doctorMail->id) }}" enctype="multipart/form-data">
                               @csrf
                               @method('PUT')
                           
                               <table class="table table-bordered mb-0">
                                <thead class="text-primary bg-light">
                                    <tr>
                                        <th>الخدمة</th>
                                        <th class="text-center">المبلغ</th>
                                        <th class="text-center">الملف</th>
                                        <th class="text-center">سبب الرفض</th>
                                        @if ($doctorMail->status == "under_edit")
                                            <th class="text-center">حذف</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($doctorMail->doctorMailItems as $item)
                                        <tr class="@if($item->status === 'approved') table-success @elseif($item->status === 'rejected') table-danger @endif">
                                            <td>{{ $item->pricing->name }}  @if ($item->work_mention === 'with')
                                                مع ذكر جهة العمل /  
                                              @elseif ($item->work_mention === 'without')
                                                دون ذكر جهة العمل / 
                                              @else
                                              @endif </td>
                            
                            
                                            <td class="text-center">{{ number_format($item->pricing->amount, 2) }} د.ل</td>
                                            <td class="text-center">
                                                @if($item->file)
                                                    <a download href="{{ asset('storage/'.$item->file) }}" target="_blank" class="btn btn-primary btn-sm">عرض الملف</a>
                                                @else
                                                    —
                                                @endif
                                                @if($doctorMail->status !== 'under_approve' && $item->status == "rejected")
                                                    <input type="file" name="files[{{ $item->id }}]" class="form-control form-control-sm mt-1">
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->rejected_reason ?? '—' }}</td>
                            
                                            @if ($doctorMail->status == "under_edit")
                                                <td class="text-center">
                                                    @if ($item->status == "rejected")
                                                        <input type="checkbox" name="items[{{ $item->id }}]" class="switch" id="item-{{ $item->id }}">
                                                        <label for="item-{{ $item->id }}" class="slider"></label>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>   
                               @if ($doctorMail->status == "under_edit")
                               <div class="mt-3 text-end me-3">
                                    <button type="submit" class="btn btn-success">تحديث الطلب</button>
                              </div>
                               @endif

                           </form>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection
