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
                    <input type="hidden" name="type" value="{{ request()->type }}">
                    @if (!request()->type)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="invoiceable_type" class="form-label">نوع الجهة</label>
                            <select name="invoiceable_type" id="invoiceable_type" class="form-control" required>
                                <option value="">اختر النوع</option>
                                <option value="App\Models\Doctor">طبيب</option>
                                <option value="App\Models\MedicalFacility">منشأة طبية</option>
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="{{ request()->type ? 'col-md-12' : 'col-md-6' }}">
                        <div class="mb-3">
                            <label for="invoiceable_id" class="form-label">الرقم النقابي</label>
                            <input type="text" name="invoiceable_id" id="invoiceable_id" class="form-control" required>
                        </div>
                    </div>

                    @if (request()->type)
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="amount" class="form-label">قيمة الفاتورة</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                        </div>
                    </div>
                    @endif
                </div>

                @if (!request()->type)
                <div class="mb-3">
                    <label for="description" class="form-label">وصف الفاتورة</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="previousSwitch">
                    <label class="form-check-label" for="previousSwitch">حساب اشتراكات سابقة</label>
                </div>

                <div id="rankTableContainer" style="display:none">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>الصفة</th>
                                <th>من سنة</th>
                                <th>إلى سنة</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody id="rankTableBody"></tbody>
                    </table>
                    <button type="button" id="addRowBtn" class="btn btn-sm btn-outline-primary">+ إضافة بند</button>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label">قيمة الفاتورة</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">نوع الحركة</label>
                        <select name="transaction_type_id" class="form-control select2">
                            <option value="">حدد نوع الحركة</option>
                            @foreach(App\Models\TransactionType::all() as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">إنشاء</button>
                    <a href="{{ route(get_area_name().'.invoices.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="rowTemplate" style="display: none">
    <table>
        <tr>
            <td>
                <select name="ranks[]" class="form-control">
                    <option value="">-- اختر --</option>
                    @foreach(App\Models\DoctorRank::all() as $rank)
                        <option value="{{ $rank->id }}" data-price="{{ optional(App\Models\Pricing::find($rank->id))->amount ?? 0 }}">{{ $rank->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="from_years[]" min="1900" max="2100" class="form-control"></td>
            <td><input type="number" name="to_years[]" min="1900" max="2100" class="form-control"></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row">حذف</button></td>
        </tr>
    </table>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const switchEl = document.getElementById('previousSwitch');
    const container = document.getElementById('rankTableContainer');
    const tbody = document.getElementById('rankTableBody');
    const addRowBtn = document.getElementById('addRowBtn');
    const rowTemplate = document.querySelector('#rowTemplate table tr');
    const amountInput = document.getElementById('amount');

    function toggleContainer() {
        container.style.display = switchEl.checked ? 'block' : 'none';
        amountInput.readOnly = switchEl.checked;
        if (switchEl.checked) calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        tbody.querySelectorAll('tr').forEach(function (row) {
            const fromYear = parseInt(row.querySelector('input[name="from_years[]"]').value) || 0;
            const toYear = parseInt(row.querySelector('input[name="to_years[]"]').value) || 0;
            const rankSelect = row.querySelector('select[name="ranks[]"]');
            const price = parseFloat(rankSelect?.selectedOptions[0]?.dataset?.price || 0);
            const years = toYear >= fromYear ? (toYear - fromYear + 1) : 0;
            total += years * price;
        });
        amountInput.value = total.toFixed(2);
    }

    if (switchEl) {
        switchEl.addEventListener('change', toggleContainer);
    }

    if (addRowBtn && rowTemplate) {
        addRowBtn.addEventListener('click', function () {
            const newRow = rowTemplate.cloneNode(true);
            tbody.appendChild(newRow);
        });
    }

    tbody.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotal();
        }
    });

    document.addEventListener('input', function (e) {
        if (
            e.target.matches('input[name="from_years[]"]') ||
            e.target.matches('input[name="to_years[]"]') ||
            e.target.matches('select[name="ranks[]"]')
        ) {
            calculateTotal();
        }
    });

    toggleContainer();
});
</script>
@endpush
