@extends('layouts.doctor')
@section('content')
    

<div class="row">
   <div class="col-lg-12">
       <div>
           <div class="d-flex">
               <!-- Nav tabs -->
               <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                   <li class="nav-item">
                       <a class="nav-link fs-14" href="{{route('doctor.dashboard', ['overview' => 1])}}" role="tab">
                           <i class="ri-airplay-fill d-inline-block d-m"></i> <span class=" d-md-inline-block">بياناتي الآساسية</span>
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link fs-14"  href="{{route('doctor.dashboard', ['licences' => 1])}}" role="tab">
                           <i class="ri-list-unordered d-inline-block d-m"></i> <span class=" d-md-inline-block">أذونات المزاولة</span>
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link fs-14 active"  href="{{route('doctor.dashboard', ['tickets' => 1])}}"  role="tab">
                           <i class="ri-price-tag-line d-inline-block d-m"></i> <span class=" d-md-inline-block">تذاكر الدعم</span>
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link fs-14"  href="{{route('doctor.dashboard', ['requests' => 1])}}" role="tab">
                           <i class="ri-folder-4-line d-inline-block d-m"></i> <span class=" d-md-inline-block">الطلبات</span>
                       </a>
                   </li>
                   <li class="nav-item">
                    <a class="nav-link fs-14" href="{{route('doctor.dashboard', ['invoices' => 1])}}"  role="tab">
                           <i class="fa fa-file d-inline-block d-m"></i> <span class=" d-md-inline-block">الفواتير</span>
                       </a>
                   </li>

                   <li class="nav-item">
                    <a class="nav-link fs-14"   href="{{route('doctor.dashboard', ['change-password' => 1])}}"   role="tab">
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
           <div class="tab-pane active" id="tickets" role="tabpanel">
                  <div class="card">
                        <div class="card-body">
                              <h4 class="text-primary" style="font-weight: bold!important;">عرض بيانات التذكرة</h4>
                              <div class="row">
                                 {{-- Left column: Ticket text & replies --}}
                                 <div class="col-12 col-lg-8 mb-3">
                                     {{-- Ticket text --}}
                             
                                     {{-- Ticket replies --}}
                                     <button 
                                     class="btn btn-primary btn-sm mb-3 w-100" 
                                     type="button" 
                                     data-bs-toggle="collapse" 
                                     data-bs-target="#replyFormCollapse" 
                                     aria-expanded="false" 
                                     aria-controls="replyFormCollapse"
                                 >
                                     إضافة رد <i class="fa fa-plus"></i>
                                     </button>
                             
                                     <div class="collapse mb-4" id="replyFormCollapse">
                                         <div class="card card-body border border-primary">
                                             <form action="{{ route(get_area_name() . '.tickets.reply', $ticket->id) }}" method="POST">
                                                 @csrf
                             
                                                 @if ($ticket->status === App\Enums\Status::Complete)
                                                 <div class="mb-2">
                                                     <div class="alert bg-gradient-warning text-light">
                                                         <i class="fa fa-warning"></i> عند الرد على التذكرة وهي مغلقة سيتم اعادة فتحها
                                                     </div>
                                                 </div>
                                                 @endif
                             
                                                 <div class="mb-3">
                                                     <label for="replyBody" class="form-label">اكتب ردك</label>
                                                     <textarea 
                                                         name="replyBody" 
                                                         id="replyBody" 
                                                         rows="3" 
                                                         class="form-control @error('replyBody') is-invalid @enderror"
                                                         placeholder="اكتب ردك على التذكرة هنا..."
                                                         required
                                                     ></textarea>
                                                     @error('replyBody')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                             
                             
                             
                             
                                                 {{-- Close ticket checkbox --}}
                                                 <div class="mb-3 form-check">
                                                     <input 
                                                         class="form-check-input" 
                                                         type="checkbox" 
                                                         name="closeTicket" 
                                                         id="closeTicket"
                                                         value="1"
                                                     >
                                                     <label for="closeTicket" class="form-check-label">
                                                         إغلاق التذكرة بعد الرد
                                                     </label>
                                                 </div>
                             
                                                 <button type="submit" class="btn btn-success btn-sm w-100">
                                                     <i class="fa fa-send"></i> إرسال الرد
                                                 </button>
                                             </form>
                                         </div>
                                     </div>
                             
                                     {{-- Existing replies (Accordion) --}}
                                     @if(isset($replies) && $replies->count() > 0)
                                     <div class="mt-3" id="ticketRepliesAccordion">
                                         @foreach($replies as $index => $reply)
                                         <div class="card border mb-3">
                                             <div class="card-header text-light
                                                 {{$reply->reply_type->value == "internal" ? 'bg-primary' : 'bg-success'}}">
                                                 <i class="fa fa-user"></i>
                                                 <span class="ms-2">
                                                     {{ $reply->author->name ?? auth('doctor')->user()->name }} - 
                                                     <small class="text-light">{{ $reply->created_at->format('Y-m-d H:i') }}</small>
                                                 </span>
                                             </div>
                                             <div class="card-body">
                                                 <p class="mb-1">{!! $reply->body !!}</p>
                                             </div>
                                         </div>
                                         @endforeach
                                     </div>
                                     @else
                                     <p class="text-muted mt-3">لا توجد ردود حتى الآن.</p>
                                     @endif
                             
                                     <div class="card border border-3 mb-3">
                                       <div class="card-body">
                                          <h4 class="main-content-label mb-3"> نص التذكرة   </h4>

                                          <h5 class="card-title text-primary">
                                              {{ $ticket->title ?? 'عنوان التذكرة' }}
                                          </h5>
                                          <p class="card-text">
                                              {{ $ticket->body ?? 'هذا هو نص التذكرة التفصيلي...' }}
                                          </p>
                                      </div>
                                     </div>
                                 </div>
                             
                                 {{-- Right column: Ticket meta --}}
                                 <div class="col-12 col-lg-4 mb-3">
                                     <div class="card">
                                          <div class="card-body">
                                             <ul class="list-group p-0 mb-3">
                                                 @if(isset($ticket->ticket_type))
                                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                                         <strong>نوع التذكرة:</strong>
                                                         <span>
                                                             @if($ticket->ticket_type === 'doctor')
                                                                 <i class="fa fa-stethoscope text-info"></i> طبيب
                                                             @else
                                                                 <i class="fa fa-user text-success"></i> مستخدم
                                                             @endif
                                                         </span>
                                                     </li>
                                                 @endif
                             
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                     <strong>القسم:</strong>
                                                     <span class="badge {{ $ticket->department->badgeClass() }}">
                                                         {{ $ticket->department->label() }}
                                                     </span>
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                     <strong>الأولوية:</strong>
                                                     <span class="badge {{ $ticket->priority->badgeClass() }}">
                                                         {{ $ticket->priority->label() }}
                                                     </span>
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                     <strong>الحالة:</strong>
                                                     <span class="badge {{ $ticket->status->badgeClass() }}">
                                                         {{ $ticket->status->label() }}
                                                     </span>
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                     <strong>تاريخ الإنشاء:</strong>
                                                     <span>{{ $ticket->created_at->format('Y-m-d') }}</span>
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                     <strong>تاريخ الإغلاق:</strong>
                                                     <span>{{ $ticket->closed_at ? $ticket->closed_at->format('Y-m-d') : 'لم يغلق' }}</span>
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                     <strong>صاحب التذكرة:</strong>
                                                     <span>
                                                         @if($ticket->initDoctor)
                                                             <i class="fa fa-stethoscope text-info"></i> 
                                                             {{ $ticket->initDoctor->name }}
                                                         @elseif($ticket->initUser)
                                                             <i class="fa fa-user text-success"></i> 
                                                             {{ $ticket->initUser->name }}
                                                         @else
                                                             <span class="text-muted">غير محدد</span>
                                                         @endif
                                                     </span>
                                                 </li>
                                                 @if ($ticket->assignedUser)
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                     <strong> المستخدم المعين  :</strong>
                                                     <span>{{ $ticket->assignedUser->name}}</span>
                                                 </li>
                                                 @endif
                                             </ul>
                             
                                             @if($ticket->attachment)
                                                 <div class="mb-3">
                                                     <strong>المرفقات:</strong><br>
                                                     <a href="{{ asset('storage/' . $ticket->attachment) }}" class="btn btn-sm btn-info mt-1" target="_blank">
                                                         <i class="fa fa-paperclip"></i> عرض المرفق
                                                     </a>
                                                 </div>
                                             @endif
                             
                                             <a href="{{ route(get_area_name() . '.dashboard',['tickets' => 1]) }}" class="btn btn-secondary w-100">
                                                 <i class="fa fa-arrow-left"></i> العودة إلى قائمة التذاكر
                                             </a>
                                         </div>
                                     </div>
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