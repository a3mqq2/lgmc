@extends('layouts.a4')
@section('title', 'طباعة قائمة الأطباء')
@section('content')

<div class="text-center mb-4">
    <h4>قائمة الأطباء</h4>
    <p>بتاريخ: {{ now()->format('Y-m-d') }}</p>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>الصفة</th>
            <th>نوع الطبيب</th>
            <th>الهاتف</th>
            <th>حالة العضوية</th>
        </tr>
    </thead>
    <tbody>
        @foreach($doctors as $index => $doctor)
            <tr
                @if ($doctor->membership_status->value == "banned")
                class="bg-gradient-danger text-light"
                @endif
            >
                <td>{{ $index + 1 }}</td>
                <td>{{ $doctor->name }}</td>
                <td>{{ $doctor->doctor_rank->name ?? '-' }}</td>
                <td>{{ $doctor->type->label() }}</td>
                <td>{{ $doctor->phone }}</td>
                <td>
                    <span class="badge {{ $doctor->membership_status->badgeClass() }}">
                        {{ $doctor->membership_status->label() }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-primary">طباعة</button>
</div>

@endsection
