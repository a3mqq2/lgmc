@extends('layouts.doctor')
@section('content')
    

<div class="row">
   <div class="col-lg-12">
       <div>
        <div class="d-flex">
            <!-- Nav tabs -->
            <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link fs-14 active" data-bs-toggle="tab"  href="#overview" role="tab">
                        <i class="ri-airplay-fill d-inline-block d-m"></i> <span class=" d-md-inline-block">بياناتي الآساسية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14" data-bs-toggle="tab"  href="#licences" role="tab">
                        <i class="ri-list-unordered d-inline-block d-m"></i> <span class=" d-md-inline-block">أذونات المزاولة</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14 "   data-bs-toggle="tab"  href="#tickets"  role="tab">
                        <i class="ri-price-tag-line d-inline-block d-m"></i> <span class=" d-md-inline-block">تذاكر الدعم</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14" data-bs-toggle="tab"   href="#requests" role="tab">
                        <i class="ri-folder-4-line d-inline-block d-m"></i> <span class=" d-md-inline-block">الطلبات</span>
                    </a>
                </li>
                <li class="nav-item">
                 <a class="nav-link fs-14" data-bs-toggle="tab" href="#invoices"  role="tab">
                        <i class="fa fa-file d-inline-block d-m"></i> <span class=" d-md-inline-block">الفواتير</span>
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
                    <div class="tab-pane {{(!request('licences') && !request('tickets') && !request('requests') && !request('invoices')) ? "active" : ""}} " id="overview" role="tabpanel">
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
                                                <span>{{ auth('doctor')->user()->country->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-birthday-cake text-info"></i> تاريخ الميلاد</span>
                                                <span>{{ auth('doctor')->user()->date_of_birth ? auth('doctor')->user()->date_of_birth->format('Y-m-d') : 'N/A' }}</span>
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
                                                <span>{{ auth('doctor')->user()->passport_expiration ? auth('doctor')->user()->passport_expiration->format('Y-m-d') : 'N/A' }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-map-marker-alt text-info"></i> العنوان</span>
                                                <span>{{ auth('doctor')->user()->address }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-phone text-info"></i> رقم الهاتف</span>
                                                <span>{{ auth('doctor')->user()->phone }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-phone-square text-info"></i> رقم الهاتف 2</span>
                                                <span>{{ auth('doctor')->user()->phone_2 }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-envelope text-info"></i> البريد الإلكتروني</span>
                                                <span>{{ auth('doctor')->user()->email }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-check-circle text-info"></i> حالة العضوية</span>
                                                <span class="badge {{auth('doctor')->user()->membership_status->badgeClass()}}">
                                                    {{ auth('doctor')->user()->membership_status->label() }}
                                                </span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-check text-info"></i> تاريخ انتهاء العضوية</span>
                                                <span>{{ auth('doctor')->user()->membership_expiration_date ? auth('doctor')->user()->membership_expiration_date->format('Y-m-d') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane {{request('tickets') ? "active" : ""}} " id="tickets" role="tabpanel">
                        <a href="{{route(get_area_name().'.tickets.create')}}" class="btn btn-success btn-sm mb-3"><i class="fa fa-plus"></i>   فتح تذكرة جديدة</a>
                        <div class="card">
                            <div class="card-body">
                                <div class="tickets-list">
                                    <div class="list-group">
                                    <h4 class="font-weight-bold text-primary">
                                        <i class="fas fa-ticket-alt text-primary"></i> التذاكر
                                    </h4>
                                    
                                        @forelse (auth('doctor')->user()->tickets as $ticket)
                                            <div class="list-group-item border-4 mb-3">
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-hashtag text-info"></i> كود التذكرة:</span>
                                                    <span>{{ $ticket->slug }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-heading text-info"></i> العنوان:</span>
                                                    <span>{{ $ticket->title }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-building text-info"></i> القسم:</span>
                                                    <span class="badge {{ $ticket->department->badgeClass() }}">{{ $ticket->department->label() }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-layer-group text-info"></i> الفئة:</span>
                                                    <span class="badge {{ $ticket->category->badgeClass() }}">{{ $ticket->category->label() }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-info-circle text-info"></i> الحالة:</span>
                                                    <span class="badge {{ $ticket->status->badgeClass() }}">{{ $ticket->status->label() }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-exclamation-circle text-info"></i> الأولوية:</span>
                                                    <span class="badge d-inline-flex align-items-center gap-1 {{ $ticket->priority->badgeClass() }}">
                                                        @switch($ticket->priority->value)
                                                            @case('low')
                                                                <i class="fa fa-circle text-success"></i>
                                                                @break
                                                            @case('medium')
                                                                <i class="fa fa-circle text-warning"></i>
                                                                @break
                                                            @case('high')
                                                                <i class="fa fa-circle text-danger"></i>
                                                                @break
                                                        @endswitch
                                                        {{ $ticket->priority->label() }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-calendar-plus text-info"></i> تاريخ الإنشاء:</span>
                                                    <span>{{ $ticket->created_at->format('Y-m-d') }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-calendar-check text-info"></i> تاريخ الإغلاق:</span>
                                                    <span>{{ $ticket->closed_at ? $ticket->closed_at->format('Y-m-d') : 'لم يُغلق بعد' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                                    <span><i class="fas fa-user text-info"></i> المغلق بواسطة:</span>
                                                    <span>{{ $ticket->closedBy ? $ticket->closedBy->name : 'N/A' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-end mt-2">
                                                    <a href="{{ route(get_area_name() . '.tickets.show', $ticket) }}" class="btn btn-primary rounded btn-sm text-light ml-3">
                                                        عرض <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="list-group-item text-center">لا توجد تذاكر متاحة.</div>
                                        @endforelse
                                    </div>
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
                                            <i class="fa fa-list-alt text-primary"></i> قائمة طلبات الأطباء
                                        </h4>

                                        <a href="{{ route(get_area_name() . '.doctor-requests.create') }}" class="btn btn-success btn-sm mb-3">
                                            <i class="fa fa-plus text-light"></i> آضف طلب جديد </a>

                                        <div class="list-group">
                                            @forelse(auth('doctor')->user()->doctorRequests as $request)
                                                <div class="list-group-item mb-3 border border-3 rounded">
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-user-md text-info"></i> اسم الطبيب:</span>
                                                        <span>{{ $request->doctor->name }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-map-marker-alt text-info"></i> الفرع:</span>
                                                        <span>{{ $request->branch?->name }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-file-alt text-info"></i> نوع الطلب:</span>
                                                        <span>{{ $request->pricing->name }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-money-bill-alt text-info"></i> السعر:</span>
                                                        <span>{{ number_format($request->pricing->amount, 2) }} د.ل</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-calendar text-info"></i> تاريخ الطلب:</span>
                                                        <span>{{ $request->date->format('Y-m-d') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-info-circle text-info"></i> الحالة:</span>
                                                        <span class="badge {{ $request->status->badgeClass() }}">{{ $request->status->label() }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-sticky-note text-info"></i> الملاحظات:</span>
                                                        <span>{{ $request->notes ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-user-check text-info"></i> الموافقة بواسطة:</span>
                                                        <span>{{ $request->approvedBy->name ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-calendar-check text-info"></i> تاريخ الموافقة:</span>
                                                        <span>{{ $request->approved_at ? $request->approved_at->format('Y-m-d H:i') : '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-user-times text-info"></i> الرفض بواسطة:</span>
                                                        <span>{{ $request->rejectedBy->name ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-calendar-times text-info"></i> تاريخ الرفض:</span>
                                                        <span>{{ $request->rejected_at ? $request->rejected_at->format('Y-m-d H:i') : '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-user-check text-info"></i> الإكمال بواسطة:</span>
                                                        <span>{{ $request->doneBy->name ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-calendar-check text-info"></i> تاريخ الإكمال:</span>
                                                        <span>{{ $request->done_at ? $request->done_at->format('Y-m-d H:i') : '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-calendar-minus text-info"></i> تاريخ الإلغاء:</span>
                                                        <span>{{ $request->canceled_at ? $request->canceled_at->format('Y-m-d H:i') : '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                                                        <span><i class="fa fa-file-invoice text-info"></i> حالة الفاتورة:</span>
                                                        <span class="badge {{ $request->invoice ? $request->invoice->status->badgeClass() : 'bg-secondary' }}">
                                                            {{ $request->invoice ? $request->invoice->status->label() : 'لا توجد فاتورة' }}
                                                        </span>
                                                    </div>
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
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                                        <span><i class="fas fa-download text-info"></i> تحميل الرخصة :</span>
                                            <a href="{{route('doctor.licences.print', $licence)}}" class="btn btn-primary text-light">تحميل</a>
                                    </div>
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


@endsection