@extends('layouts.' . get_area_name())

@section('title', 'إنشاء تحويل خزينة جديد')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">
                    <i class="fa fa-exchange-alt"></i> إنشاء تحويل خزينة جديد
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name() . '.vault-transfers.store') }}" method="POST">
                    @csrf

                    {{-- First Row: From Vault & To Vault --}}
                    <div class="row g-3">
                        {{-- From Vault --}}
                        <div class="col-md-6">
                            <label for="from_vault_id" class="form-label">
                                <i class="fa fa-arrow-left"></i> من الخزينة
                            </label>
                            <select 
                                name="from_vault_id" 
                                id="from_vault_id" 
                                class="form-control @error('from_vault_id') is-invalid @enderror"
                                required
                            >
                                <option value="">-- اختر الخزينة --</option>
                                @foreach($from_vaults as $vault)
                                    <option 
                                        value="{{ $vault->id }}" 
                                        {{ old('from_vault_id') == $vault->id ? 'selected' : '' }}
                                    >
                                        {{ $vault->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from_vault_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- To Vault --}}
                        <div class="col-md-6">
                            <label for="to_vault_id" class="form-label">
                                <i class="fa fa-arrow-right"></i> إلى الخزينة
                            </label>
                            <select 
                                name="to_vault_id" 
                                id="to_vault_id" 
                                class="form-control @error('to_vault_id') is-invalid @enderror"
                                required
                            >
                                <option value="">-- اختر الخزينة --</option>
                                @foreach($to_vaults as $vault)
                                    <option 
                                        value="{{ $vault->id }}" 
                                        {{ old('to_vault_id') == $vault->id ? 'selected' : '' }}
                                    >
                                        {{ $vault->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_vault_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    {{-- Second Row: Description --}}
                    <div class="row g-3">


                     <div class="col-md-12">
                        <label for="amount" class="form-label">
                            <i class="fa fa-money-bill"></i> القيمة 
                        </label>
                        <input type="number" name="amount" id="" class="form-control">
                    </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label">
                                <i class="fa fa-info-circle"></i> وصف التحويل
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4" 
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="أدخل وصفًا للتحويل (اختياري)"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <hr>

                    {{-- Form Actions --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fa fa-check"></i> حفظ
                        </button>
                        <a href="{{ route(get_area_name() . '.vault-transfers.index') }}" class="btn btn-secondary">
                            <i class="fa fa-ban"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
