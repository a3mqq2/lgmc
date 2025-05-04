@extends('layouts.' . get_area_name())
@section('title', 'اضافة طلب مراسلة')

@section('content')
<div class="">
    <h4 class="mb-4">اضافة طلب مراسلة</h4>

    <form method="POST" action="{{ route(get_area_name().'.doctor-emails.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="doctor_type" value="{{ $doctor_type }}">

        {{-- اختيار الطبيب أولاً --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white"><h6 class="mb-0">اختر الطبيب</h6></div>
            <div class="card-body">
                <select name="doctor_id" id="doctor_id" class="form-control select2" required>
                    <option value="">— اختر الطبيب —</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="emails_container"></div>

        <button type="button" class="btn btn-primary my-3" id="add_email_btn">+ إضافة بريد جديد</button>

        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between">
                <span class="h5 mb-0">الإجمالي:</span>
                <span id="total_amount" class="h5 mb-0">0.00 د.ل</span>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success">حفظ</button>
        </div>
    </form>
</div>

<template id="email_template">
    <div class="card email-block mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span>📧 بريد جديد</span>
            <button type="button" class="btn btn-sm btn-danger remove-email">حذف</button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" class="form-control email-input" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">الدولة</label>
                    <select class="form-control country-select">
                        @php $countries = \App\Models\Country::orderBy('name')->get(); @endphp
                        <option value="">-</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                        <option value="other">أخرى</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="requests-container"></div>

            <button type="button" class="btn btn-outline-primary add-request mt-2">+ أضف طلب</button>
        </div>
    </div>
</template>

<template id="request_template">
    <div class="row align-items-center g-3 request-row">
        <div class="col-md-6">
            <select class="form-control pricing-select" required>
                <option value="">اختر نوع الطلب</option>
                @foreach($pricings as $p)
                    <option value="{{ $p->id }}" data-amount="{{ $p->amount }}">
                        {{ $p->name }} - {{ number_format($p->amount, 2) }} د.ل
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control file-input">
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-sm btn-danger remove-request">حذف</button>
        </div>
    </div>
</template>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    $('#doctor_id').select2({width:'100%'});

    const emailsContainer = document.getElementById('emails_container');
    const emailTpl   = document.getElementById('email_template').content;
    const reqTpl     = document.getElementById('request_template').content;
    const addEmailBtn = document.getElementById('add_email_btn');
    const totalSpan  = document.getElementById('total_amount');
    const doctorType = document.getElementById('doctor_type').value;

    const defaultPricing = {libyan:38, foreign:48, palestinian:72}[doctorType] ?? '';
    let emailIndex = 0;

    const fmt = v => Number(v).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
    const recalcTotal = () => {
        let t=0;
        emailsContainer.querySelectorAll('.pricing-select').forEach(sel=>{
            t+=parseFloat(sel.selectedOptions[0]?.dataset.amount||0);
        });
        totalSpan.textContent = fmt(t)+' د.ل';
    };

    const addRequest = (wrap, idx, pre='', lock=false) => {
        const frag = reqTpl.cloneNode(true);
        const row  = frag.querySelector('.request-row');
        const sel  = frag.querySelector('.pricing-select');
        const file = frag.querySelector('.file-input');
        const del  = frag.querySelector('.remove-request');

        sel.name  = `emails[${idx}][requests][]`;
        file.name = `emails[${idx}][files][]`;
        if(pre){ sel.value=pre; sel.disabled=true; }
        sel.addEventListener('change',recalcTotal);

        if(lock){ del.remove(); }
        else{ del.addEventListener('click',()=>{ row.remove(); recalcTotal(); }); }

        wrap.appendChild(frag);
        recalcTotal();
    };

    const addEmail = () => {
        const idx = emailIndex++;
        const frag = emailTpl.cloneNode(true);
        const block = frag.querySelector('.email-block');
        const emailIn = block.querySelector('.email-input');
        const country = block.querySelector('.country-select');
        const addReq  = block.querySelector('.add-request');
        const delCard = block.querySelector('.remove-email');
        const reqWrap = block.querySelector('.requests-container');

        emailIn.name = `emails[${idx}][email]`;
        country.name = `emails[${idx}][country_id]`;

        addReq.addEventListener('click',()=>addRequest(reqWrap,idx));
        delCard.addEventListener('click',()=>{ block.remove(); recalcTotal(); });

        addRequest(reqWrap, idx, defaultPricing, true);
        emailsContainer.appendChild(frag);
        recalcTotal();
    };

    addEmail();
    addEmailBtn.addEventListener('click', addEmail);
});
</script>
@endsection

@section('styles')
<style>
.email-block{border:1px solid #dfe3e8;border-radius:8px}
.request-row{margin-bottom:8px}
</style>
@endsection
