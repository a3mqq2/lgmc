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
            <th>رقم العضوية</th>
            <th>الاسم</th>
            <th>الصفة</th>
            <th>الهاتف</th>
            <th> البريد الالكتروني</th>
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
                <td>{{ $doctor->code }}</td>
                <td>{{ $doctor->name }}</td>

                <td>{{ $doctor->rank_name ?? '-' }}</td>

                <td>{{ $doctor->phone }}</td>
                <td>{{ $doctor->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-primary">طباعة</button>
</div>

@endsection
