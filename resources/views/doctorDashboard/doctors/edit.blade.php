@extends('doctorDashboard.layout.layout')

@php
    $title = 'Edit Doctor';
    $subTitle = 'Update Doctor Information';
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Doctor</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $doctor->user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $doctor->user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Specialization</label>
                        <select name="specialization_id"
                            class="form-control @error('specialization_id') is-invalid @enderror" required>
                            <option value="">Select Specialization</option>
                            @foreach ($specializations as $specialization)
                                <option value="{{ $specialization->id }}"
                                    {{ old('specialization_id', $doctor->specialization_id) == $specialization->id ? 'selected' : '' }}>
                                    {{ $specialization->specialization_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialization_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Image</label>
                        @if ($doctor->getFirstMediaUrl('profile_image'))
                            <div class="mb-2">
                                <img src="{{ $doctor->getFirstMediaUrl('profile_image') }}" alt="Current Profile Image"
                                    class="img-thumbnail" style="max-width: 150px">
                            </div>
                        @endif
                        <input type="file" name="profile_image"
                            class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="experience_description" class="form-control @error('experience_description') is-invalid @enderror"
                            rows="4">{{ old('experience_description', $doctor->experience_description) }}</textarea>
                        @error('experience_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1"
                                {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">Active Status</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Doctor</button>
                </div>
            </form>
        </div>
    </div>
@endsection
