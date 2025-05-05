@extends('layouts.' . get_area_name())
@section('title', 'تعديل البريد الإلكتروني')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title text-white mb-0">تعديل البريد الإلكتروني</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.emails.update', $email->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-warning">تحديث</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
