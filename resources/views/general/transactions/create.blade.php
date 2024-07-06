@extends('layouts.' . get_area_name())
@section('title', 'إنشاء معاملة مالية جديدة')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">إنشاء معاملة مالية جديدة</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.transactions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="vault_id" class="form-label">الخزينة</label>
                            <select class="form-control" id="vault_id" name="vault_id" required>
                                <option value="">حدد خزينة</option>
                                @foreach ($vaults as $vault)
                                    <option value="{{$vault->id}}">{{$vault->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="desc" class="form-label">الوصف</label>
                            <input type="text" class="form-control" id="desc" name="desc" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">المبلغ</label>
                            <input type="text" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">نوع المعاملة</label>
                            <select class="form-control" id="type" name="type" required onchange="updateTransactionTypes()">
                                <option value="deposit">إيداع</option>
                                <option value="withdrawal">سحب</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="transaction_type_id" class="form-label">نوع المعاملة المالية</label>
                            <select class="form-control" id="transaction_type_id" name="transaction_type_id" required>
                                <!-- Options will be populated dynamically based on user selection -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Function to update transaction types based on selected type (deposit or withdrawal)
        function updateTransactionTypes() {
            var selectedType = document.getElementById('type').value;
            var transactionTypesSelect = document.getElementById('transaction_type_id');

            // Clear existing options
            transactionTypesSelect.innerHTML = '';

            // Populate options based on selected type
            @foreach ($transactionTypes as $type)
                @if ($type->type == 'deposit')
                    if (selectedType === 'deposit') {
                        var option = document.createElement('option');
                        option.value = '{{ $type->id }}';
                        option.text = '{{ $type->name }}';
                        transactionTypesSelect.appendChild(option);
                    }
                @elseif ($type->type == 'withdrawal')
                    if (selectedType === 'withdrawal') {
                        var option = document.createElement('option');
                        option.value = '{{ $type->id }}';
                        option.text = '{{ $type->name }}';
                        transactionTypesSelect.appendChild(option);
                    }
                @endif
            @endforeach
        }

        // Initial call to populate transaction types on page load
        updateTransactionTypes();
    </script>
@endsection
