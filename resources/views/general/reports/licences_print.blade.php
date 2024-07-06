@extends('layouts.a4')

@section('title', 'تجديدات اذونات المزاولة خلال فترة معينة')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="text-center" style="margin-bottom:30px!important">تجديد اذونات المزاولة خلال الفتره : {{request('from_date')}} الى {{request('to_date')}} </h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th class="bg-light">#</th>
                        <th class="bg-light">المرخص له</th>
                        <th class="bg-light">تاريخ الإصدار</th>
                        <th class="bg-light">تاريخ الانتهاء</th>
                        <th class="bg-light">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($licences as $licence)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $licence->licensable->name }}</td>
                        <td>{{ $licence->issued_date }}</td>
                        <td>{{ $licence->expiry_date }}</td>
                        <td>{!! $licence->status_badge !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
