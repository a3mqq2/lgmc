@extends('layouts.' . get_area_name())

@section('title', 'عرض التذكرة')

@section('content')
<div class="row">
    {{-- Left column: Ticket text & replies --}}
    <div class="col-md-8 mb-3">

        {{-- Ticket text --}}
     

        {{-- Ticket replies --}}

        <button 
        class="btn btn-primary btn-sm mb-3" 
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

              <div class="mb-3">
                  <label for="replyType" class="form-label">نوع الرد</label>
                  <select 
                      name="replyType" 
                      id="replyType" 
                      class="form-control @error('replyType') is-invalid @enderror"
                      required
                  >
                      <option value="external" selected>خارجي</option>
                      <option value="internal">داخلي</option>
                  </select>
                  @error('replyType')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>



              <div class="mb-3">
               <label for="department" class="form-label">إحالة التذكرة قسم</label>
               <select 
                   name="department" 
                   id="department" 
                   class="form-control @error('department') is-invalid @enderror"
               >
                   <option value="">بدون إحالة</option>
                   @foreach(App\Enums\Department::cases() as $department)
                       <option {{$department->value == $ticket->department->value ? "selected" : ""}} value="{{ $department->value }}">
                           {{ $department->label() }}
                       </option>
                   @endforeach
               </select>
               @error('category')
                   <div class="invalid-feedback">{{ $message }}</div>
               @enderror
           </div>


              <div class="mb-3">
                  <label for="assignToUser" class="form-label">إحالة التذكرة لمستخدم</label>
                  <select 
                      name="assignToUser" 
                      id="assignToUser" 
                      class="form-control @error('assignToUser') is-invalid @enderror"
                  >
                      <option value="">بدون إحالة</option>
                      @foreach($users as $user)
                          <option value="{{ $user->id }}">
                              {{ $user->name }}
                          </option>
                      @endforeach
                  </select>
                  @error('assignToUser')
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

              <button type="submit" class="btn btn-success btn-sm">
                  <i class="fa fa-send"></i> إرسال الرد
              </button>
          </form>
      </div>
  </div>


         {{-- Existing replies (Accordion) --}}
         @if(isset($replies) && $replies->count() > 0)
         <div class=" mt-3" id="ticketRepliesAccordion">
             @foreach($replies as $index => $reply)
             <div class="card border">
               <div class="card-header text-light
                  {{$reply->reply_type->value == "internal" ? 'bg-primary' : 'bg-success'}}
               ">
                <i class="fa fa-user"></i>
                <span class="ms-2">
                    {{ $reply->author->name ?? 'مستخدم مجهول' }} - 
                    <small class="text-light">{{ $reply->created_at->format('Y-m-d H:i') }}</small>
                </span>
               </div>
               <div class="card-body">
                <p class="mb-1">{!! $reply->body !!}</p>
                <span class="badge bg-dark">
                    {{ $reply->reply_type->value == "internal" ? "داخلي" : "خارجي" }}
                </span>
               </div>


           </div>
             @endforeach
         </div>
     @else
         <p class="text-muted mt-3">لا توجد ردود حتى الآن.</p>
     @endif

     <div class="card mb-3">
      <div class="card-header bg-primary text-light">
          <i class="fa fa-file-text-o"></i> نص التذكرة
      </div>
      <div class="card-body">
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
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <i class="fa fa-info-circle"></i> بيانات التذكرة
            </div>
            <div class="card-body">
                <ul class="list-group mb-3">
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

                @if($ticket->attachement)
                    <div class="mb-3">
                        <strong>المرفقات:</strong><br>
                        <a href="{{ asset('storage/' . $ticket->attachement) }}" class="btn btn-sm btn-info mt-1" target="_blank">
                            <i class="fa fa-paperclip"></i> عرض المرفق
                        </a>
                    </div>
                @endif

                <a href="{{ route(get_area_name() . '.tickets.index') }}" class="btn btn-secondary w-100">
                    <i class="fa fa-arrow-left"></i> العودة إلى قائمة التذاكر
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle closeTicket checkbox based on replyType
    const replyTypeSelect = document.getElementById('replyType');
    const closeTicketCheckbox = document.getElementById('closeTicket');

    function toggleCloseOption() {
        if (replyTypeSelect.value === 'internal') {
            closeTicketCheckbox.disabled = true;
            closeTicketCheckbox.checked = false;
        } else {
            closeTicketCheckbox.disabled = false;
        }
    }

    toggleCloseOption(); // run on page load
    replyTypeSelect.addEventListener('change', toggleCloseOption);
</script>
@endsection
