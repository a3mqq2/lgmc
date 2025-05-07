@extends('layouts.doctor')
@section('content')


@if (auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::Active)
    
<div class="row">
    <div class="col-lg-12">
        <div>
         <div class="d-flex">
             <!-- Nav tabs -->
             <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                 <li class="nav-item">
                     <a class="nav-link fs-14  {{!request("facilities")}}  " data-bs-toggle="tab"  href="#overview" role="tab">
                         <i class="ri-airplay-fill d-inline-block d-m"></i> <span class=" d-md-inline-block">بياناتي الآساسية</span>
                     </a>
                 </li>
                 <li class="nav-item">
                    <a class="nav-link fs-14" data-bs-toggle="tab" href="#facilities" role="tab">
                        <i class="fa fa-hospital d-inline-block d-m"></i> <span class="d-md-inline-block">منشآتي الطبية</span>
                    </a>
                </li>
                
                 <li class="nav-item">
                     <a class="nav-link fs-14" data-bs-toggle="tab"  href="#licences" role="tab">
                         <i class="ri-list-unordered d-inline-block d-m"></i> <span class=" d-md-inline-block">أذونات المزاولة</span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link fs-14" data-bs-toggle="tab"   href="#requests" role="tab">
                         <i class="ri-folder-4-line d-inline-block d-m"></i> <span class=" d-md-inline-block">اوراق الخارج</span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link fs-14" data-bs-toggle="tab" href="#invoices"  role="tab">
                            <i class="fa fa-file d-inline-block d-m"></i> <span class=" d-md-inline-block">الفواتير</span>
                        </a>
                    </li>
 
                    
 
 
                    <li class="nav-item">
                     <a class="nav-link fs-14" data-bs-toggle="tab" href="#change-password"  role="tab">
                            <i class="ri-lock-password-line d-inline-block d-m"></i> <span class=" d-md-inline-block">تغيير كلمة المرور</span>
                        </a>
                    </li>
 
                    
 
                 <li class="nav-item">
                     <a class="nav-link fs-14" href="{{route('doctor.logout')}}"  role="tab">
                            <i class="ri-logout-box-line d-inline-block d-m"></i> <span class=" d-md-inline-block">تسجيل خروج</span>
                        </a>
                    </li>
 
 
             </ul>
         </div>
            <div class="tab-content pt-4 text-muted">
                     <div class="tab-pane {{(!request('licences') && !request('tickets') && !request('requests') && !request('invoices') && !request('change-password') ) ? "active" : ""}} " id="overview" role="tabpanel">
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="card">
                                     <div class="card-body">
                                         <h4 class="font-weight-bold text-primary" style="font-weight: bold!important;">
                                             <i class="fas fa-user text-primary"></i> البيانات الشخصية
                                         </h4>
                                         <div class="list-group">
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-id-badge text-info"></i> نوع الطبيب</span>
                                                 <span class="badge bg-primary">
                                                     {{ auth('doctor')->user()->type->label() }}
                                                 </span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-barcode text-info"></i> كود الطبيب</span>
                                                 <span>{{ auth('doctor')->user()->code }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-hashtag text-info"></i> الرقم النقابي الأول</span>
                                                 <span>{{ auth('doctor')->user()->doctor_number }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-user text-info"></i> الاسم</span>
                                                 <span>{{ auth('doctor')->user()->name }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-user-circle text-info"></i> الاسم بالإنجليزية</span>
                                                 <span>{{ auth('doctor')->user()->name_en }}</span>
                                             </div>
                                             @if (auth('doctor')->user()->type == "libyan")
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-id-card text-info"></i> الرقم الوطني</span>
                                                 <span>{{ auth('doctor')->user()->national_number }}</span>
                                             </div>
                                             @endif
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-female text-info"></i> اسم الأم</span>
                                                 <span>{{ auth('doctor')->user()->mother_name }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-flag text-info"></i> الدولة</span>
                                                 <span>{{ auth('doctor')->user()->country->name ?? '-' }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-birthday-cake text-info"></i> تاريخ الميلاد</span>
                                                 <span>{{ auth('doctor')->user()->date_of_birth ? auth('doctor')->user()->date_of_birth->format('Y-m-d') : '-' }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-heart text-info"></i> الحالة الاجتماعية</span>
                                                 <span>{{ auth('doctor')->user()->marital_status->label() }}</span>
                                             </div>
                                         </div>
                                         <h4 class="font-weight-bold text-primary mt-4">
                                             <i class="fas fa-info-circle text-primary"></i> بيانات إضافية
                                         </h4>
                                         <div class="list-group">
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-venus-mars text-info"></i> الجنس</span>
                                                 <span>{{ auth('doctor')->user()->gender->label() }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-passport text-info"></i> رقم الجواز</span>
                                                 <span>{{ auth('doctor')->user()->passport_number }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-calendar-alt text-info"></i> تاريخ انتهاء الجواز</span>
                                                 <span>{{ auth('doctor')->user()->passport_expiration ? auth('doctor')->user()->passport_expiration->format('Y-m-d') : '-' }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-map-marker-alt text-info"></i> الإقامة</span>
                                                 <span>{{ auth('doctor')->user()->address }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-phone text-info"></i> رقم الهاتف</span>
                                                 <span>{{ auth('doctor')->user()->phone }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-phone-square text-info"></i>رقم الواتساب </span>
                                                 <span>{{ auth('doctor')->user()->phone_2 }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-envelope text-info"></i> البريد الإلكتروني</span>
                                                 <span>{{ auth('doctor')->user()->email }}</span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-check-circle text-info"></i> الاشتراك السنوي</span>
                                                 <span class="badge {{auth('doctor')->user()->membership_status->badgeClass()}}">
                                                     {{ auth('doctor')->user()->membership_status->label() }}
                                                 </span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-calendar-check text-info"></i> الاشتراك السنوي</span>
                                                 <span>{{ auth('doctor')->user()->membership_expiration_date ? auth('doctor')->user()->membership_expiration_date->format('Y-m-d') : '-' }}</span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="tab-pane {{ request('facilities') ? 'active' : '' }}" id="facilities" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold text-primary mb-3">
                                    <i class="fa fa-hospital text-primary"></i> منشآتي الطبية
                                </h4>
                    
                                <a href="{{ route('doctor.medical-facilities.create') }}" class="btn btn-success btn-sm mb-3">
                                    <i class="fa fa-plus text-light"></i> تسجيل منشأة جديدة
                                </a>
                    
                                <div class="list-group">
                                    @forelse(auth('doctor')->user()->medicalFacilities as $facility)
                                    <div class="list-group-item mb-3 border border-3 rounded">
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 pt-2">
                                            <span><i class="fa fa-building text-info"></i> اسم المنشأة:</span>
                                            <span>{{ $facility->name }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 pt-2">
                                            <span><i class="fa fa-map-marker-alt text-info"></i> العنوان:</span>
                                            <span>{{ $facility->address }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 pt-2">
                                            <span><i class="fa fa-phone text-info"></i> رقم الهاتف:</span>
                                            <span>{{ $facility->phone_number }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 pt-2">
                                            <span><i class="fa fa-info-circle text-info"></i> الحالة :</span>
                                            <span class="badge {{ $facility->membership_status->badgeClass() }}">
                                                {{ $facility->membership_status->label() }}
                                            </span>
                                        </div>
                                
                                        {{-- عرض الملفات المرتبطة --}}
                                        <div class="border-top pt-3 mt-3">
                                            <h6 class="text-primary"><i class="fa fa-paperclip"></i> الملفات المرفقة:</h6>
                                            @if($facility->files->count())
                                                <ul class="list-group list-group-flush">
                                                    @foreach($facility->files as $file)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>{{ $file->fileType->name ?? '—' }}</span>
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fa fa-eye"></i> عرض الملف
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">لا توجد ملفات مرفقة لهذه المنشأة.</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-3 border rounded">لا توجد منشآت مسجلة.</div>
                                @endforelse
                                
                                </div>
                            </div>
                        </div>
                    </div>

 
 
                     <div class="tab-pane {{ request('change-password') ? "active" : ""}} " id="change-password" role="tabpanel">
                             <div class="row">
                                 <div class="card">
                                     <div class="card-body">
                                         <h4 class="font-weight-bold text-primary mb-3">
                                             <i class="fa fa-list-alt text-primary"></i> تغيير كلمة المرور
                                         </h4>
                                         <form action="{{ route(get_area_name().'.profile.change-password') }}" method="POST" onsubmit="return validatePasswords()">
                                             @csrf
                                             
                                             {{-- Current Password --}}
                                             <div class="mb-3 position-relative">
                                                 <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                                                 <div class="input-group">
                                                     <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                                     <span class="input-group-text" onclick="togglePassword('current_password')">
                                                         <i class="fas fa-eye" id="eye_current_password"></i>
                                                     </span>
                                                 </div>
                                                 @error('current_password')
                                                     <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                                 @enderror
                                             </div>
                                             
                                             {{-- New Password --}}
                                             <div class="mb-3 position-relative">
                                                 <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                                 <div class="input-group">
                                                     <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                                     <span class="input-group-text" onclick="togglePassword('password')">
                                                         <i class="fas fa-eye" id="eye_password"></i>
                                                     </span>
                                                 </div>
                                                 @error('password')
                                                     <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                                 @enderror
                                             </div>
                                             
                                             {{-- Confirm New Password --}}
                                             <div class="mb-3 position-relative">
                                                 <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                                 <div class="input-group">
                                                     <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                                     <span class="input-group-text" onclick="togglePassword('password_confirmation')">
                                                         <i class="fas fa-eye" id="eye_password_confirmation"></i>
                                                     </span>
                                                 </div>
                                             </div>
                                             
                                             <button type="submit" class="btn btn-primary w-100">تحديث كلمة المرور</button>
                                         </form>
                                     </div>
                                 </div>
                             </div>
                     </div>
 
                     
 
 
                     <div class="tab-pane {{(request('requests')) ? "active" : ""}}  " id="requests" role="tabpanel">
                         <div class="row">
                             <div class="card">
                                 <div class="card-body">
                                     <div class="doctor-requests">
                                         <h4 class="font-weight-bold text-primary mb-3">
                                             <i class="fa fa-list-alt text-primary"></i> اوراق الخارج 
                                         </h4>
 
                                         <a href="{{ route(get_area_name() . '.doctor-requests.create') }}" class="btn btn-success btn-sm mb-3">
                                             <i class="fa fa-plus text-light"></i> آضف طلب جديد </a>
 
                                         <div class="list-group">
                                             @forelse(auth('doctor')->user()->doctor_mails as $request)
                                                 <div class="list-group-item mb-3 border border-3 rounded">
                                                    
                                                     <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                         <span><i class="fa fa-map-marker-alt text-info"></i> الايميلات :</span>
                                                         <ul class="mb-0 ps-3">
                                                            @foreach($request->emails as $e) <li>{{ $e }}</li> @endforeach
                                                          </ul>
                                                     </div>
                                                     
                                                     <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                         <span><i class="fa fa-sticky-note text-info"></i> الملاحظات:</span>
                                                         <span>{{ $request->notes ?? '-' }}</span>
                                                     </div>


                                                     <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-sticky-note text-info"></i> الحالة:</span>
                                                        @php
                                                        $badge = [
                                                          'under_approve'  => 'bg-warning',
                                                          'under_payment'  => 'bg-info',
                                                          'under_proccess' => 'bg-primary',
                                                          'done'      => 'bg-success',
                                                            'under_edit' => 'bg-secondary',
                                                          'failed'         => 'bg-danger',
                                                        ][$request->status] ?? 'bg-secondary';
                                      
                                                        $label = [
                                                          'under_approve'  => 'قيد الموافقة',
                                                          'under_payment'  => 'قيد الدفع',
                                                          'under_proccess' => 'قيد التجهيز',
                                                          'done'      => 'مكتمل',
                                                          'under_edit' => 'قيد التعديل',
                                                          'failed'         => 'فشل',
                                                        ][$request->status] ?? 'غير معروف';
                                                      @endphp
                                                      <span class="badge {{ $badge }}">{{ $label }}</span>
                                                    </div>

                                                    

                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-sticky-note text-info"></i>تاريخ الادخال:</span>
                                                        <span>{{ $request->created_at ?? '-' }}</span>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-dollar-sign text-info"></i> قيمة الفاتورة :</span>
                                                        <span>{{ $request->grand_total ?? '-' }} د.ل </span>
                                                    </div>

                                                
                                                     <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                         <span><i class="fa fa-file-invoice text-info"></i> حالة الفاتورة:</span>
                                                         <span class="badge {{ $request->invoice ? $request->invoice->status->badgeClass() : 'bg-secondary' }}">
                                                             {{ $request->invoice ? $request->invoice->status->label() : 'لا توجد فاتورة' }}
                                                         </span>
                                                     </div>


                                                   @if ($request->status == "under_edit")
                                                        <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                            <a class="btn btn-primary text-light mt-3" href="{{route("doctor.doctor-mails.show", $request)}}" >عرض الملف</a>
                                                        </div>
                                                   @endif

                                                 </div>
                                             @empty
                                                 <div class="text-center p-3 border rounded">لا توجد طلبات مسجلة.</div>
                                             @endforelse
                                         </div>
                                     </div>
                                     
                                 </div>
                             </div>
                         </div>
                 </div>
 
 
 
 
                 <div class="tab-pane {{(request('invoices')) ? "active" : ""}}  " id="invoices" role="tabpanel">
                     <div class="card">
                         <div class="card-body">
                             <div class="invoices-list">
                                 <h4 class="font-weight-bold text-primary mb-3">
                                     <i class="fa fa-file-invoice text-primary"></i> قائمة الفواتير
                                 </h4>
                                 <div class="list-group">
                                     @forelse(auth('doctor')->user()->invoices as $invoice)
                                         <div class="list-group-item mb-3 border border-3 rounded">
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-hashtag text-info"></i> رقم الفاتورة:</span>
                                                 <span>{{ $invoice->invoice_number }}</span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-user text-info"></i> الاسم:</span>
                                                 <span>{{ $invoice->invoiceable?->name }}</span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-info-circle text-info"></i> الوصف:</span>
                                                 <span>{{ $invoice->description }}</span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-user-circle text-info"></i> المستخدم:</span>
                                                 <span>{{ $invoice->user?->name ?? '-' }}</span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-key text-info"></i> رقم الإذن:</span>
                                                 <span>{{ $invoice->license_id ?? '-' }}</span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-money-bill text-info"></i> المبلغ:</span>
                                                 <span>{{ number_format($invoice->amount, 2) }} د.ل</span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-info-circle text-info"></i> الحالة:</span>
                                                 <span class="badge {{$invoice->status->badgeClass()}}">
                                                     {{$invoice->status->label()}}
                                                 </span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-ban text-info"></i> سبب الإعفاء:</span>
                                                 <span>{{ $invoice->relief_reason ?? '-' }}</span>
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                 <span><i class="fa fa-calendar text-info"></i> تاريخ الإنشاء:</span>
                                                 <span>{{ $invoice->created_at->format('Y-m-d') }}</span>
                                             </div>
                                         </div>
                                     @empty
                                         <div class="text-center p-3 border rounded">لا توجد فواتير متاحة.</div>
                                     @endforelse
                                 </div>
                             </div>
                             
                         </div>
                     </div>
              </div>
 
 
 
 
                 <div class="tab-pane {{request('licences') ? "active" : ""}} " id="licences" role="tabpanel">
                     <div class="card">
                         <div class="card-body">
                             <h4 class="font-weight-bold text-primary">
                                 <i class="fas fa-id-card text-primary"></i> الرخص
                             </h4>
 
                             <div class="row">
                                     {{-- check if the last licence of doctor has expired --}}
                                     @if (auth('doctor')->user()->licenses->last() && auth('doctor')->user()->licenses->last()->expiry_date < now())
                                     <div class="col-md-12">
                                         <div class="alert alert-danger">
                                             <i class="fas fa-exclamation-triangle"></i> يبدو ان اخر رخصة لديك قد انتهت، يرجى تجديد الرخصة للمتابعة.
                                         </div>
                                     @endif
                                     @if (!auth('doctor')->user()->licenses->last() )
                                     <div class="col-md-12">
                                         <div class="alert alert-danger">
                                             <i class="fas fa-exclamation-triangle"></i> يبدو ان ليس لديك رخصة مزاولة، يرجى اضافة رخصة للمتابعة.
                                         </div>
                                     </div>
                                     @endif
                             </div>
 
                             <div class="list-group p-3">
                                 @foreach (auth('doctor')->user()->licenses as $licence)
                                 <div class="list-group-item border-3">
                                     <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                         <h2 class="text-primary" style="font-weight: bold!important;">رقم الإذن : {{ "#".$licence->id }}</h2>
                                     </div>
                                     <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                         <span><i class="fas fa-user text-info"></i> المرخص له:</span>
                                         <span>{{ $licence->licensable->name }}</span>
                                     </div>
                                     <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                         <span><i class="fas fa-calendar-alt text-info"></i> تاريخ الإصدار:</span>
                                         <span>{{ $licence->issued_date }}</span>
                                     </div>
                                         <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                             <span><i class="fas fa-id-card text-info"></i>  حالة الرخصة  :</span>
                                             <span>{!! $licence->status_badge !!}</span>
                                             </div>
                                         <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                             <span><i class="fas fa-laptop text-info"></i> مكان العمل :</span>
                                             <span>{{ $licence->medicalFacility->name }}</span>
                                         </div>
                                     <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                         <span><i class="fas fa-calendar-check text-info"></i> تاريخ الانتهاء:</span>
                                         <span>{{ $licence->expiry_date }}</span>
                                     </div>
                                     
                                     @if ($licence->status == 'active')
                                     <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                        <span><i class="fas fa-download text-info"></i> تحميل الرخصة :</span>
                                            <a href="{{route('doctor.licences.print', $licence)}}" class="btn btn-primary text-light">تحميل</a>
                                    </div>
                                     @endif
                                     
                                 </div>
                                 @endforeach
                             </div>
                         </div>
                     </div>
                     </div>
         </div>
 
 
         </div>
         </div>
        
    </div>
 </div>
 @elseif(auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::Pending)

 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/pending.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong>لم يتم تفعيل حسابك بعد</strong>
        </h1>

        <p class="text-center h3">نحن الان في صدد مراجعة معلوماتك  ٫ سنعلمك قريباََ بالتفاصيل عبر بريدك الالكتروني والنظام  </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>

 @elseif( auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::InitApprove )
 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/init-approve.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong class="text-success">تم قبولك بشكل مبدئي</strong>
        </h1>

        <p class="text-center h3"> يجب عليك زيارة الفرع المسجل به في يوم {{ auth('doctor')->user()->visiting_date }} وذلك لإستكمال الإجراءات </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>

 @elseif( auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::InActive )
 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/inactive.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong class="text-danger">عضويتك غير مفعله </strong>
        </h1>

        <p class="text-center h3"> يجب عليك زيارة الفرع الخاص بك لمزيد من التفاصيل </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>

 @elseif( auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::Rejected )
 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/rejection.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong class="text-danger">تم الرفض</strong>
        </h1>

        <p class="text-center h3"> تم الرفض طلبك للانضمام لنقابة الاطباء وذلك لسبب : <strong class="text-danger">{{auth('doctor')->user()->notes}}</strong> يرجى مراجعة الفرع لتفاصيل اكثر </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>
@endif



@endsection

@section('scripts')
<script>
    function validatePasswords() {
        let newPassword = document.getElementById('password').value;
        let confirmPassword = document.getElementById('password_confirmation').value;
        if (newPassword !== confirmPassword) {
            alert('كلمة المرور الجديدة وتأكيد كلمة المرور غير متطابقين!');
            return false;
        }
        return true;
    }

    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        let eyeIcon = document.getElementById('eye_' + fieldId);
        if (field.type === "password") {
            field.type = "text";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
