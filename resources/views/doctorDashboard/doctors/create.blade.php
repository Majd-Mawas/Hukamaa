@extends('doctorDashboard.layout.layout')

@php
    $title = 'Add Doctor';
    $subTitle = 'Create New Doctor';
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Add New Doctor</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Specialization</label>
                        <select name="specialization_id"
                            class="form-control @error('specialization_id') is-invalid @enderror" required>
                            <option value="">Select Specialization</option>
                            @foreach ($specializations as $specialization)
                                <option value="{{ $specialization->id }}"
                                    {{ old('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                    {{ $specialization->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialization_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image"
                            class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1"
                                {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label">Active Status</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Doctor</button>
                </div>
            </form>
        </div>
    </div>
@endsection
