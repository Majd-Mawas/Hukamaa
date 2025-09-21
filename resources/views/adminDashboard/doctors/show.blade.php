@extends('adminDashboard.layout.layout')

@php
    $title = 'Doctor Details';
    $subTitle = 'View Doctor Information';
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Doctor Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- <div class="col-md-4 text-center mb-4">
                    @if ($doctor->getFirstMediaUrl('profile_picture'))
                        <img src="{{ $doctor->getFirstMediaUrl('profile_picture') }}" alt="{{ $doctor->user->name }}"
                            class="img-fluid rounded-circle" style="max-width: 200px">
                    @else
                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 200px; height: 200px">
                            <iconify-icon icon="solar:user-outline" style="font-size: 5em; color: white;"></iconify-icon>
                        </div>
                    @endif
                </div> --}}
                <div class="col-md-8">
                    <table class="table">
                        <tr>
                            <th width="200">Name</th>
                            <td class="text-break">{{ $doctor->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td class="text-break">{{ $doctor->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Birthdate</th>
                            <td class="text-break">{{ $doctor->birth_date->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td class="text-break">{{ ucfirst($doctor->gender) }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td class="text-break" style="max-width: 400px; word-wrap: break-word;">
                                {{ $doctor->address }}</td>
                        </tr>
                        <tr>
                            <th>Consultation Fee</th>
                            <td class="text-break">{{ $doctor->consultation_fee }}</td>
                        </tr>
                        <tr>
                            <th>Commission Percent</th>
                            <td class="text-break">{{ round($doctor->commission_percent) . '%' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td class="text-break">{{ $doctor->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Coverage Areas</th>
                            <td class="text-break">
                                @foreach ($doctor->coverageAreas as $area)
                                    {{ $area->name }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td class="text-break" style="max-width: 400px; word-wrap: break-word;">
                                {!! nl2br(e($doctor->title)) !!}</td>
                        </tr>
                        <tr>
                            <th>Bio</th>
                            <td class="text-break" style="max-width: 400px; word-wrap: break-word;">
                                {!! nl2br(e($doctor->experience_description)) !!}</td>
                        </tr>
                        <tr>
                            <th>Expertise Focus</th>
                            <td class="text-break" style="max-width: 400px; word-wrap: break-word;">
                                {!! nl2br(e($doctor->expertise_focus)) !!}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td class="text-break">{{ $doctor->created_at->format('F d, Y H:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Documents Gallery Section --}}
            <div class="mt-5">
                <h6 class="mb-4">Doctor's Documents</h6>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    {{-- Identity Documents --}}
                    @forelse ($doctor->getMedia('identity_document') as $document)
                        <div
                            class="hover-scale-img border border-neutral-200 dark:border-neutral-600 rounded-2xl overflow-hidden">
                            <div class="max-h-[266px] overflow-hidden">
                                {{-- @if (in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                    <div class="h-[266px] flex items-center justify-center bg-light">
                                        <iconify-icon icon="ph:image" class="text-primary"
                                            style="font-size: 64px;"></iconify-icon>
                                    </div>
                                @else
                                    <div class="h-[266px] flex items-center justify-center bg-light">
                                        @if (str_contains($document->mime_type, 'pdf'))
                                            <iconify-icon icon="ph:file-pdf" class="text-danger"
                                                style="font-size: 64px;"></iconify-icon>
                                        @elseif(str_contains($document->mime_type, 'word'))
                                            <iconify-icon icon="ph:file-doc" class="text-primary"
                                                style="font-size: 64px;"></iconify-icon>
                                        @else
                                            <iconify-icon icon="ph:file" class="text-secondary"
                                                style="font-size: 64px;"></iconify-icon>
                                        @endif
                                    </div>
                                @endif --}}
                            </div>
                            <div class="py-4 px-6">
                                <h6 class="mb-1.5">Identity Document</h6>
                                <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">View
                                    Document</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>No identity documents available
                        </div>
                    @endforelse

                    {{-- Practice License --}}
                    @forelse ($doctor->getMedia('practice_licenses') as $document)
                        <div
                            class="hover-scale-img border border-neutral-200 dark:border-neutral-600 rounded-2xl overflow-hidden">
                            <div class="max-h-[266px] overflow-hidden">
                                {{-- @if (in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                    <img src="{{ $document->getUrl() }}" alt="Practice License" class="hover-scale-img__img w-full h-full object-cover">
                                @else --}}
                                {{-- @if (in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                    <div class="h-[266px] flex items-center justify-center bg-light">
                                        <iconify-icon icon="ph:image" class="text-primary"
                                            style="font-size: 64px;"></iconify-icon>
                                    </div>
                                @else
                                    <div class="h-[266px] flex items-center justify-center bg-light">
                                        @if (str_contains($document->mime_type, 'pdf'))
                                            <iconify-icon icon="ph:file-pdf" class="text-danger"
                                                style="font-size: 64px;"></iconify-icon>
                                        @elseif(str_contains($document->mime_type, 'word'))
                                            <iconify-icon icon="ph:file-doc" class="text-primary"
                                                style="font-size: 64px;"></iconify-icon>
                                        @else
                                            <iconify-icon icon="ph:file" class="text-secondary"
                                                style="font-size: 64px;"></iconify-icon>
                                        @endif
                                    </div>
                                @endif --}}
                            </div>
                            <div class="py-4 px-6">
                                <h6 class="mb-1.5">Practice License</h6>
                                <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">View
                                    Document</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>No practice license available
                        </div>
                    @endforelse

                    {{-- Medical Certificates --}}
                    @forelse ($doctor->getMedia('medical_certificates') as $document)
                        <div
                            class="hover-scale-img border border-neutral-200 dark:border-neutral-600 rounded-2xl overflow-hidden">
                            <div class="max-h-[266px] overflow-hidden">
                                {{-- @if (in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                    <img src="{{ $document->getUrl() }}" alt="Medical Certificate"
                                        class="hover-scale-img__img w-full h-full object-cover">
                                @else --}}
                                {{-- @if (in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                    <div class="h-[266px] flex items-center justify-center bg-light">
                                        <iconify-icon icon="ph:image" class="text-primary"
                                            style="font-size: 64px;"></iconify-icon>
                                    </div>
                                @else
                                    <div class="h-[266px] flex items-center justify-center bg-light">
                                        @if (str_contains($document->mime_type, 'pdf'))
                                            <iconify-icon icon="ph:file-pdf" class="text-danger"
                                                style="font-size: 64px;"></iconify-icon>
                                        @elseif(str_contains($document->mime_type, 'word'))
                                            <iconify-icon icon="ph:file-doc" class="text-primary"
                                                style="font-size: 64px;"></iconify-icon>
                                        @else
                                            <iconify-icon icon="ph:file" class="text-secondary"
                                                style="font-size: 64px;"></iconify-icon>
                                        @endif
                                    </div>
                                @endif --}}
                            </div>
                            <div class="py-4 px-6">
                                <h6 class="mb-1.5">Medical Certificate</h6>
                                <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">View
                                    Document</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>No medical certificates available
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Back to List</a>
                {{-- <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">Edit Doctor</a> --}}
            </div>
        </div>
    </div>
@endsection
