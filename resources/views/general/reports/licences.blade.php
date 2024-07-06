@extends('layouts.'.get_area_name())

@section('title', 'تجديدات اذونات المزاولة خلال فترة معينة')

@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="{{route(get_area_name().'.reports.licences_print', request()->all())}}" class="btn btn-secondary mb-3"><i class="fa fa-print"></i>طباعة </a>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">تجديدات اذونات المزاولة خلال فترة معينة</div>
            <div class="card-body">
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
    </div>
</div>
@endsection
