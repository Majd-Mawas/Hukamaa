@extends('adminDashboard.layout.layout')

@php
    $title = 'Patient Details';
    $subTitle = 'View patient information and files';
@endphp

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('admin.patients.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Patients
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Patient Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Basic Details</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Name:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $patient->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $patient->user->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Gender:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ ucfirst($patient->gender) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Birth Date:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $patient->birth_date ? $patient->birth_date->format('Y-m-d') : 'Not set' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Profile Status:</span>
                                <span class="ml-2">
                                    @if($patient->is_profile_complete)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Complete
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Incomplete
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Medical Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Medical History:</span>
                                <div class="mt-1 text-gray-900 dark:text-white">
                                    @if(!empty($patient->medical_history))
                                        <ul class="list-disc list-inside">
                                            @foreach($patient->medical_history as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">No medical history provided</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Chronic Conditions:</span>
                                <div class="mt-1 text-gray-900 dark:text-white">
                                    @if(!empty($patient->chronic_conditions))
                                        <ul class="list-disc list-inside">
                                            @foreach($patient->chronic_conditions as $condition)
                                                <li>{{ $condition }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">No chronic conditions reported</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Allergies:</span>
                                <div class="mt-1 text-gray-900 dark:text-white">
                                    @if(!empty($patient->allergies))
                                        <ul class="list-disc list-inside">
                                            @foreach($patient->allergies as $allergy)
                                                <li>{{ $allergy }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">No allergies reported</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Current Medications:</span>
                                <div class="mt-1 text-gray-900 dark:text-white">
                                    @if(!empty($patient->current_medications))
                                        <ul class="list-disc list-inside">
                                            @foreach($patient->current_medications as $medication)
                                                <li>{{ $medication }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">No current medications reported</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Files Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Patient Files</h2>

                @if($files->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($files as $file)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                            <iconify-icon icon="solar:gallery-linear" class="text-blue-500 text-xl mr-2"></iconify-icon>
                                        @elseif($file->mime_type === 'application/pdf')
                                            <iconify-icon icon="solar:file-pdf-linear" class="text-red-500 text-xl mr-2"></iconify-icon>
                                        @else
                                            <iconify-icon icon="solar:file-linear" class="text-gray-500 text-xl mr-2"></iconify-icon>
                                        @endif
                                        <span class="text-gray-900 dark:text-white font-medium truncate max-w-[200px]" title="{{ $file->file_name }}">
                                            {{ $file->file_name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    <div>Size: {{ round($file->size / 1024, 2) }} KB</div>
                                    <div>Uploaded: {{ $file->created_at->format('Y-m-d') }}</div>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ $file->getUrl() }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View
                                    </a>
                                    <a href="{{ $file->getUrl() }}" download class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Download
                                    </a>
                                </div>

                                @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                    <div class="mt-3">
                                        <img src="{{ $file->getUrl() }}" alt="{{ $file->file_name }}" class="rounded-md w-full h-32 object-cover">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <iconify-icon icon="solar:file-broken-linear" class="text-4xl mb-2 mx-auto"></iconify-icon>
                        <p>No files uploaded for this patient</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
