@extends('layouts.' . get_area_name())
@section('title', 'إضافة فاتورة جديدة')

@section('content')
<div class="mt-2">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-light">
              {{ request()->type ? 'تجديد عضويه طبيب'  : 'إضافة فاتورة جديدة' }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route(get_area_name().'.invoices.store') }}" method="POST">
                @csrf
                <div class="row">
                  <input type="hidden" name="type" value="{{request()->type}}">
                  @if (!request()->type)
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="invoiceable_type" class="form-label">نوع الجهة</label>
                        <select name="invoiceable_type" id="invoiceable_type" class="form-control @error('invoiceable_type') is-invalid @enderror" required>
                            <option value="">اختر النوع</option>
                            <option value="App\Models\Doctor" {{ old('invoiceable_type') == 'App\\Models\\Doctor' ? 'selected' : '' }}>طبيب</option>
                            <option value="App\Models\MedicalFacility" {{ old('invoiceable_type') == 'App\\Models\\MedicalFacility' ? 'selected' : '' }}>منشأة طبية</option>
                        </select>
                        @error('invoiceable_type')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                      </div>
                  </div>
                  @endif
                  <div class="{{ request()->type ? "col-md-12" : "col-md-6" }}">
                     <div class="mb-3">
                        <label for="invoiceable_id" class="form-label">الرقم النقابي</label>
                        <input type="text" name="invoiceable_id" id="invoiceable_id" class="form-control @error('invoiceable_id') is-invalid @enderror" value="{{ old('invoiceable_id') }}" required>
                        @error('invoiceable_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                     </div>
                  </div>
                  @if (request()->type)
                  <div class="col-md-12">
                    <div class="mb-3">
                       <label for="amount" class="form-label">قيمة الفاتورة</label>
                       <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                       @error('amount')
                       <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                       @enderror
                    </div>
                 </div>
                  @endif
                </div>
                @if (!request()->type)
                <div class="mb-3">
                      <label for="description" class="form-label">وصف الفاتورة</label>
                      <textarea name="description" class="form-control" cols="30" rows="5">{{ old('description') }}</textarea>
                      @error('description')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                  </div>
                @endif
               
                @if (!request()->type)
                <div class="form-check form-switch mb-3">
                  <input class="form-check-input" type="checkbox" id="previousLicensesSwitch" name="previous_licenses_switch" {{ old('previous_licenses_switch') ? 'checked' : '' }}>
                  <label class="form-check-label" for="previousLicensesSwitch">حساب الاشتركات السنوية</label>
              </div>
                <div class="row mb-3" id="previousLicensesFields" style="display:none;">
                  <div class="col-md-4">
                      <label for="from_year" class="form-label">من سنة</label>
                      <input type="number" min="1900" max="2100" name="from_year" id="from_year" class="form-control" value="{{ old('from_year') }}">
                  </div>
                  <div class="col-md-4">
                      <label for="to_year" class="form-label">إلى سنة</label>
                      <input type="number" min="1900" max="2100" name="to_year" id="to_year" class="form-control" value="{{ old('to_year') }}">
                  </div>
                  <div class="col-md-4">
                      <label for="yearly_penalty" class="form-label">ضريبة تأخير كل سنة</label>
                      <input type="number" step="0.01" name="yearly_penalty" id="yearly_penalty" class="form-control" value="{{ old('yearly_penalty') }}">
                  </div>
              </div>


              <div class="row">
                   <div class="col-md-6">
                      <div class="mb-3">
                         <label for="amount" class="form-label">قيمة الفاتورة</label>
                         <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                         @error('amount')
                         <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                         @enderror
                      </div>
                   </div>
                   <div class="col-md-6">
                      <div class="mb-3">
                         <label for="amount" class="form-label">نوع الحركة</label>
                         <select name="transaction_type_id" class="form-control select2">
                            <option value="">حدد نوع الحركة</option>
                            @foreach (\App\Models\TransactionType::all() as $transaction_type)
                            <option value="{{ $transaction_type->id }}" {{ old('transaction_type_id') == $transaction_type->id ? 'selected' : '' }}>{{ $transaction_type->name }}</option>
                            @endforeach
                         </select>
                      </div>
                   </div>
              </div>
                @endif
                <button type="submit" class="btn btn-primary">إنشاء</button>
                <a href="{{ route(get_area_name().'.invoices.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
<script>
    let switchEl = document.getElementById('previousLicensesSwitch')
    let previousLicensesFields = document.getElementById('previousLicensesFields')
    let fromYearEl = document.getElementById('from_year')
    let toYearEl = document.getElementById('to_year')
    let yearlyPenaltyEl = document.getElementById('yearly_penalty')
    let amountEl = document.getElementById('amount')
    function togglePreviousLicenses() {
      if (switchEl.checked) {
        previousLicensesFields.style.display = 'flex'
        amountEl.readOnly = true
      } else {
        previousLicensesFields.style.display = 'none'
        amountEl.readOnly = false
      }
    }
    function calculatePenalty() {
      if (switchEl.checked) {
        let fromY = parseInt(fromYearEl.value) || 0
        let toY = parseInt(toYearEl.value) || 0
        let yearly = parseFloat(yearlyPenaltyEl.value) || 0
        let years = toY >= fromY ? (toY - fromY + 1) : 0
        let total = years * yearly
        amountEl.value = total
      }
    }
    switchEl.addEventListener('change', function() {
      togglePreviousLicenses()
      calculatePenalty()
    })
    fromYearEl.addEventListener('input', calculatePenalty)
    toYearEl.addEventListener('input', calculatePenalty)
    yearlyPenaltyEl.addEventListener('input', calculatePenalty)
    togglePreviousLicenses()
</script>
@endsection
