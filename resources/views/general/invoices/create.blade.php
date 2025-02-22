@extends('layouts.' . get_area_name())
@section('title', 'إضافة فاتورة جديدة')

@section('content')
<div class="mt-2">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-light">إنشاء فاتورة</h5>
        </div>
        <div class="card-body">
            <form action="{{ route(get_area_name().'.invoices.store') }}" method="POST">
                @csrf

                {{-- Invoiceable Type --}}
                <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="invoiceable_type" class="form-label">نوع الجهة</label>
                        <select name="invoiceable_type" id="invoiceable_type"
                                class="form-control @error('invoiceable_type') is-invalid @enderror" required>
                            <option value="">اختر النوع</option>
                            <option value="App\Models\Doctor"
                                {{ old('invoiceable_type') == 'App\\Models\\Doctor' ? 'selected' : '' }}>
                                طبيب
                            </option>
                            <option value="App\Models\MedicalFacility"
                                {{ old('invoiceable_type') == 'App\\Models\\MedicalFacility' ? 'selected' : '' }}>
                                منشأة طبية
                            </option>
                        </select>
                        @error('invoiceable_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                          {{-- Invoiceable ID --}}
                        <div class="mb-3">
                           <label for="invoiceable_id" class="form-label">الرقم النقابي</label>
                           <input type="text" name="invoiceable_id" id="invoiceable_id"
                                 class="form-control @error('invoiceable_id') is-invalid @enderror"
                                 value="{{ old('invoiceable_id') }}" required>
                           @error('invoiceable_id')
                           <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                     </div>
 
                  </div>
                </div>
                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">وصف الفاتورة</label>
                    <textarea name="description" class="form-control" id="" cols="30" rows="5"></textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                {{-- Amount --}}
             
                <div class="row">
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label for="amount" class="form-label">قيمة الفاتورة</label>
                           <input type="number" step="0.01" name="amount" id="amount"
                                  class="form-control @error('amount') is-invalid @enderror"
                                  value="{{ old('amount') }}" required>
                           @error('amount')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                       </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label for="amount" class="form-label">قيمة الفاتورة</label>
                           <select name="transaction_type_id" id="" class="form-control select2">
                              <option value="">حدد نوع الحركة</option>
                              @foreach (\App\Models\TransactionType::all() as $transaction_type)
                                 <option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
                              @endforeach
                           </select>
                       </div>
                     </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">إنشاء</button>
                <a href="{{ route(get_area_name().'.invoices.index') }}" class="btn btn-secondary">
                    إلغاء
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
