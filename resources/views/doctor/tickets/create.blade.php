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
                 <a class="nav-link fs-14" data-bs-toggle="tab" href="{{route('doctor.dashboard', ['invoices' => 1])}}"  role="tab">
                        <i class="fa fa-file d-inline-block d-m"></i> <span class=" d-md-inline-block">الفواتير</span>
                    </a>
                </li>
            </ul>
        </div>
           <div class="tab-content pt-4 text-muted">
           <div class="tab-pane active" id="tickets" role="tabpanel">
                  <div class="card">
                        <div class="card-body">
                           <div class="tickets-list">
                              <div class="list-group">
                                 <h4 class="font-weight-bold text-primary">
                                    <i class="fas fa-ticket-alt text-primary"></i> فتح تذكرة جديدة
                                </h4>
                                
                                <div class="row">
                                 <div class="col-md-12">
                                     <div class="card">
                                       <div class="card-body">
                                          <form action="{{ route(get_area_name() . '.tickets.store') }}" method="POST" enctype="multipart/form-data">
                                             @csrf
                         
                                             {{-- First Row: Title & Department --}}
                                             <div class="row g-3">
                                                 {{-- Ticket Title --}}
                                                 <div class="col-md-6">
                                                     <label for="title" class="form-label">
                                                         <i class="fa fa-heading"></i> عنوان التذكرة
                                                     </label>
                                                     <input 
                                                         type="text" 
                                                         name="title" 
                                                         id="title" 
                                                         class="form-control @error('title') is-invalid @enderror" 
                                                         value="{{ old('title') }}"
                                                         placeholder="أدخل عنوان التذكرة"
                                                         required
                                                     >
                                                     @error('title')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                         
                                                 {{-- Department (Example) --}}
                                                 <div class="col-md-6">
                                                     <label for="department" class="form-label">
                                                         <i class="fa fa-sitemap"></i> القسم
                                                     </label>
                                                     <select 
                                                         name="department" 
                                                         id="department" 
                                                         class="form-control @error('department') is-invalid @enderror"
                                                         required
                                                     >
                                                         <option value="">-- اختر القسم --</option>
                                                         @foreach(App\Enums\Department::cases() as $dept)
                                                             <option 
                                                                 value="{{ $dept->value }}" 
                                                                 {{ old('department') == $dept->value ? 'selected' : '' }}
                                                             >
                                                                 {{ $dept->label() }}
                                                             </option>
                                                         @endforeach
                                                     </select>
                                                     @error('department')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                                             </div> {{-- End Row --}}
                         
                                             <hr>
                         
                                             {{-- Second Row: Ticket Body & Category --}}
                                             <div class="row g-3">
                                                 {{-- Ticket Body/Description --}}
                                                 <div class="col-md-12">
                                                     <label for="body" class="form-label">
                                                         <i class="fa fa-info-circle"></i> وصف التذكرة
                                                     </label>
                                                     <textarea 
                                                         name="body" 
                                                         id="body" 
                                                         rows="4" 
                                                         class="form-control @error('body') is-invalid @enderror"
                                                         placeholder="أدخل وصفًا مفصلًا للتذكرة"
                                                         required
                                                     >{{ old('body') }}</textarea>
                                                     @error('body')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                         
                                                 {{-- Category (Example) --}}
                                                 <div class="col-md-12">
                                                     <label for="category" class="form-label">
                                                         <i class="fa fa-folder-open"></i> الفئة
                                                     </label>
                                                     <select 
                                                         name="category" 
                                                         id="category" 
                                                         class="form-control @error('category') is-invalid @enderror"
                                                         required
                                                     >
                                                         <option value="">-- اختر الفئة --</option>
                                                         @foreach(App\Enums\Category::cases() as $cat)
                                                             <option 
                                                                 value="{{ $cat->value }}" 
                                                                 {{ old('category') == $cat->value ? 'selected' : '' }}
                                                             >
                                                                 {{ $cat->label() }}
                                                             </option>
                                                         @endforeach
                                                     </select>
                                                     @error('category')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                                             </div> {{-- End Row --}}
                         
                                             <hr>
                         
                                             {{-- Third Row: Ticket Type (User/Doctor) & Priority --}}
                                             <div class="row g-3">
                                                 {{-- Ticket Type (User/Doctor) --}}
                                                 

                                               
                         
                                                 {{-- Priority (Example) --}}
                                                 <div class="col-md-6">
                                                     <label for="priority" class="form-label">
                                                         <i class="fa fa-exclamation-circle"></i> الأولوية
                                                     </label>
                                                     <select 
                                                         name="priority" 
                                                         id="priority" 
                                                         class="form-control @error('priority') is-invalid @enderror"
                                                         required
                                                     >
                                                         <option value="">-- اختر الأولوية --</option>
                                                         @foreach(App\Enums\Priority::cases() as $priority)
                                                             <option 
                                                                 value="{{ $priority->value }}" 
                                                                 {{ old('priority') == $priority->value ? 'selected' : '' }}
                                                             >
                                                                 {{ $priority->label() }}
                                                             </option>
                                                         @endforeach
                                                     </select>
                                                     @error('priority')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                                             </div> 
                         
                                             <hr>
                        
                                             <div class="row g-3">
                         
                                             
                         

                                                 <div class="col-md-6 mt-3">
                                                     <label for="attachment" class="form-label">
                                                         <i class="fa fa-paperclip"></i> المرفقات
                                                     </label>
                                                     <input 
                                                         type="file" 
                                                         name="attachment" 
                                                         id="attachment" 
                                                         class="form-control @error('attachment') is-invalid @enderror" 
                                                     >
                                                     @error('attachment')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                         
                                             </div> 
                         
                                             <hr>
                         
                                             <div class="d-flex justify-content-end mt-4">
                                                 <button type="submit" class="btn btn-success">
                                                     <i class="fa fa-check"></i> حفظ
                                                 </button>
                                                 <a href="{{ route(get_area_name() . '.dashboard') }}" class="btn btn-secondary">
                                                     <i class="fa fa-ban"></i> إلغاء
                                                 </a>
                                             </div>
                                         </form>
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
   </div>
</div>


@endsection