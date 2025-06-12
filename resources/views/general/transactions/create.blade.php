@extends('layouts.' . get_area_name())
@section('title', 'إنشاء معاملة مالية جديدة')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">
                        <i class="fa fa-plus-circle me-2"></i>
                        إنشاء معاملة مالية جديدة
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.transactions.store') }}" method="POST" id="transactionForm">
                        @csrf
                       
                        @if (auth()->id() == 1)
                        <div class="mb-3">
                            <label for="vault_id" class="form-label">
                                <i class="fa fa-vault me-1"></i>
                                الحساب <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" id="vault_id" name="vault_id" required>
                                <option value="">حدد حساب</option>
                                @foreach ($vaults as $vault)
                                    <option value="{{$vault->id}}" data-balance="{{$vault->balance}}">
                                        {{$vault->name}} - الرصيد: {{ number_format($vault->balance, 2) }} د.ل
                                    </option>
                                @endforeach
                            </select>
                            @error('vault_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @else 
                        <input type="hidden" name="vault_id" value="{{auth()->user()->vault_id}}">
                        <div class="mb-3">
                            <label class="form-label">الحساب المختار</label>
                            <div class="bg-light p-3 rounded">
                                <i class="fa fa-vault me-2"></i>
                                <strong>{{ auth()->user()->vault->name ?? 'لا يوجد حساب محدد' }}</strong>
                                @if(auth()->user()->vault)
                                    <br><small class="text-muted">الرصيد الحالي: {{ number_format(auth()->user()->vault->balance, 2) }} د.ل</small>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="type" class="form-label">
                                <i class="fa fa-exchange-alt me-1"></i>
                                نوع المعاملة <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">حدد نوع المعاملة</option>
                                <option value="deposit">
                                    <i class="fa fa-arrow-down"></i> إيداع
                                </option>
                                <option value="withdrawal">
                                    <i class="fa fa-arrow-up"></i> سحب
                                </option>
                            </select>
                            @error('type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="financial_category_id" class="form-label">
                                <i class="fa fa-tags me-1"></i>
                                التصنيف المالي <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" id="financial_category_id" name="financial_category_id" required>
                                <option value="">اختر نوع المعاملة أولاً</option>
                            </select>
                            <small class="text-muted">يرجى اختيار نوع المعاملة أولاً لإظهار التصنيفات المناسبة</small>
                            @error('financial_category_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">
                                <i class="fa fa-money-bill me-1"></i>
                                المبلغ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required 
                                       placeholder="أدخل المبلغ" value="{{ old('amount') }}">
                                <span class="input-group-text">د.ل</span>
                            </div>
                            <div id="balanceWarning" class="text-danger mt-1" style="display: none;">
                                <i class="fa fa-exclamation-triangle me-1"></i>
                                المبلغ أكبر من الرصيد المتاح
                            </div>
                            @error('amount')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="desc" class="form-label">
                                <i class="fa fa-file-text me-1"></i>
                                الوصف <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="desc" name="desc" rows="3" required 
                                      placeholder="أدخل وصف مفصل للمعاملة...">{{ old('desc') }}</textarea>
                            @error('desc')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route(get_area_name().'.transactions.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right me-2"></i>
                                رجوع
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fa fa-save me-2"></i>
                                إنشاء المعاملة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
// Store financial categories data globally
window.financialCategoriesData = {
    deposit: [
        @foreach($financialCategories->where('type', 'deposit') as $category)
        {
            id: {{ $category->id }},
            name: "{{ $category->name }}",
            type: "{{ $category->type }}"
        },
        @endforeach
    ],
    withdrawal: [
        @foreach($financialCategories->where('type', 'withdrawal') as $category)
        {
            id: {{ $category->id }},
            name: "{{ $category->name }}",
            type: "{{ $category->type }}"
        },
        @endforeach
    ]
};

// Wait for DOM and jQuery to be ready
$(document).ready(function() {
    console.log('DOM Ready - Initializing Transaction Form');
    
    // Initialize Select2 with error handling
    try {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                placeholder: 'اختر...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });
            console.log('Select2 initialized successfully');
        } else {
            console.warn('Select2 is not loaded, using regular selects');
        }
    } catch (error) {
        console.error('Error initializing Select2:', error);
    }

    // Get form elements with existence checks
    const elements = {
        typeSelect: document.getElementById('type'),
        categorySelect: document.getElementById('financial_category_id'),
        amountInput: document.getElementById('amount'),
        vaultSelect: document.getElementById('vault_id'),
        balanceWarning: document.getElementById('balanceWarning'),
        submitBtn: document.getElementById('submitBtn'),
        form: document.getElementById('transactionForm')
    };

    // Check if required elements exist
    if (!elements.typeSelect || !elements.categorySelect || !elements.submitBtn) {
        console.error('Required form elements not found');
        return;
    }

    console.log('Form elements found:', elements);

    // Financial categories data
    const financialCategories = window.financialCategoriesData || {
        deposit: [],
        withdrawal: []
    };

    console.log('Financial categories loaded:', financialCategories);

    // Function to update categories
    function updateCategories() {
        const selectedType = elements.typeSelect.value;
        console.log('Updating categories for type:', selectedType);
        
        // Clear current categories
        elements.categorySelect.innerHTML = '<option value="">اختر التصنيف المالي</option>';
        
        if (selectedType && financialCategories[selectedType]) {
            // Enable category select
            elements.categorySelect.disabled = false;
            
            // Add appropriate categories
            financialCategories[selectedType].forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                elements.categorySelect.appendChild(option);
            });

            // Update Select2 if available
            try {
                if (typeof $.fn.select2 !== 'undefined') {
                    $(elements.categorySelect).prop('disabled', false).trigger('change.select2');
                }
            } catch (error) {
                console.warn('Error updating Select2:', error);
            }
        } else {
            // Disable category select
            elements.categorySelect.disabled = true;
            elements.categorySelect.innerHTML = '<option value="">اختر نوع المعاملة أولاً</option>';
            
            // Update Select2 if available
            try {
                if (typeof $.fn.select2 !== 'undefined') {
                    $(elements.categorySelect).prop('disabled', true).trigger('change.select2');
                }
            } catch (error) {
                console.warn('Error updating Select2:', error);
            }
        }

        // Reset category selection
        elements.categorySelect.value = '';
        updateSubmitButton();
    }

    // Function to check balance
    function checkBalance() {
        if (!elements.vaultSelect || !elements.amountInput || elements.typeSelect.value !== 'withdrawal') {
            if (elements.balanceWarning) {
                elements.balanceWarning.style.display = 'none';
            }
            return;
        }

        const selectedVault = elements.vaultSelect.options[elements.vaultSelect.selectedIndex];
        const balance = parseFloat(selectedVault.dataset.balance || 0);
        const amount = parseFloat(elements.amountInput.value || 0);

        if (amount > balance && elements.balanceWarning) {
            elements.balanceWarning.style.display = 'block';
            elements.balanceWarning.innerHTML = `
                <i class="fa fa-exclamation-triangle me-1"></i>
                المبلغ (${amount.toLocaleString('ar-LY', {minimumFractionDigits: 2})} د.ل) أكبر من الرصيد المتاح (${balance.toLocaleString('ar-LY', {minimumFractionDigits: 2})} د.ل)
            `;
        } else if (elements.balanceWarning) {
            elements.balanceWarning.style.display = 'none';
        }

        updateSubmitButton();
    }

    // Function to update submit button
    function updateSubmitButton() {
        const typeSelected = elements.typeSelect.value !== '';
        const categorySelected = elements.categorySelect.value !== '';
        const amountValid = elements.amountInput && elements.amountInput.value && parseFloat(elements.amountInput.value) > 0;
        const balanceValid = !elements.balanceWarning || elements.balanceWarning.style.display === 'none';
        const descValid = document.getElementById('desc') && document.getElementById('desc').value.trim() !== '';

        console.log('Validation check:', {
            typeSelected,
            categorySelected,
            amountValid,
            balanceValid,
            descValid,
            amountValue: elements.amountInput ? elements.amountInput.value : 'N/A',
            categoryValue: elements.categorySelect.value,
            typeValue: elements.typeSelect.value
        });

        if (typeSelected && categorySelected && amountValid && balanceValid && descValid) {
            elements.submitBtn.disabled = false;
            elements.submitBtn.classList.remove('btn-secondary');
            elements.submitBtn.classList.add('btn-primary');
            console.log('Submit button enabled');
        } else {
            elements.submitBtn.disabled = true;
            elements.submitBtn.classList.remove('btn-primary');
            elements.submitBtn.classList.add('btn-secondary');
            console.log('Submit button disabled');
        }
    }

    // Function to update UI based on transaction type
    function updateTransactionTypeUI() {
        const selectedType = elements.typeSelect.value;
        const formElements = document.querySelectorAll('.form-control, .form-select');
        
        formElements.forEach(element => {
            element.classList.remove('border-success', 'border-danger');
            
            if (selectedType === 'deposit') {
                element.classList.add('border-success');
            } else if (selectedType === 'withdrawal') {
                element.classList.add('border-danger');
            }
        });

        // Update submit button
        if (selectedType === 'deposit') {
            elements.submitBtn.classList.remove('btn-primary', 'btn-danger');
            elements.submitBtn.classList.add('btn-success');
            elements.submitBtn.innerHTML = '<i class="fa fa-plus me-2"></i>إضافة إيداع';
        } else if (selectedType === 'withdrawal') {
            elements.submitBtn.classList.remove('btn-primary', 'btn-success');
            elements.submitBtn.classList.add('btn-danger');
            elements.submitBtn.innerHTML = '<i class="fa fa-minus me-2"></i>تنفيذ سحب';
        } else {
            elements.submitBtn.classList.remove('btn-success', 'btn-danger');
            elements.submitBtn.classList.add('btn-primary');
            elements.submitBtn.innerHTML = '<i class="fa fa-save me-2"></i>إنشاء المعاملة';
        }
    }

    // Event listeners
    elements.typeSelect.addEventListener('change', function() {
        console.log('Type changed to:', this.value);
        updateCategories();
        updateTransactionTypeUI();
        checkBalance();
    });

    if (elements.amountInput) {
        elements.amountInput.addEventListener('input', function() {
            console.log('Amount changed to:', this.value);
            checkBalance();
        });
    }

    if (elements.vaultSelect) {
        elements.vaultSelect.addEventListener('change', function() {
            console.log('Vault changed to:', this.value);
            checkBalance();
        });
    }

    elements.categorySelect.addEventListener('change', function() {
        console.log('Category changed to:', this.value);
        updateSubmitButton();
    });

    // Add listener for description field
    const descField = document.getElementById('desc');
    if (descField) {
        descField.addEventListener('input', function() {
            console.log('Description changed to:', this.value);
            updateSubmitButton();
        });
    }

    // Form submission handler
    if (elements.form) {
        elements.form.addEventListener('submit', function(e) {
            const type = elements.typeSelect.value;
            const category = elements.categorySelect.value;
            const amount = parseFloat(elements.amountInput ? elements.amountInput.value || 0 : 0);

            if (!type || !category || amount <= 0) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة بشكل صحيح');
                return false;
            }

            if (type === 'withdrawal' && elements.vaultSelect) {
                const selectedVault = elements.vaultSelect.options[elements.vaultSelect.selectedIndex];
                const balance = parseFloat(selectedVault.dataset.balance || 0);
                
                if (amount > balance) {
                    e.preventDefault();
                    alert('لا يمكن سحب مبلغ أكبر من الرصيد المتاح');
                    return false;
                }
            }

            // Show loading state
            elements.submitBtn.disabled = true;
            elements.submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>جاري الحفظ...';
        });
    }

    // Initial setup
    elements.categorySelect.disabled = true;
    try {
        if (typeof $.fn.select2 !== 'undefined') {
            $(elements.categorySelect).prop('disabled', true);
        }
    } catch (error) {
        console.warn('Error disabling Select2:', error);
    }
    
    updateCategories();
    updateSubmitButton();
    
    // Force enable submit button for testing (remove this line once working)
    console.log('Forcing submit button check...');
    setTimeout(() => {
        updateSubmitButton();
    }, 1000);
    
    console.log('Transaction form initialized successfully');
});
</script>

<style>
/* تحسينات CSS للنموذج */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-label i {
    color: #6c757d;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-control.border-success:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

.form-control.border-danger:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
    font-weight: 600;
}

.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
    color: #495057;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.text-danger {
    color: #dc3545 !important;
}

.text-muted {
    color: #6c757d !important;
}

/* أنماط خاصة بأنواع المعاملات */
.transaction-type-deposit {
    border-left: 4px solid #198754;
}

.transaction-type-withdrawal {
    border-left: 4px solid #dc3545;
}

/* تحسينات للشاشات الصغيرة */
@media (max-width: 768px) {
    .card-body {
        padding: 15px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
}

/* أنماط التحقق من الصحة */
.is-valid {
    border-color: #198754;
}

.is-invalid {
    border-color: #dc3545;
}

.valid-feedback {
    color: #198754;
}

.invalid-feedback {
    color: #dc3545;
}

/* أنماط التحميل */
.fa-spin {
    animation: fa-spin 2s infinite linear;
}

@keyframes fa-spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(359deg);
    }
}
</style>
@endpush
@endsection